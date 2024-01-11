{{__('Follow this link to reset your password-')}}
{{config('app.url')}}/app/password-reset?id={{$user->uuid}}&token={{$user->password_reset_token}}
