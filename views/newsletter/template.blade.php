@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="mail outline icon"></i>
        <div class="content">
            {{ ___('Newsletters') }}
            <div class="sub header">{{ ___('Manage the newsletters of the system.') }}</div>
        </div>
    </h1>
@endsection
