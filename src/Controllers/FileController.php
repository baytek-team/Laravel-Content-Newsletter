<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Controllers;

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
     * The model the Content Controller super class will use to access the newsletter
     *
     * @var App\ContentTypes\Events\Models\Event
     */
    protected $model = File::class;

    protected $redirectsKey = 'newsletter';

    /**
     * Download the file from the backend
     */
    public function download($id)
    {
        $file = $this->bound($id);
        $file->load('meta');

        return Response::download(storage_path('app/' . $file->metadata('file')), $file->metadata('original'));
    }

    /**
     * Show the file, if it's an image
     */
    public function show($id)
    {
        $file = $this->bound($id);
        $file->load('meta');

        return response()->file(storage_path('app/' . $file->metadata('file')));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        $this->redirects = false;

        $uploaded = $request->file('file');
        $originalName = $uploaded->getClientOriginalName();

        $path = $uploaded->store('newsletters');

        $file = new File([
            'key' => str_slug($originalName) . '_' . date('Y-m-d_H-i-s'),
            'language' => $request->language,
            'title' => $originalName,
            'content' => ''
        ]);

        $file->save();

        $file->saveRelation('content-type', $file->getContentIdByKey('file'));
        $file->saveMetadata('file', $path);
        $file->saveMetadata('original', $originalName);
        $file->saveMetadata('size', FS::size($uploaded));
        $file->saveMetadata('mime', FS::mimeType($uploaded));

        //Add excluded from search status
        $file->onBit(self::EXCLUDED)->update();

        if(!is_null($id)) {
            $file->saveRelation('parent-id', $id);
        }

        // $file->onBit(File::APPROVED)->onBit(File::RESTRICTED)->update();

        return $file;
    }

    /**
     * Remove a file from storage and set its status to deleted
     */
    public function delete(Request $request, $id)
    {
        $file = $this->bound($id);

        $file->offBit(File::APPROVED)->onBit(File::DELETED)->update();
        \Storage::delete($file->getMeta('file'));
        $file->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

}
