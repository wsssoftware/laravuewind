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

class StorageCleanerTask implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 60;

    private string $trashBinPath;

    public function __construct(protected CleanerTask $cleanerTask)
    {
        $this->trashBinPath = CleanerTask::pathSanitize(config('laravuewind.storage_cleaner.trash_bin_path'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        $dbFiles = $this->cleanerTask->modelCleaner
            ->reduce(
                fn (Collection $carry, ModelFiles $modelCleaner) => $carry->merge(
                    $modelCleaner->getDbFiles($this->cleanerTask->path)
                ),
                collect()
            );
        $diskFiles = $this->cleanerTask->getDiskFiles();

        $eligiblePaths = $diskFiles->diff($dbFiles);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->cleanerTask->disk);
        $moveTimestamp = $this->formatTimestamp(Carbon::now()->getTimestamp());
        $success = collect();
        foreach ($eligiblePaths as $path) {
            $elapsedTime = microtime(true) - $startTime;
            if ($elapsedTime >= $this->timeout * 0.90) {
                break;
            }
            if ($disk->directoryExists($path) === false && $disk->fileExists($path) === true) {
                if ($this->cleanerTask->trashBinTtl instanceof Carbon) {
                    $trashedPostfix = sprintf(
                        '.%s.%s.deleted',
                        $moveTimestamp,
                        $this->formatTimestamp($this->cleanerTask->trashBinTtl->getTimestamp()),
                    );
                    $newPath = $this->trashBinPath.'/'.$path.$trashedPostfix;
                    if ($disk->move($path, $newPath)) {
                        $success->push($path);
                    } else {
                        Log::error(sprintf(
                            'Failed to move file from "%s" to "%s"',
                            $path,
                            $newPath
                        ));
                    }
                } else {
                    if ($disk->delete($path)) {
                        $success->push($path);
                    } else {
                        Log::error(sprintf('Failed to delete file from "%s"', $path));
                    }
                }
            }
        }
        if ($eligiblePaths->count() > 0) {
            $verb = $this->cleanerTask->trashBinTtl instanceof Carbon ? 'moved to trash bin' : 'deleted';
            Log::info(sprintf(
                'at path "%s" %s of %s files were %s from disk "%s" in %s seconds',
                $this->cleanerTask->path,
                $success->count(),
                $eligiblePaths->count(),
                $verb,
                $this->cleanerTask->disk,
                round(microtime(true) - $startTime, 2)
            ));
        }
    }

    protected function formatTimestamp(int $timestamp): string
    {
        return str_pad($timestamp, 11, '0', STR_PAD_LEFT);
    }

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return $this->cleanerTask->uniqueId();
    }
}
