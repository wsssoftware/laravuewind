<?php

namespace Laravuewind\Support;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravuewind\Http\Resources\SelectResource;

class SelectCollection extends Collection
{

    protected static function getArrayType(array $items): array
    {
        $hasArrayValue = null;
        $hasArrayExtra = null;
        $differentTypes = false;
        $failed = function () {throw new \InvalidArgumentException('Consistency problem. All items value must have same structure.');};
        foreach ($items as $value) {
            if (($hasArrayValue === true && empty($value['value'])) || $hasArrayValue === false && !empty($value['value'])) {
                $failed();
            }
            $hasArrayValue = !empty($value['value']);

            if (($hasArrayExtra === true && !isset($value['extra'])) || $hasArrayExtra === false && isset($value['extra'])) {
                $failed();
            }
            $hasArrayExtra = isset($value['extra']);

            if (!is_bool($value) && !is_numeric($value) && !is_string($value)) {
                $differentTypes = true;
            }
        }
        if ($differentTypes && !$hasArrayValue && !$hasArrayExtra) {
            $failed();
        }
        if ($hasArrayExtra && !$hasArrayValue) {
            $failed();
        }
        return ['hasArrayValue' => $hasArrayValue, 'hasArrayExtra' => $hasArrayExtra];
    }

    public static function fromArray(array $items): SelectCollection
    {
        $arrayType = self::getArrayType($items);
        $collection = new SelectCollection();
        if ($arrayType['hasArrayValue']) {
            foreach ($items as $key => $value) {
                $collection->push(['key' => $key, 'value' => $value['value'], 'extra' => $value['extra']]);
            }
        } else {
            foreach ($items as $key => $value) {
                $collection->push(['key' => $key, 'value' => $value]);
            }
        }

        return $collection;
    }

    public static function fromPluck(
        array|Collection $items,
        string $value,
        ?string $key = null,
        ?string $extra = null
    ): SelectCollection {
        if (is_array($items)) {
            $items = collect($items);
        }
        if (empty($extra)) {
            $collection = self::fromArray($items->pluck($value, $key)->toArray());
        } else {
            $items = $items->mapWithKeys(function ($item, $index) use ($value, $key, $extra) {
                $key = empty($key) ? $index : (is_object($item) ? $item->{$key} : $item[$key]);
                return [$key => [
                    'value' => is_object($item) ? $item->{$value} : $item[$value],
                    'extra' => is_object($item) ? $item->{$extra} : $item[$extra],
                ]];
            });
            $collection = self::fromArray($items->toArray());
        }

        return $collection;
    }

    public function sortByKey(int|null $options = null, $descending = false): SelectCollection
    {
        return $this->sortChooser('key', $options, $descending);
    }

    public function sortByKeyDesc(int|null $options = null): SelectCollection
    {
        return $this->sortByKey($options, true);
    }

    public function sortByValue(int|null $options = null, $descending = false): SelectCollection
    {
        return $this->sortChooser('value', $options, $descending);
    }

    public function sortByValueDesc(int|null $options = null): SelectCollection
    {
        return $this->sortByValue($options, true);
    }

    protected function sortChooser(string $field, int|null $options, $descending): SelectCollection
    {
        $options = $this->sortTypePredictor($field, $options);
        return match ($options) {
            SORT_STRING, SORT_REGULAR => $this->sortUTF8($field, $descending),
            default => $this->sortBy($field, $options, $descending),
        };
    }

    protected function sortTypePredictor(string $field, int|null $options = null): int
    {
        if (is_int($options)) {
            return $options;
        }
        $isNumeric = false;
        $isString = false;
        foreach ($this->items as $item) {
            $value = Arr::get($item, $field, $item);
            if (is_numeric($value)) {
                $isNumeric = true;
            } else {
                $isString = true;
            }
        }
        return match (true) {
            $isNumeric && !$isString => SORT_NUMERIC,
            $isString && !$isNumeric => SORT_STRING,
            default => SORT_REGULAR,
        };
    }

    protected function sortUTF8(string $field, $descending): SelectCollection
    {
        setlocale(LC_ALL, config('app.locale'));
        return $this->sortBy($field, SORT_LOCALE_STRING, $descending);
    }

    public function toResource(): AnonymousResourceCollection
    {
        return SelectResource::collection($this);
    }
}