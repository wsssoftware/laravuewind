<?php

namespace Laravuewind\FilePond;

trait HasOptions
{
    private ?array $options = [];

    public function disk(string $disk): self
    {
        $this->options['disk'] = $disk;
        return $this;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function public(): self
    {
        $this->options['visibility'] = 'public';
        return $this;
    }

    public function resetOptions(): self
    {
        $this->options = [];
        return $this;
    }

    public function setOptions(string|array $options): self
    {
        $options = is_string($options) ? ['disk' => $options] : $options;
        $this->options = $options + $this->options;
        return $this;
    }
}
