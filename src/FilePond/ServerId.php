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

    /**
     * @throws \Exception
     */
    private function __construct(FilePondFactory $factory, ?string $folderId = null, ?int $size = null, ?string $encrypted = null)
    {
        if (!empty($folderId) && !empty($size)) {
            $this->folderId = $folderId;
            $this->size = $size;
            $data = [
                'folder' => $this->folderId,
                'size' => $this->size,
            ];
            $this->encrypted = Crypt::encryptString(json_encode($data));
        } elseif (!empty($encrypted)) {
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

        return '';
    }

    public function getFolderPath(): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->folderId . DIRECTORY_SEPARATOR;
    }
}