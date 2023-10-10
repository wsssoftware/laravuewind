<?php

namespace Laravuewind\FilePond;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use const UPLOAD_ERR_OK;

class FilePondUploadedFile extends UploadedFile
{
    public const EXTENDED_FILENAME_POSTFIX = 'extended_file.tmp';

    protected ?BeforeStore $beforeStore = null;

    protected ?FilePondUploadedFile $beforeStoreCache = null;

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
        if ($this->beforeStore && $this->beforeStoreCache === null) {
            $content = $this->beforeStore
                ->setFilePondUploadFile($this)
                ->handle();

            return $this->beforeStoreCache = $this->createExtendedFilePondUploadedFile($content);
        } elseif ($this->beforeStoreCache !== null) {
            return $this->beforeStoreCache;
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

    public static function fromPath(string $path): FilePondUploadedFile
    {
        if (is_file($path) === false) {
            throw new \InvalidArgumentException('Path is not a file');
        }
        $factory = app()->make(FilePondFactory::class);
        $folderId = $factory->createFolderId();
        $serverId = $factory->createServerId($folderId, filesize($path));
        $newPath = $factory->getBasePath()."/$folderId/".basename($path);
        $factory->disk()->put($newPath, file_get_contents($path));
        return new static(
            $factory,
            $serverId,
            $factory->disk()->path($newPath),
            basename($path),
            $factory->disk()->mimeType($newPath)
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

    public function storeItem(StoreItem $item, string $name = null, array $options = []): false|array
    {
        $itemImplementations = class_implements($item);
        if (isset($itemImplementations[WithCustomFilename::class])) {
            /** @var \Laravuewind\FilePond\StoreItem|\Laravuewind\FilePond\WithCustomFilename $item */
            $name = $item->getFilename();
        }
        $file = $this->callBeforeStore() ?? $this;
        $item->setFilePondUploadFile($file);
        $extendedFile = $this->createExtendedFilePondUploadedFile($item->handle());
        $options = $item->options() + $options;
        if (! empty($name)) {
            $path = $extendedFile->storeAs(
                $item->path(),
                $name.'.'.$extendedFile->extension(),
                $options
            );
        } else {
            $path = $extendedFile->store($item->path(), $options);
        }

        if ($path === false) {
            return false;
        }

        return [
            'disk' => Arr::get($options, 'disk') ?? config('filesystems.default'),
            'path' => $path,
        ];
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
