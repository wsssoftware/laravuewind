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
        $serverId = FilePond::getServerId($request->getContent());
        if (FilePond::disk()->deleteDirectory($serverId->getFolderPath())) {
            return response('', 200, [
                'Content-Type' => 'text/plain',
            ]);
        }
        abort('', 500);
    }
}