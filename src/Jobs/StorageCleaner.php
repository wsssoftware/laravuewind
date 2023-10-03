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
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class StorageCleaner implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    /**
     * @var \Illuminate\Support\Collection<int, \Laravuewind\Jobs\CleanerTask>
     */
    protected static Collection $cleanerTasks;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty(static::$cleanerTasks)) {
            return;
        }
        $tasks = [];
        foreach (self::$cleanerTasks as $cleanerTask) {
            $tasks[] = new StorageCleanerTask($cleanerTask);
        }
        $tasks[] = new ClearRecycleBin(static::$cleanerTasks->pluck('disk')->unique()->values());
        Bus::chain($tasks)
            ->catch(function (Throwable $e) {
                Log::warning(sprintf('StorageCleaner job was failed with message: %s', $e->getMessage()));
            })
            ->dispatch();
    }

    /**
     * @param  \Laravuewind\Jobs\ModelFiles|\Laravuewind\Jobs\ModelFiles[]  $modelCleaner
     */
    public static function registerTask(
        string $disk,
        string $path,
        ModelFiles|array $modelCleaner,
        false|int|Carbon $trashBinTtl = null,
        bool $recursive = true
    ): void {
        $cleanerTask = new CleanerTask(
            $disk,
            CleanerTask::pathSanitize($path),
            $recursive,
            $modelCleaner,
            $trashBinTtl
        );
        if (empty(static::$cleanerTasks)) {
            static::$cleanerTasks = collect();
        }

        $duplicatedItems = self::$cleanerTasks->filter(function (CleanerTask $task) use ($cleanerTask): bool {
            if ($task->disk !== $cleanerTask->disk) {
                return false;
            }
            if ($task->recursive && str_contains('/'.$cleanerTask->path.'/', '/'.$task->path.'/')) {
                return true;
            }
            if ($task->path === $cleanerTask->path) {
                return true;
            }

            return false;
        });
        if ($duplicatedItems->isNotEmpty()) {
            /** @var \Laravuewind\Jobs\CleanerTask $task */
            $task = $duplicatedItems->first();
            throw new \InvalidArgumentException(sprintf(
                'The task on disk "%s", path "%s" and model "%s" will affect task on disk "%s", path "%s" and model "%s. This can be dangerous. Please, check your code."',
                $task->disk,
                $task->path,
                $task->model,
                $cleanerTask->disk,
                $cleanerTask->path,
                $cleanerTask->model,
            ));
        }
        static::$cleanerTasks->push($cleanerTask);
    }
}
