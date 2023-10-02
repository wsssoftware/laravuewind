<?php

namespace Laravuewind\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravuewind\FilePond\FilePondUploadedFile;
use Laravuewind\FilePond\SaveRecipe;

class FilePond implements CastsAttributes
{
    public bool $withoutObjectCaching = true;

    public function __construct(
        protected string|SaveRecipe $recipe
    ) {
        if (!class_exists($recipe)) {
            throw new \InvalidArgumentException("Class {$recipe} does not exist.");
        }
        if (!is_subclass_of($recipe, SaveRecipe::class)) {
            throw new \InvalidArgumentException("Class {$recipe} must be a subclass of " . SaveRecipe::class);
        }
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): array|null
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
            foreach ($value as $key => $item) {
                $value[$key]['url'] = Storage::disk($item['disk'])->url($item['path']);
            }
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|null
    {
        if ($value instanceof FilePondUploadedFile) {
            return json_encode($this->recipe::fromFilePondUploadFile($value));
        }
        return $value;
    }
}
