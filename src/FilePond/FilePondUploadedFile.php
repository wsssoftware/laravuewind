<?php

namespace Laravuewind\FilePond;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use const UPLOAD_ERR_OK;

class FilePondUploadedFile extends UploadedFile
{
    public const EXTENDED_FILENAME_POSTFIX = 'extended_file.tmp';

    protected ?BeforeStore $beforeStore = null;

    protected static Collection $registeredShutdowns;

    protected static bool $removeUploadFileOnShutdownAfterStore = true;

    protected function __construct(
        protected FilePondFactory $factory,
        protected ServerId $serverId,
        string $path,
        string $originalName,
        string $mimeType = null,
        int $error = null,
        bool $test = false
    ) {
        parent::__construct($path, $originalName, $mimeType, $error, $test);
    }

    protected function afterStore(false|array|string $result): false|array|string
    {
        if ($result === false) {
            return false;
        }
        $this->setRemoveUploadFIleOnShutdown();

        return $result;
    }

    public function beforeStore(BeforeStore $beforeStore): self
    {
        $this->beforeStore = $beforeStore;

        return $this;
    }

    protected function callBeforeStore(): ?FilePondUploadedFile
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
        $isOk = $this->getError() === UPLOAD_ERR_OK;
        $pathBegin = $this->factory->disk()->path($this->serverId->getFolderPath());

        return $isOk && str_starts_with($this->getPathname(), $pathBegin);
    }

    public function store($path = '', $options = []): false|string
    {
        $result = $this->callBeforeStore()
            ?->store($path, $options) ?? parent::store($path, $options);

        return $this->afterStore($result);
    }

    public function storeAs($path, $name = null, $options = []): false|string
    {
        $result = $this->callBeforeStore()
            ?->storeAs($path, $name, $options) ?? parent::storeAs($path, $name, $options);

        return $this->afterStore($result);
    }

    public function storePublicly($path = '', $options = []): false|string
    {
        $result = $this->callBeforeStore()
            ?->storePublicly($path, $options) ?? parent::storePublicly($path, $options);

        return $this->afterStore($result);
    }

    public function storePubliclyAs($path, $name = null, $options = []): false|string
    {
        $result = $this->callBeforeStore()
            ?->storePubliclyAs($path, $name, $options) ?? parent::storePubliclyAs($path, $name, $options);

        return $this->afterStore($result);
    }

    public static function withoutRemoveUploadFileOnShutdownAfterStore(): void
    {
        static::$removeUploadFileOnShutdownAfterStore = false;
    }

    protected function setRemoveUploadFIleOnShutdown(): void
    {
        if (self::$removeUploadFileOnShutdownAfterStore) {
            if (empty(self::$registeredShutdowns)) {
                self::$registeredShutdowns = collect();
            }
            $folderId = $this->serverId->folderId;
            if (! self::$registeredShutdowns->has($folderId)) {
                self::$registeredShutdowns->put($folderId, true);
                register_shutdown_function(fn () => $this->factory->removeUpload($this->serverId));
            }
        }
    }
}
