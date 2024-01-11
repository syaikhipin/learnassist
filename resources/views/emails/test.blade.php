@component('mail::message')
    # {{__('Hello')}} {{$user->first_name}}!

    {{__('This is a test email from ')}} {{config('app.name')}}.

    {{ __('If you receive this email, it means that your SMTP settings are correct.') }}

    {{__('Thanks')}},
    {{ config('app.name') }}

@endcomponent
