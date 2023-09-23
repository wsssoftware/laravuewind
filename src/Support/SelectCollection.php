<?php

namespace Laravuewind\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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

    protected function completeSort(string $field, $options, $descending): SelectCollection {
        return match ($options) {
            SORT_STRING, SORT_REGULAR => $this->sortBy(function ($value1, $value2) use ($field) {
                $a = Arr::get($value1, $field, $value1);
                $b = Arr::get($value2, $field, $value2);
                $at = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
                $bt = iconv('UTF-8', 'ASCII//TRANSLIT', $b);

                return strcmp($at, $bt);
            }, $options, $descending),
            default => $this->sortBy($field, $options, $descending),
        };
    }

    public function sortByKey($options = SORT_NUMERIC, $descending = false): SelectCollection
    {
        return $this->completeSort('key', $options, $descending);
    }

    public function sortByKeyDesc($options = SORT_REGULAR): SelectCollection
    {
        return $this->sortByKey($options, true);
    }

    public function sortByValue($options = SORT_REGULAR, $descending = false): SelectCollection
    {
        return $this->completeSort('value', $options, $descending);
    }

    public function sortByValueDesc($options = SORT_REGULAR): SelectCollection
    {
        return $this->sortBy($options, true);
    }
}