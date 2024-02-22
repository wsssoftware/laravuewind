<?php

namespace Laravuewind\Support;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Laravuewind\Http\Resources\SelectResource;
use UnitEnum;

class SelectCollection extends Collection
{
    protected static string $keyField = 'key';
    protected static string $valueField = 'value';
    protected static string $extraField = 'extra';

    public static function primeVueFormat(): void
    {
        self::$keyField = 'code';
        self::$valueField = 'name';
    }

    protected static function getArrayType(array $items): array
    {
        $hasArrayValue = null;
        $hasArrayExtra = null;
        $differentTypes = false;
        $failed = function () {
            throw new \InvalidArgumentException('Consistency problem. All items value must have same structure.');
        };
        foreach ($items as $value) {
            if (($hasArrayValue === true && empty($value[self::$valueField])) || $hasArrayValue === false && ! empty($value[self::$valueField])) {
                $failed();
            }
            $hasArrayValue = ! empty($value[$value]);

            if (($hasArrayExtra === true && ! isset($value[self::$valueField])) || $hasArrayExtra === false && isset($value[self::$extraField])) {
                $failed();
            }
            $hasArrayExtra = isset($value[self::$extraField]);

            if (! is_bool($value) && ! is_numeric($value) && ! is_string($value)) {
                $differentTypes = true;
            }
        }
        if ($differentTypes && ! $hasArrayValue && ! $hasArrayExtra) {
            $failed();
        }
        if ($hasArrayExtra && ! $hasArrayValue) {
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
                $collection->push([self::$keyField => strval($key), self::$valueField => strval($value[self::$valueField]), self::$extraField => $value[self::$extraField]]);
            }
        } else {
            foreach ($items as $key => $value) {
                $collection->push([self::$keyField => strval($key), self::$valueField => strval($value)]);
            }
        }

        return $collection;
    }

    public static function fromEnum(string $enum, string $labelMethod = null, mixed $filter = false): SelectCollection
    {
        /** @var UnitEnum|WithFilters|string $enum */
        if (! enum_exists($enum)) {
            throw new InvalidArgumentException("Enum {$enum} not found.");
        }
        if ($filter !== false && !is_subclass_of($enum, WithFilters::class)) {
            throw new InvalidArgumentException(sprintf(
                'To use filters in enum "%s" you must implement "%s".',
                $enum,
                WithFilters::class,
            ));
        }
        $items = [];
        $cases = is_subclass_of($enum, WithFilters::class) && $filter !== false
            ? $enum::filteredCases($filter)
            : $enum::cases();
        foreach ($cases as $case) {
            $items[$case->value] = $labelMethod ? $case->{$labelMethod}() : $case->value;
        }

        return self::fromArray($items);
    }

    public static function fromPluck(
        array|Collection $items,
        string $value,
        string $key = null,
        string $extra = null
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
                    self::$keyField => is_object($item) ? $item->{$value} : $item[$value],
                    self::$extraField => is_object($item) ? $item->{$extra} : $item[$extra],
                ]];
            });
            $collection = self::fromArray($items->toArray());
        }

        return $collection;
    }

    public function sortByKey(int $options = null, $descending = false): SelectCollection
    {
        return $this->sortChooser(self::$keyField, $options, $descending)->values();
    }

    public function sortByKeyDesc(int $options = null): SelectCollection
    {
        return $this->sortByKey($options, true)->values();
    }

    public function sortByValue(int $options = null, $descending = false): SelectCollection
    {
        return $this->sortChooser(self::$valueField, $options, $descending)->values();
    }

    public function sortByValueDesc(int $options = null): SelectCollection
    {
        return $this->sortByValue($options, true)->values();
    }

    protected function sortChooser(string $field, ?int $options, $descending): SelectCollection
    {
        $options = $this->sortTypePredictor($field, $options);

        return match ($options) {
            SORT_STRING, SORT_REGULAR => $this->sortUTF8($field, $descending),
            default => $this->sortBy($field, $options, $descending),
        };
    }

    protected function sortTypePredictor(string $field, int $options = null): int
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
            $isNumeric && ! $isString => SORT_NUMERIC,
            $isString && ! $isNumeric => SORT_STRING,
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
