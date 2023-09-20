<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Laravuewind\Facades\FilePond;

class UploadController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        $input = $request->file('filepond');


        if ($input === null) {
//            return $this->handleChunkInitialization();
        }

        $file = is_array($input) ? $input[0] : $input;
        $path = config('laravuewind.filepond.temporary_path', 'filepond');

        $savedFile = $file->storeAs(
            $path . DIRECTORY_SEPARATOR . Str::random(),
            $file->getClientOriginalName(),
            FilePond::diskName(),
        );

        if (!$savedFile) {
            return response('Could not save file', 500, [
                'Content-Type' => 'text/plain',
            ]);
        }
        return response(FilePond::getUploadId($savedFile), 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}