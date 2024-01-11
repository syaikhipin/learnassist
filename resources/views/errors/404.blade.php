@extends('layouts.message')
@section('page_title', __('Not Found'))
@section('message_content')
    <div class="text-center">
        <h1 class="display-1 text-dark">404</h1>
        <p class="h4 mb-3">{{__('Ohh No! Page not found')}}</p>
        <p class=" mb-3">{{__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.')}}</p>
        <a class="btn btn-primary" href="{{$base_url ?? config('app.url')}}">{{__('Go to Home')}}</a>
    </div>
@endsection
