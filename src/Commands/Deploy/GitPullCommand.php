<?php

namespace Laravuewind\Commands\Deploy;

use CzProject\GitPhp\Git;
use CzProject\GitPhp\GitRepository;
use Illuminate\Console\Command;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;

class GitPullCommand extends Command
{
    use HasCwdOption;
    use FakeRun;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:pull {--c|cwd= : Current work directory for command}
                                     {--r|release= : Release that will be checked out}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Git git pull to get all updates';

    /**
     * Execute the console command.
     *
     *
     * @throws \CzProject\GitPhp\GitException
     */
    public function handle(): int
    {
        if (self::$fake) {
            return self::SUCCESS;
        }
        $cwd = $this->getCwdOption();
        $release = $this->option('release');
        $this->components->info(sprintf('Running GIT git pull on "%s"', $cwd));

        $repository = (new Git())->open($cwd);
        if ($repository->getCurrentBranchName() !== 'main') {
            $this->components->warn(sprintf('Current branch is "%s", switching to "main"',
                $repository->getCurrentBranchName()));
            $repository->checkout('main');
        }
        if ($repository->hasChanges()) {
            if (
                !app()->isProduction() &&
                !confirm('There are changes in the repository and it will be lost. Do you want to continue?', false)
            ) {
                $this->components->warn('Skipped.');

                return self::SUCCESS;
            } else {
                $this->components->warn('There are changes in the repository, cleaning it...');
            }
            $outputLines = spin(fn() => $repository->checkout('.'), 'cleaning git repository');
            foreach ($outputLines as $output) {
                $this->components->info('GIT: '.$output);
            }
        }

        $outputLines = spin(fn() => $repository->execute('pull', 'origin', 'main'), 'pulling git remote changes');
        foreach ($outputLines as $output) {
            $this->components->info('GIT: '.$output);
        }
        if ($release) {
            $this->checkoutRelease($release, $repository);
        }

        return self::SUCCESS;
    }

    protected function checkoutRelease(string $release, GitRepository $repository): void
    {
        spin(fn() => $repository->execute('fetch', '--tags'), 'fetching git tags');


        $tags = collect($repository->execute('tag', '--sort=committerdate'))
            ->mapWithKeys(fn($tag) => [$tag => $tag]);
        $tag = spin(function () use ($release, $tags) {
            return $tags
                ->filter(fn($tag) => str_starts_with($tag, $release))
                ->last();
        }, 'Finding the last tag starting with: "'.$release.'"');

        $confirmMsg = sprintf(
            'Tag %s appears to be the last of filter of %s. Do you want to checkout it?',
            $tag,
            $release
        );
        if (!empty($tag) && !confirm($confirmMsg, true)) {
            $tag = null;
        }
        if (empty($tag)) {
            $tag = select('Select tag to checkout', $tags->reverse()->toArray(), required: true);
        }
        if (empty($tag)) {
            throw new \RuntimeException('Tag is empty');
        }

        $outputLines = spin(fn() => $repository->execute('checkout', "tags/$tag"), 'checking out tag');
        foreach ($outputLines as $output) {
            $this->components->info('GIT: '.$output);
        }
    }
}
