<?php

namespace Laravuewind\Rules\pt_BR;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class Phone implements ValidationRule
{

    public function __construct(
        public bool $allowCellphone = true,
        public bool $allowLocalFare = true,
        public bool $allowNonRegional = true,
        public bool $allowPhone = true,
        public bool $allowPublicServices = true,
    ) {
        if ( ! $allowCellphone && ! $allowLocalFare && ! $allowNonRegional && ! $allowPhone && ! $allowPublicServices) {
            throw new \InvalidArgumentException('At least one of the options must be true.');
        }
    }

    public static function cellphone(): self
    {
        return new self(true, false, false, false, false);
    }

    public static function generic(): self
    {
        return new self();
    }

    public static function localFare(): self
    {
        return new self(false, true, false, false, false);
    }

    public static function nonRegional(): self
    {
        return new self(false, false, true, false, false);
    }

    public static function phone(): self
    {
        return new self(false, false, false, true, false);
    }

    public static function publicServices(): self
    {
        return new self(false, false, false, false, true);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(__('validation.string'));
            return;
        }
        $value = preg_replace('/[^0-9]/i', '', $value);
        $length = strlen($value);
        $c = $this->allowCellphone;
        $lf = $this->allowLocalFare;
        $nr = $this->allowNonRegional;
        $p = $this->allowPhone;
        $ps = $this->allowPublicServices;

        $result = match (true) {
            $c && ! $lf && ! $nr && ! $p && ! $ps => $this->validateCellphone($value),
            ! $c && $lf && ! $nr && ! $p && ! $ps => $this->validateLocalFare($value),
            ! $c && ! $lf && $nr && ! $p && ! $ps => $this->validateNonRegional($value),
            ! $c && ! $lf && ! $nr && $p && ! $ps => $this->validatePhone($value),
            ! $c && ! $lf && ! $nr && ! $p && $ps => $this->validatePublicServices($value),
            default => $this->validateGeneric($value),
        };
        if (is_string($result)) {
            $fail($result);
        }
    }

    protected function validateCellphone(string $value): string|true
    {
        return preg_match('/^[1-9][0-9]9[5-9][0-9]{7}$/', $value) === 0 ?
            __('laravuewind::validation.phone.cellphone') :
            true;
    }

    protected function validateLocalFare(string $value): string|true
    {
        return preg_match('/^400[0-9]{5}$/', $value) === 0 ?
            __('laravuewind::validation.phone.local_fare') :
            true;
    }

    protected function validateNonRegional(string $value): string|true
    {
        return preg_match('/^0[3589]00[0-9]{7}$/', $value) === 0 ?
            __('laravuewind::validation.phone.non_regional') :
            true;
    }

    protected function validatePhone(string $value): string|true
    {
        return preg_match('/^[1-9][0-9][1-5][0-9]{7}$/', $value) === 0 ?
            __('laravuewind::validation.phone.phone') :
            true;
    }

    protected function validatePublicServices(string $value): string|true
    {
        return preg_match('/^1[0-9]{2}$/', $value) === 0 ?
            __('laravuewind::validation.phone.public_services') :
            true;
    }

    protected function validateGeneric(string $value): string|true
    {
        if (preg_match('/^[1-9][0-9]9$/', substr($value,  0, 3)) === 1) {
            return $this->validateCellphone($value);
        }
        if (preg_match('/^400$/', substr($value,  0, 3)) === 1) {
            return $this->validateLocalFare($value);
        }
        if (preg_match('/^0[3589]00$/', substr($value,  0, 4)) === 1) {
            return $this->validateNonRegional($value);
        }
        if (preg_match('/^[1-9][0-9][1-5][0-9]$/', substr($value,  0, 4)) === 1) {
            return $this->validatePhone($value);
        }
        if (preg_match('/^1[0-9]{2}$/', substr($value,  0, 3)) === 1) {
            return $this->validatePublicServices($value);
        }
        return __('laravuewind::validation.phone.generic');
    }
}
