<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\Facades\FilePond;

class DeleteController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        $path = FilePond::getPath($request->getContent());
        $folder = dirname($path);
        if (FilePond::disk()->deleteDirectory($folder)) {
            return response('', 200, [
                'Content-Type' => 'text/plain',
            ]);
        }
        return response('', 500, [
            'Content-Type' => 'text/plain',
        ]);
    }
}