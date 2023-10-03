<?php

namespace Laravuewind\Jobs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

readonly class ModelFiles
{
    public Collection $columns;

    public Model $model;

    public function __construct(
        string $model,
        string|array $columns,
    ) {
        if (! class_exists($model) || ! is_subclass_of($model, Model::class)) {
            throw new \InvalidArgumentException(sprintf(
                'Model %s is not exists or not instance of %s',
                $model,
                Model::class
            ));
        }
        $this->model = new $model();
        if (is_array($columns)) {
            $this->columns = collect($columns);
        } else {
            $this->columns = collect([$columns]);
        }
        $connectionName = $this->model->getConnection()?->getName() ?? 'default';
        $tableName = $this->model->getTable();
        $dtColumns = Cache::remember(
            "lvw_db_columns_from::$connectionName::$tableName",
            3600,
            fn () => Schema::getColumnListing($tableName)
        );

        $this->columns->each(function (string $column) use ($dtColumns): void {
            if (! in_array($column, $dtColumns)) {
                throw new \InvalidArgumentException(sprintf(
                    'Column "%s" is not exists in table "%s"',
                    $column,
                    $this->model->getTable()
                ));
            }
        });
    }

    public function getDbFiles(string $path): array
    {
        $files = [];
        $query = $this->model::query()
            ->select($this->columns->toArray())
            ->where(function (Builder $query) {
                foreach ($this->columns as $column) {
                    $query->orWhereNotNull($column);
                }
            });

        if ($query->hasMacro('withTrashed')) {
            $query = $query->withTrashed();
        }

        $query->chunk(500, function (Collection $models) use (&$files) {
            foreach ($models as $model) {
                foreach ($this->columns as $column) {
                    $columnFiles = $model->{$column};
                    if (is_string($columnFiles)) {
                        $files[] = trim($columnFiles, '/');
                    } elseif (is_array($columnFiles)) {
                        foreach ($columnFiles as $columnFile) {
                            if (isset($columnFile['path']) && is_string($columnFile['path'])) {

                                $files[] = trim($columnFile['path'], '/');
                            }
                        }
                    }
                }
            }
        });
        foreach ($files as $index => $file) {
            if (! str_starts_with($file, $path.'/') && $file !== $path) {
                unset($files[$index]);
            }
        }

        return $files;
    }
}
