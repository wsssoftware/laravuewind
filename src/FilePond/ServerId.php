<?php

namespace Laravuewind\FilePond;

use Exception;
use Illuminate\Support\Facades\Crypt;

readonly class ServerId
{
    public string $folderId;

    public int $size;

    public string $encrypted;

    private string $basePath;

    private FilePondFactory $factory;

    /**
     * @throws \Exception
     */
    private function __construct(
        FilePondFactory $factory,
        string $folderId = null,
        int $size = null,
        string $encrypted = null
    ) {
        $this->factory = $factory;
        if (! empty($folderId) && ! empty($size)) {
            $this->folderId = $folderId;
            $this->size = $size;
            $data = [
                'folder' => $this->folderId,
                'size' => $this->size,
            ];
            $this->encrypted = Crypt::encryptString(json_encode($data));
        } elseif (! empty($encrypted)) {
            $this->encrypted = $encrypted;
            $data = json_decode(Crypt::decryptString($encrypted), true);
            if ($data === null || empty($data['folder']) || empty($data['size'])) {
                throw new Exception('Invalid server ID');
            }
            $this->folderId = $data['folder'];
            $this->size = $data['size'];
        } else {
            throw new Exception('Invalid server ID');
        }
        $this->basePath = $factory->getBasePath();
    }

    /**
     * @throws \Exception
     */
    public static function decode(FilePondFactory $factory, string $encrypted): ServerId
    {
        return new ServerId($factory, encrypted: $encrypted);
    }

    /**
     * @throws \Exception
     */
    public static function create(FilePondFactory $factory, string $folderId, int $size): ServerId
    {
        return new ServerId($factory, $folderId, $size);
    }

    /**
     * @throws \Exception
     */
    public function getFilePath(): string
    {
        $files = collect($this->factory->disk()->files($this->getFolderPath()))
            ->filter(fn (string $path) => ! str_ends_with($path, FilePondUploadedFile::EXTENDED_FILENAME_POSTFIX));

        return match ($files->count()) {
            0 => throw new Exception(sprintf(
                'The upload "%s" file does not exist or has been already removed',
                $this->folderId
            )),
            1 => $files->first(),
            default => throw new Exception(sprintf('The upload "%s" has more than one file', $this->folderId)),
        };
    }

    public function getFolderPath(): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.$this->folderId.DIRECTORY_SEPARATOR;
    }
}
