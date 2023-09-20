<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\Facades\FilePond;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCreateDirectory;

class ChunkController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        $serverId = $request->input('patch');
        abort_if(empty($serverId), 'No server id provided', 400);
        try {
            $folderPath = FilePond::getFolderPath($serverId);
        } catch (DecryptException) {
            abort(400, 'Invalid upload id');
        }

        $offset = $request->server('HTTP_UPLOAD_OFFSET');
        $length = $request->server('HTTP_UPLOAD_LENGTH');
        abort_if(
            ! is_numeric($offset) || ! is_numeric($length),
            'Invalid chunk length or offset', 400
        );

        FilePond::disk()
            ->put(
                $folderPath."patch.$offset.tmp",
                $request->getContent(), ['mimetype' => 'application/octet-stream']);

        $this->persistFileIfDone($request, $serverId, $folderPath);

        return response('', 204);
    }

    private function persistFileIfDone(Request $request, string $serverId, string $folderPath): void
    {
        $disk = FilePond::disk();

        $size = 0;
        $chunks = $disk
            ->files($folderPath);

        foreach ($chunks as $chunk) {
            $size += $disk
                ->size($chunk);
        }
        $wantedSize = $request->headers->get('upload-length', 0);

        if ($size < $wantedSize) {
            return;
        }

        // Sort chunks
        $chunks = collect($chunks);
        $chunks = $chunks->keyBy(function ($chunk) {
            return substr($chunk, strrpos($chunk, '.') + 1);
        });
        $chunks = $chunks->sortKeys();

        // Append each chunk to the final file
        $data = '';
        foreach ($chunks as $chunk) {
            // Get chunk contents
            $chunkContents = $disk
                ->get($chunk);

            // Laravel's local disk implementation is quite inefficient for appending data to existing files
            // To be at least a bit more efficient, we build the final content ourselves, but the most efficient
            // Way to do this would be to append using the driver's capabilities
            $data .= $chunkContents;
            unset($chunkContents);
        }
        Storage::disk($disk)->put($finalFilePath, $data, ['mimetype' => 'application/octet-stream']);
        Storage::disk($disk)->deleteDirectory($basePath);
    }

    public static function initChunk(Request $request): Response
    {
        $folderId = FilePond::createFolderId();
        $folderPath = FilePond::getBasePath().DIRECTORY_SEPARATOR.$folderId.DIRECTORY_SEPARATOR;

        try {
            FilePond::disk()->createDirectory($folderPath);
        } catch (UnableToCreateDirectory|FilesystemException) {
            return response('Could not create file', 500, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return response(
            FilePond::createServerId($folderId, (int) $request->headers->get('upload-length'))->encrypted,
            200,
            [
                'Content-Type' => 'text/plain',
            ]
        );
    }
}
