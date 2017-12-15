@extends('newsletter::newsletter.template')

@section('page.head.menu')
    <div class="ui secondary menu">
        @if(Auth::user()->can('Create Newsletter'))
            <a class="item" href="{{ route('newsletter.create') }}">
                <i class="add icon"></i>{{ ___('Add Newsletter') }}
            </a>
        @endif
    </div>
@endsection

@section('content')

<table class="ui selectable table">
    <thead>
        <tr>
            <th class="nine wide">{{ ___('Title') }}</th>
            <th class="nine wide">{{ ___('Date') }}</th>
            <th class="center aligned collapsing">{{ ___('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($newsletters as $newsletter)
            <tr class="nine wide" data-news-id="{{ $newsletter->id }}">
                <td>
                    @link(str_limit($newsletter->title, 100), [
                        'type' => 'route',
                        'location' => 'newsletter.edit',
                        'model' => $newsletter->id
                    ])
                </td>
                <td>{{ (new Carbon\Carbon($newsletter->getMeta('newsletter_date')))->formatLocalized(___('%B %e, %Y')) }}</td>
                <td class="right aligned collapsing">
                    <div class="ui compact text menu">
                        <a class="item" href="{{ route('newsletter.edit', $newsletter->id) }}">
                            <i class="pencil icon"></i>
                            {{ ___('Edit') }}
                        </a>
                        @button(___('Delete'), [
                            'method' => 'delete',
                            'location' => 'newsletter.destroy',
                            'type' => 'route',
                            'confirm' => 'Are you sure you want to delete this newsletter?</br>All associated images and PDFs will be deleted as well.</br>This cannot be undone.',
                            'class' => 'item action',
                            'prepend' => '<i class="delete icon"></i>',
                            'model' => $newsletter,
                        ])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    <div class="ui centered">{{ ___('There are no results') }}</div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $newsletters->links('pagination.default') }}

@endsection
