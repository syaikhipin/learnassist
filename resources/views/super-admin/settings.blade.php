@extends('super-admin.app')
@section('content')


    @switch($tab)

        @case('payment_gateways')
        @include('super-admin.settings.payment-gateways')
        @break

        @case('general')
        @include('super-admin.settings.general')
        @break

        @case('users')
        @include('super-admin.settings.users')
        @break
        @case('email-settings')
        @include('super-admin.settings.email-settings')
        @break
        @case('storage')
        @include('super-admin.settings.storage')
        @break

        @case('user')
        @include('super-admin.settings.user')
        @break

        @case('integrations')
        @include('super-admin.settings.integrations')
        @break

        @case('about')
        @include('super-admin.settings.about')
        @break

    @endswitch


@endsection
