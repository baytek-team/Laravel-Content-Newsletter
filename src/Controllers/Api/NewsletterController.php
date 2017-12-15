<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Controllers\Api;

use Baytek\Laravel\Content\Types\Newsletter\Models\Newsletter;
use Baytek\Laravel\Content\Types\Newsletter\Scopes\NewsletterScope;
use Baytek\Laravel\Content\Types\Newsletter\Scopes\ApprovedNewsletterScope;

use Baytek\Laravel\Content\Controllers\ApiController;

use Illuminate\Http\Request;

use Baytek\Laravel\Content\Models\ContentMeta;

use Carbon\Carbon;

class NewsletterController extends ApiController
{
    public function __construct()
    {
    }

    public function all()
    {
        return Newsletter::paginate(5);
    }

    public function get($newsletter)
    {
        return Newsletter::where('contents.key', $newsletter)
            ->get()
            ->first();
    }

    public function years()
    {
        $prefix = env('DB_PREFIX');

        return \DB::select("SELECT DISTINCT LEFT(meta.value, 4) as title
            FROM ${prefix}content_meta meta
            LEFT JOIN ${prefix}contents content ON meta.content_id = content.id
            WHERE meta.key = 'newsletter_date'
            AND content.status & ?!=0
            AND content.deleted_at IS NULL
            ORDER BY title DESC", [Newsletter::APPROVED]);
    }

    public function byYear($year)
    {
        return Newsletter::withMeta()
            ->whereMetadata('newsletter_date', $year.'%', 'like')
            ->get()
            ->each(function(&$self){
                $self->pdf = File::childenOfTypeWhereMetadata($self->id, 'file', 'mime', 'application/pdf')->withStatus('r', File::APPROVED)->first();

                $self->images = File::childenOfTypeWhereMetadata($self->id, 'file', 'mime', 'image/%', 'like')->withStatus('r', File::APPROVED)->get()->sortBy(function($image, $key){
                        return $image->getMeta('order');
                    })->values();
            });
    }
}
