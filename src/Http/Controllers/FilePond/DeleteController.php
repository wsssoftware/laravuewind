<?php

namespace Laravuewind\Http\Controllers\FilePond;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravuewind\FilePond\FilePondFactory;

class DeleteController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, FilePondFactory $factory): Response
    {
        $memoryLimit = config('laravuewind.filepond.memory_limit');
        if (is_numeric($memoryLimit)) {
            ini_set('memory_limit', (int)$memoryLimit);
        }
        $serverId = $factory->getServerId($request->getContent());
        if ($factory->disk()->deleteDirectory($serverId->getFolderPath())) {
            return response('', 200, ['Content-Type' => 'text/plain']);
        }
        abort('', 500, ['Content-Type' => 'text/plain']);
    }
}
