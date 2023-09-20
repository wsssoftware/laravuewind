<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\Facades\FilePond;

class UploadController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        $input = $request->file('filepond');

        if ($input === null) {
            return ChunkController::initChunk($request);
        }

        $file = is_array($input) ? $input[0] : $input;

        $folderId = FilePond::createFolderId();
        $savedFile = $file->storeAs(
            FilePond::getBasePath().DIRECTORY_SEPARATOR.$folderId,
            $file->getClientOriginalName(),
            FilePond::diskName(),
        );

        abort_if(! $savedFile, 500, 'Could not save file');

        return response(
            FilePond::createServerId($folderId, (int) $request->headers->get('content-length'))->encrypted,
            200,
            [
                'Content-Type' => 'text/plain',
            ]
        );
    }
}