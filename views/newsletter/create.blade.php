@extends('newsletter::newsletter.template')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <form action="{{route('newsletter.store')}}" method="POST" class="ui form">
                {{ csrf_field() }}

                @include('newsletter::newsletter.form')
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>

                <div class="field actions">
    	            <a class="ui button" href="{{ route('newsletter.index') }}">{{ ___('Cancel') }}</a>
    	            <button type="submit" class="ui right floated primary button">
    	            	{{ ___('Create') }}
                	</button>
                </div>
            </form>
        </div>
    </div>
@endsection