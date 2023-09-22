<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\FilePond\FilePondFactory;

class UploadController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, FilePondFactory $factory): Response
    {
        $memoryLimit = config('laravuewind.filepond.memory_limit');
        if (is_numeric($memoryLimit)) {
            ini_set('memory_limit', (int) $memoryLimit);
        }
        $input = $request->file('filepond');

        if ($input === null) {
            return ChunkController::initChunk($request, $factory);
        }

        $file = is_array($input) ? $input[0] : $input;

        $folderId = $factory->createFolderId();
        $savedFile = $file->storeAs(
            $factory->getBasePath().DIRECTORY_SEPARATOR.$folderId,
            $file->getClientOriginalName(),
            $factory->diskName(),
        );

        abort_if(! $savedFile, 500, 'Could not save file', ['Content-Type' => 'text/plain']);

        $factory->garbageCollect();

        return response(
            $factory->createServerId($folderId, (int) $request->headers->get('content-length'))->encrypted,
            200,
            ['Content-Type' => 'text/plain']
        );
    }
}
