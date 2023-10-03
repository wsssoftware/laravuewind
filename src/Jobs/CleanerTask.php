<?php

namespace Laravuewind\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read string|Model $model
 */
readonly class CleanerTask
{

    public string $path;

    public false|Carbon $trashBinTtl;

    public Collection $modelCleaner;

    public function __construct(
        public string $disk,
        string $path,
        public bool $recursive,
        ModelFiles|array $modelCleaner,
        false|int|Carbon|null $trashBinTtl,
    ) {
        $this->path = self::pathSanitize($path);
        if (!is_array(config("filesystems.disks.$disk"))) {
            throw new \InvalidArgumentException(sprintf(
                'Disk %s is not exists in filesystems config',
                $disk
            ));
        }
        if (is_array($modelCleaner)) {
            $this->modelCleaner = collect($modelCleaner);
        } else {
            $this->modelCleaner = collect([$modelCleaner]);
        }
        $this->modelCleaner->ensure(ModelFiles::class);

        if ($trashBinTtl === null) {
            $trashBinTtl = intval(config('laravuewind.storage_cleaner.trashed_ttl'));
        }
        if (is_int($trashBinTtl)) {
            $this->trashBinTtl = Carbon::now()->addSeconds($trashBinTtl);
        } else {
            $this->trashBinTtl = $trashBinTtl;
        }
    }


    public static function pathSanitize(string $path): string
    {
        $chars = str_split($path);
        $lastChar = null;
        $newPath = '';
        foreach ($chars as $char) {
            if (in_array($char, ['/', '\\']) && $lastChar === '/') {
                continue;
            }
            $char = $char === '\\' ? '/' : $char;
            $newPath .= $char;
            $lastChar = $char;
        }

        return trim($newPath, '/');
    }

    public function getDiskFiles(): Collection
    {
        return collect(Storage::disk($this->disk)->files($this->path, $this->recursive));
    }

    public function uniqueId(): string
    {
        return $this->disk.'::'.$this->path;
    }
}
