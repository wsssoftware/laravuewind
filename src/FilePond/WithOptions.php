<?php

namespace Laravuewind\FilePond;

interface WithOptions
{
    public function disk(string $disk): self;

    public function options(): array;

    public function public(): self;

    public function resetOptions(): self;

    public function setOptions(string|array $options): self;
}
