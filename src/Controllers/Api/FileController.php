<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Controllers\Api;

use Baytek\Laravel\Content\Types\Newsletter\Models\File;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Controllers\ContentController;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;
use View;
use File as FS;
use Response;

class FileController extends ContentController
{
    /**
     * Show the file, if it's an image
     */
    public function show($newsletter, $file)
    {
        $path = "newsletter/{$newsletter}/{$file}";
		$file = (new File)->getWithPath($path)->first()->load('meta');

        return response()->file(storage_path('app/' . $file->metadata('file')));
    }

    /**
     * Download the file
     */
    public function download($newsletter, $file)
    {
        $path = "newsletter/{$newsletter}/{$file}";
        $file = (new File)->getWithPath($path)->first()->load('meta');

        return Response::download(storage_path('app/' . $file->metadata('file')), $file->metadata('original'));
    }
}