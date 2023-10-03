<?php

namespace Laravuewind\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClearRecycleBin implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 60;

    public float $startTime;

    protected string $trashBinPath;

    public function __construct(protected Collection $disks)
    {
        $this->trashBinPath = CleanerTask::pathSanitize(config('laravuewind.storage_cleaner.trash_bin_path'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->startTime = microtime(true);
        $this->disks->each(fn (string $disk) => $this->handleDisk($disk));
    }

    protected function handleDisk(string $diskName): void
    {
        $disk = Storage::disk($diskName);
        if (! $disk->exists($this->trashBinPath)) {
            $disk->makeDirectory($this->trashBinPath);
        }
        $files = collect($disk->files($this->trashBinPath, true));

        $toDeletePath = $files->filter(fn (string $path) => $this->getTtl($path)->isPast());

        $success = collect();
        foreach ($toDeletePath as $path) {
            if (microtime(true) - $this->startTime >= $this->timeout * 0.90) {
                break;
            }
            if ($disk->delete($path)) {
                $success->push($path);
            } else {
                Log::error(sprintf('Failed to delete file: %s', $path));
            }
        }

        if ($success->count() > 0) {
            Log::info(sprintf(
                '%s of %s files were deleted from trash bin from disk "%s" in %s seconds',
                $success->count(),
                $toDeletePath->count(),
                $diskName,
                round(microtime(true) - $this->startTime, 2)
            ));
        }
    }

    protected function getTtl(string $path): Carbon
    {
        return Carbon::createFromTimestamp(intval(substr($path, -19, 11)));
    }
}
