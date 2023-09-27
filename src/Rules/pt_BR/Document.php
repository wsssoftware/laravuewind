<?php

namespace Laravuewind\Rules\pt_BR;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class Document implements ValidationRule
{

    public function __construct(
        public bool $allowCNPJ = true,
        public bool $allowCPF = true,
    ) {
        if ( ! $this->allowCNPJ && ! $this->allowCPF) {
            throw new \InvalidArgumentException('You must allow at least one document type.');
        }
    }

    public static function cpf(): self
    {
        return new self(allowCNPJ: false, allowCPF: true);
    }

    public static function cnpj(): self
    {
        return new self(allowCNPJ: true, allowCPF: false);
    }

    public static function generic(): self
    {
        return new self();
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
        $isGeneric = $this->allowCNPJ && $this->allowCPF;
        $isCNPJ = $this->allowCNPJ && ! $this->allowCPF;
        $isCPF = ! $this->allowCNPJ && $this->allowCPF;

        $result = match (true) {
            $isGeneric && ! in_array($length, [11, 14]) => __('laravuewind::validation.document.generic.size'),
            $isCNPJ && $length !== 14 => __('laravuewind::validation.document.cnpj.size'),
            $isCPF && $length !== 11 => __('laravuewind::validation.document.cpf.size'),
            $isGeneric && $length === 11 && ! $this->validateCpf($value) => __('laravuewind::validation.document.generic.invalid'),
            $isGeneric && $length === 14 && ! $this->validateCnpj($value) => __('laravuewind::validation.document.generic.invalid'),
            $isCNPJ && $length === 14 && ! $this->validateCnpj($value) => __('laravuewind::validation.document.cnpj.invalid'),
            $isCPF && $length === 11 && ! $this->validateCpf($value) => __('laravuewind::validation.document.cpf.invalid'),
            default => true,
        };
        if ($result !== true) {
            $fail($result);
        }
    }

    protected function validateCnpj(string $value): bool
    {
        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
            $sum += $value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;

        if ($value[12] != ($rest < 2 ? 0 : 11 - $rest)) {
            return false;
        }
        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
            $sum += $value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;

        if ($value[13] != ($rest < 2 ? 0 : 11 - $rest)) {
            return false;
        }
        return true;
    }

    protected function validateCpf(string $value): bool
    {
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $value[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($value[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
