<?php

namespace Laravuewind\FilePond;

use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Log;

class GarbageCollector
{

    protected int $deleted = 0;

    public function __invoke(): void
    {
        $took = Benchmark::measure(fn () => $this->collect());
        Log::info(sprintf(
            'FilePond garbage collector deleted %s outdated upload(s) in %.1fms',
            $this->deleted,
            $took
        ));
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function collect(): void
    {
        /** @var \Laravuewind\FilePond\FilePondFactory $factory */
        $factory = app()->make(FilePondFactory::class);
        $interactionsLimit = (int) config('laravuewind.filepond.gc.interactions_limit', 5);
        $interactions = 0;
        $uploadValidity = (int) config('laravuewind.filepond.gc.upload_validity', 3600);
        $tsLimit = time() - $uploadValidity;

        $disk = $factory->disk();
        foreach ($disk->directories($factory->getBasePath()) as $directory) {
            if ($interactions >= $interactionsLimit) {
                break;
            }
            if ($disk->lastModified($directory) > $tsLimit) {
                continue;
            }
            if ($disk->deleteDirectory($directory)) {
                $this->deleted++;
                $interactions++;
            }
        }

    }
}