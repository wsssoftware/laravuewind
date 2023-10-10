<?php

namespace Laravuewind\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Laravuewind\Casts\FilePond\Images;
use Laravuewind\FilePond\FilePondUploadedFile;
use Laravuewind\FilePond\SaveRecipe;

class FilePond implements CastsAttributes
{
    public bool $withoutObjectCaching = true;

    public function __construct(
        protected string|SaveRecipe $recipe
    ) {
        if (! class_exists($recipe)) {
            throw new \InvalidArgumentException("Class {$recipe} does not exist.");
        }
        if (! is_subclass_of($recipe, SaveRecipe::class)) {
            throw new \InvalidArgumentException("Class {$recipe} must be a subclass of ".SaveRecipe::class);
        }
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): null|Images
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON format.');
            }
            $isValid = true;
            foreach ($value as $key => $item) {
                if (! isset($item['disk'], $item['path'])) {
                    $isValid = false;
                    break;
                }
                if (! is_string($item['disk']) || ! is_string($item['path'])) {
                    $isValid = false;
                    break;
                }
            }
            if ($isValid) {
                return new Images($value);
            }
            throw new \InvalidArgumentException('Invalid JSON data.');
        }

        return null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value instanceof FilePondUploadedFile) {
            return json_encode($this->recipe::fromFilePondUploadFile($value));
        }
        if ($value instanceof Images) {
            return json_encode($value->toArray());
        }

        return $value;
    }
}
