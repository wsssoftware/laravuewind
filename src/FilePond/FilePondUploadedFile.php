<?php

namespace Laravuewind\FilePond;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use const UPLOAD_ERR_OK;

class FilePondUploadedFile extends UploadedFile
{
    public const EXTENDED_FILENAME_POSTFIX = "extended_file.tmp";

    protected ?BeforeStore $beforeStore = null;
    protected static bool $removeUploadFileOnShutdown = true;

    protected function __construct(
        protected FilePondFactory $factory,
        protected ServerId $serverId,
        string $path,
        string $originalName,
        string $mimeType = null,
        int $error = null,
        bool $test = false
    ) {
        if (self::$removeUploadFileOnShutdown) {
            register_shutdown_function(fn() => $factory->removeUpload($serverId));
        }
        parent::__construct($path, $originalName, $mimeType, $error, $test);
    }


    public function beforeStore(BeforeStore $beforeStore): self
    {
        $this->beforeStore = $beforeStore;
        return $this;
    }

    protected function callBeforeStore(): FilePondUploadedFile|null
    {
        if ($this->beforeStore) {
            $content = $this->beforeStore
                ->setFilePondUploadFile($this)
                ->handle();
            return $this->createExtendedFilePondUploadedFile($content);
        }
        return null;
    }

    protected function createExtendedFilePondUploadedFile(string $content): FilePondUploadedFile
    {
        $filename = sprintf('%s.%s', Str::random(), self::EXTENDED_FILENAME_POSTFIX);
        $filepath = sprintf(
            '%s%s',
            $this->serverId->getFolderPath(),
            $filename
        );
        $disk = $this->factory->disk();
        $disk->put($filepath, $content);

        return new FilePondUploadedFile(
            $this->factory,
            $this->serverId,
            $disk->path($filepath),
            $filename,
            $disk->mimeType($filepath)
        );
    }

    public static function createFromServerId(
        FilePondFactory $factory,
        ServerId $serverId,
    ): FilePondUploadedFile {
        $disk = $factory->disk();
        $diskFilePath = $serverId->getFilePath();
        $filepath = $disk->path($diskFilePath);

        return new static($factory, $serverId, $filepath, basename($filepath), $disk->mimeType($diskFilePath));
    }

    public function isValid(): bool
    {
        $isOk = UPLOAD_ERR_OK === $this->getError();
        $pathBegin = $this->factory->disk()->path($this->serverId->getFolderPath());
        return $isOk && str_starts_with($this->getPathname(), $pathBegin);
    }

    public function store($path = '', $options = []): false|string
    {
        return $this->callBeforeStore()
            ?->store($path, $options) ?? parent::store($path, $options);
    }

    public function storeAs($path, $name = null, $options = []): false|string
    {
        return $this->callBeforeStore()
            ?->storeAs($path, $name, $options) ?? parent::storeAs($path, $name, $options);
    }

    /**
     * @param  \Laravuewind\FilePond\StoreManyItem[]|Collection<int, \Laravuewind\FilePond\StoreManyItem>  $items
     */
    public function storeMany(array|Collection $items, string|array $options = []): bool
    {
        if (is_array($items)) {
            $items = collect($items);
        }
        $items->ensure(StoreManyItem::class);
        $file = $this->callBeforeStore() ?? $this;
        $itsOk = true;
        foreach ($items as $item) {
            $item->setFilePondUploadFile($file);
            $extendedFile = $this->createExtendedFilePondUploadedFile($item->handle());
            $extendedOptions = $item->options() ?? $options;
            if ($item->name() && ! $extendedFile->storeAs($item->path(), $item->name(), $extendedOptions)) {
                $itsOk = false;
            } elseif ( ! $item->name() && ! $extendedFile->store($item->path(), $extendedOptions)) {
                $itsOk = false;
            }
        }
        return $itsOk;
    }

    public function storePublicly($path = '', $options = []): false|string
    {
        return $this->callBeforeStore()
            ?->storePublicly($path, $options) ?? parent::storePublicly($path, $options);
    }

    public function storePubliclyAs($path, $name = null, $options = []): false|string
    {
        return $this->callBeforeStore()
            ?->storePubliclyAs($path, $name, $options) ?? parent::storePubliclyAs($path, $name, $options);
    }

    public static function withoutRemoveUploadFileOnShutdown(): void
    {
        static::$removeUploadFileOnShutdown = false;
    }
}