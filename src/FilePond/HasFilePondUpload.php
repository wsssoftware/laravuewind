<?php

namespace Laravuewind\FilePond;

use Illuminate\Http\Request;
use Laravuewind\Facades\FilePond;

/**
 * @method mixed input(null|string $key = null, mixed $default = null)
 * @method Request merge(array $input)
 */
trait HasFilePondUpload
{
    protected function setFilePondInput(string $input): void
    {
        $serverId = $this->input($input);
        if (! is_array($serverId) && ! is_string($serverId)) {
            return;
        } elseif (is_array($serverId)) {
            $files = [];
            foreach ($serverId as $item) {
                $files[] = FilePond::getUpload($item);
            }
            $this->merge([$input => $files]);
        } else {
            $this->merge([$input => FilePond::getUpload($serverId)]);
        }
    }
}
