<?php

namespace Laravuewind\FilePond;

use Exception;
use Illuminate\Support\Facades\Crypt;

readonly class ServerId
{

    public string $folderId;

    public int $size;

    public string $encrypted;

    /**
     * @throws \Exception
     */
    private function __construct(?string $folderId = null, ?int $size = null, ?string $encrypted = null)
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
    }

    /**
     * @throws \Exception
     */
    public static function decode(string $encrypted): ServerId
    {
        return new ServerId(encrypted: $encrypted);
    }

    /**
     * @throws \Exception
     */
    public static function create(string $folderId, int $size): ServerId
    {
        return new ServerId($folderId, $size);
    }

    /**
     * @throws \Exception
     */
    public function getFilePath(): string
    {

        return '';
    }

    /**
     * @throws \Exception
     */
    public function getFolderPath(): string
    {
        return \Laravuewind\Facades\FilePond::getBasePath() . DIRECTORY_SEPARATOR . $this->folderId . DIRECTORY_SEPARATOR;
    }
}