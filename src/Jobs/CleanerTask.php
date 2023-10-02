<?php

namespace Laravuewind\Jobs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read string|Model $model
 */
readonly class CleanerTask
{

    public string $path;

    public function __construct(
        public string $disk,
        string $path,
        public bool $recursive,
        public string $model,
        public array $modelColumns,
    ) {
        $this->path = self::pathSanitize($path);
        if (!is_array(config("filesystems.disks.$disk"))) {
            throw new \InvalidArgumentException(sprintf(
                'Disk %s is not exists in filesystems config',
                $disk
            ));
        }
        if (!class_exists($model) || !is_subclass_of($model, Model::class)) {
            throw new \InvalidArgumentException(sprintf(
                'Model %s is not exists or not instance of %s',
                $model,
                Model::class
            ));
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

    public function getModelFiles(): Collection
    {
        $files = [];
        $query = $this->model::query()
            ->select($this->modelColumns)
            ->where(function (Builder$query) {
                foreach ($this->modelColumns as $column) {
                    foreach ($this->modelColumns as $modelColumn) {
                        $query->whereNotNull($column);
                    }
                }
            });

        //TODO add soft delete support

        $query->chunk(500, function (Collection $models) use (&$files) {
            foreach ($models as $model) {
                foreach ($this->modelColumns as $column) {
                    $columnFiles = $model->{$column};
                    if (is_string($columnFiles)) {
                        $files[] = $columnFiles;
                    } elseif (is_array($columnFiles)) {
                        foreach ($columnFiles as $columnFile) {
                            if (isset($columnFile['path']) && is_string($columnFile['path'])) {
                                $files[] = $columnFile['path'];
                            }
                        }
                    }
                }
            }
        });
        return collect($files);
    }

    public function getFiles(): Collection
    {
        return collect(Storage::disk($this->disk)->files($this->path, $this->recursive));
    }

    public function uniqueId(): string
    {
        return $this->disk.'::'.$this->path;
    }
}
