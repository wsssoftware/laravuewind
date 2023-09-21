<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\FilePond\FilePondFactory;
use Laravuewind\FilePond\ServerId;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCreateDirectory;

class ChunkController extends BaseController
{

    protected Filesystem|FilesystemAdapter $disk;
    protected FilePondFactory $factory;
    protected Request $request;
    protected ServerId $serverId;

    public function __construct(Request $request, FilePondFactory $factory)
    {
        $memoryLimit = config('laravuewind.filepond.memory_limit');
        if (is_numeric($memoryLimit)) {
            ini_set('memory_limit', (int)$memoryLimit);
        }
        $this->request = $request;
        $this->factory = $factory;
        $this->disk = $factory->disk();
    }

    /**
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        $serverId = $this->request->input('patch');
        abort_if(empty($serverId), 'No server id provided', 400);
        try {
            $this->serverId = $this->factory->getServerId($serverId);
        } catch (DecryptException) {
            abort(400, 'Invalid upload id', ['Content-Type' => 'text/plain']);
        }
        $offset = $this->request->server('HTTP_UPLOAD_OFFSET');
        abort_if(
            ! is_numeric($offset) || ! is_numeric($this->request->server('HTTP_UPLOAD_LENGTH')),
            'Invalid chunk length or offset', 400,
            ['Content-Type' => 'text/plain']
        );

        $this->disk->put(
            $this->serverId->getFolderPath()."patch.$offset.tmp", $this->request->getContent(),
            ['mimetype' => 'application/octet-stream']
        );

        return response(
            status: match ($this->wasPersisted()) {
                null => 204,
                true => 200,
                false => 500,
            },
            headers: ['Content-Type' => 'text/plain']
        );
    }

    protected function getFilename(): string
    {
        $filename = $this->request->headers->get('upload-name');
        abort_if(
            empty($filename) || !is_string($filename),
            'No file name provided',
            500,
            ['Content-Type' => 'text/plain']
        );
        return $filename;
    }

    protected function mergeChunk(int $carry, string $chunkFilePath, $resource): int {
        fwrite($resource, $this->disk->get($chunkFilePath));
        return $carry + 1;
    }

    private function wasPersisted(): ?bool
    {
        $filenamePath = $this->serverId->getFolderPath().$this->getFilename();
        if ($this->disk->exists($filenamePath)) {
            $this->disk->delete($filenamePath);
        }

        $chunks = collect($this->disk->files($this->serverId->getFolderPath()));
        $size = $chunks->sum(fn($chunk) => $this->disk->size($chunk));
        $wantedSize = (int)$this->request->headers->get('upload-length', 0);

        if ($size < $wantedSize) {
            return null;
        }

        $file = fopen($this->disk->path($filenamePath), 'w');
        abort_if($file === false, 'Could not open file', 500, ['Content-Type' => 'text/plain']);
        $chunks = $chunks
            ->mapWithKeys(fn($chunk) => [
                str($chunk)->afterLast(DIRECTORY_SEPARATOR)->replace(['patch.', '.tmp'], '')->toInteger() => $chunk,
            ])
            ->sortKeys(SORT_NUMERIC);

        $processedChunks = $chunks->reduce(
            fn (int $carry, string $chunkPath) => $this->mergeChunk($carry, $chunkPath, $file),
            0
        );
        abort_if(fclose($file) === false, 'Could not close file', 500, ['Content-Type' => 'text/plain']);

        if ($processedChunks === $chunks->count() && $this->disk->size($filenamePath) === $wantedSize) {
            $chunks->each(fn($chunk) => $this->disk->delete($chunk));
            $this->factory->garbageCollect();
            return true;
        }
        return false;
    }


    public static function initChunk(Request $request, FilePondFactory $factory): Response
    {
        $folderId = $factory->createFolderId();
        $folderPath = $factory->getBasePath().DIRECTORY_SEPARATOR.$folderId.DIRECTORY_SEPARATOR;

        try {
            $factory->disk()->createDirectory($folderPath);
        } catch (UnableToCreateDirectory|FilesystemException) {
            return response('Could not create file', 500, ['Content-Type' => 'text/plain']);
        }

        return response(
            $factory->createServerId($folderId, (int) $request->headers->get('upload-length'))->encrypted,
            200,
            ['Content-Type' => 'text/plain']
        );
    }
}