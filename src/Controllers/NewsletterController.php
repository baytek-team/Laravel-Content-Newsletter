<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Controllers;

use Baytek\Laravel\Content\Types\Newsletter\Models\Newsletter;
use Baytek\Laravel\Content\Types\Newsletter\Models\File;
use Baytek\Laravel\Content\Types\Newsletter\Requests\NewsletterRequest;
use Baytek\Laravel\Content\Types\Newsletter\Scopes\ApprovedNewsletterScope;
use Baytek\Laravel\Content\Types\Newsletter\Events\NewsletterPublished;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Events\ContentEvent;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Validator;
use View;

class NewsletterController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the newsletter
     *
     * @var App\ContentTypes\Newsletter\Models\Newsletter
     */
    protected $model = Newsletter::class;
    protected $request = NewsletterRequest::class;

    protected $viewPrefix = 'admin';

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'newsletter.index',
        'create' => 'newsletter.create',
        'edit' => 'newsletter.edit',
        'show' => 'newsletter.show',
    ];

    protected $redirectsKey = 'newsletter';

    /**
     * Show the index of all content with content type 'newsletter'
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicID = null)
    {
        $this->viewData['index'] = [
            'newsletters' => Newsletter::paginate(),
            'filter' => 'all',
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['create'] = [
            'pdf' => null,
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['key' => str_slug((new Carbon($request->newsletter_date))->toDateString().' '.$request->title)]);

        Validator::make(
            $request->all(),
            (new $this->request)->rules()
        )->validate();

        $this->redirects = false;

        $newsletter = parent::contentStore($request);
        $newsletter->saveMetadata('newsletter_date', (new Carbon($request->newsletter_date))->toDateTimeString());
        $newsletter->saveRelation('parent-id', content('content-type/newsletter', false));
        $newsletter->onBit(Newsletter::APPROVED)->update();

        //Update the PDF
        if ($request->pdf_ids && $request->pdf_ids[0] > 0) {
            $file = File::find($request->pdf_ids[0]);
            $file->saveRelation('parent-id', $newsletter->id);
            $file->onBit(File::APPROVED)->update();
        }

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($newsletter));

        //Publish event
        event(new NewsletterPublished($newsletter));

        return redirect(route('newsletter.edit', $newsletter->id));
    }

    /**
     * Update a newly created newsletter in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->merge(['key' => str_slug((new Carbon($request->newsletter_date))->toDateString().' '.$request->title)]);

        Validator::make(
            $request->all(),
            (new $this->request)->rules()
        )->validate();

        $this->redirects = false;

        $newsletter = parent::contentUpdate($request, $id);
        $newsletter->saveMetadata('newsletter_date', (new Carbon($request->newsletter_date))->toDateTimeString());

        //Make sure it has the correct parent id
        $newsletter->removeRelationByType('parent-id');
        $newsletter->saveRelation('parent-id', content('content-type/newsletter', false));

        //Update the PDF
        if ($request->pdf_ids) {
            $file = File::find($request->pdf_ids[0]);
            $file->saveRelation('parent-id', $newsletter->id);
            $file->onBit(File::APPROVED)->update();
        }

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($newsletter));

        return redirect(route('newsletter.edit', $newsletter->id));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsletter = $this->bound($id);

        $pdf = $newsletter->pdf->first();

        $this->viewData['edit'] = [
            'pdf' => $pdf
        ];

        return parent::contentEdit($newsletter);
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $categories = Content::childrenOfType($committee->id, 'file')
        //     ->with(['relations', 'relations.relation', 'relations.relationType'])
        //     ->get();


            // ->sortBy(function($category){
            //     // dump($category->relationships()->get('content_type'));
            //     return $category->relationships()->get('content_type') != 'resource-category';
            // });

        $this->viewData['show'] = [
            // 'categories' => $categories,
            // 'members' => $committee->members(),
            // 'webpages' => Content::childrenOfType($committee->id, 'webpage')->paginate(),
        ];

        return parent::contentShow($id);
    }

    /**
     * Delete a newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $newsletter = $this->bound($id);

        getChildrenAndDelete($newsletter->load(['relations', 'relations.relation', 'relations.relationType']));

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($newsletter));

        return redirect(route('newsletter.index'));
    }
}