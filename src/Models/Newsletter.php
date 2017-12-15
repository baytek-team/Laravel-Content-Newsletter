<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Models;

use Baytek\Laravel\Content\Types\Newsletter\Scopes\NewsletterScope;
use Baytek\Laravel\Content\Types\Newsletter\Scopes\ApprovedNewsletterScope;

use Baytek\Laravel\Content\Models\Content;

use Carbon\Carbon;

class Newsletter extends Content
{

    /**
    * Content keys that will be saved to the relation tables
    * @var Array
    */
    public $relationships = [
        'content-type' => 'newsletter'
    ];

    public $translatableMetadata = [
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new NewsletterScope);
        static::addGlobalScope(new ApprovedNewsletterScope);
        parent::boot();
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getNewsletterDateAttribute()
    {
        return new Carbon($this->getMeta('newsletter_date'));
    }

    /**
     * Scope a query to only include deleted events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeleted($query)
    {
        return $query->withStatus('contents', Newsletter::DELETED);
    }
}
