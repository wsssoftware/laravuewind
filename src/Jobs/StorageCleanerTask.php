<?php

namespace Laravuewind\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StorageCleanerTask implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(protected CleanerTask $cleanerTask) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ray($this->cleanerTask->getModelFiles());
        ray($this->cleanerTask->getFiles());
    }

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return $this->cleanerTask->uniqueId();
    }
}
