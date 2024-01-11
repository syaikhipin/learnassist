@extends('layouts.base')
@section('base_content')
    <div class="container">
        <div class="card mx-auto max-width-600 mt-5">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center">
                    <a href="{{$base_url}}" class="app-brand-link gap-2 mb-3">
                        @if(!empty($settings['logo']))
                            <img src="{{config('app.url')}}/uploads/{{$settings['logo']}}"
                                 alt="{{$settings['workspace_name'] ?? config('app.name')}}" class="max-height-70 py-1 my-1">
                        @else
                            <img src="{{config('app.url')}}/uploads/system/logo.png?v=3"
                                 alt="{{$settings['workspace_name'] ?? config('app.name')}}" class="max-height-70 py-1 my-1">
                        @endif
                    </a>
                </div>
                <!-- /Logo -->


                @switch($step)

                    @case('1')

                    <h4 class="mb-2 text-center">{{ __('Hey, Welcome!') }}</h4>
                    <p class="mb-4 text-center">{{ __('Install the application to get started.') }}</p>

                    @if(!$passed)
                        <div class="alert alert-danger my-3">
                            <p class="mb-0">{{ __('Some requirements are not met. Please fix them to continue.') }}</p>
                        </div>
                    @endif
                    <table class="table table-light table-bordered table-striped mb-3">
                        <thead>
                        <tr>
                            <th>{{ __('Requirements') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                        @foreach($required_extensions as $extension)

                            <tr>
                                <td>{{ $extension }}</td>
                                <td>
                                    @if(extension_loaded($extension))
                                        <span class="badge bg-success">{{ __('Enabled') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('Disabled') }}</span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </thead>
                    </table>

                    <form novalidate="novalidate" method="post" action="{{ $base_url }}/save-database-info"
                          id="form-install" data-form="{{ $base_url }}/?step=2" data-btn-id="btn-install">

                        <h5>{{ __('Database Info') }}</h5>

                        <div class="mb-3">
                            <label for="database_host" class="form-label">{{ __('Database Host') }}</label>
                            <input type="text" class="form-control" id="database_host" name="database_host" required/>
                        </div>

                        <div class="mb-3">
                            <label for="database_name" class="form-label">{{ __('Database Name') }}</label>
                            <input type="text" class="form-control" id="database_name" name="database_name" required/>
                        </div>

                        <div class="mb-3" id="database_username">
                            <label for="database_username" class="form-label">{{ __('Database Username') }}</label>
                            <input type="text" class="form-control" id="database_username" name="database_username"
                                   required/>
                        </div>

                        <div class="mb-3" id="database_password">
                            <label for="database_password" class="form-label">{{ __('Database Password') }}</label>
                            <input type="password" class="form-control" id="database_password" name="database_password"/>
                        </div>



                        <div class="mb-3">
                            <button @if(!$passed) disabled @endif class="btn btn-lg bg-primary-gradient d-grid w-100 text-white" id="btn-install"
                                    type="submit">{{ __('Continue') }}</button>
                        </div>

                    </form>

                    @break

                    @case('2')
                    <form novalidate="novalidate" method="post" action="{{ $base_url }}/create-database-tables"
                          id="form-install" data-form="{{ $base_url }}/?step=3" data-btn-id="btn-install">

                        <h5 class="text-center">{{ __('Database Tables') }}</h5>
                        <p class="text-center">{{__('We have created the config file. In the next step, we will create database tables, Click Continue.')}}</p>

                        <div class="mb-3">
                            <button class="btn btn-lg bg-primary-gradient d-grid w-100 text-white" id="btn-install"
                                    type="submit">{{ __('Continue') }}</button>
                        </div>

                    </form>

                    @break

                    @case('3')

                    <form novalidate="novalidate" method="post" action="{{ $base_url }}/save-primary-data"
                          id="form-install" data-form="{{ $base_url }}/?step=4" data-btn-id="btn-install">

                        <h5 class="mb-2 text-center ">{{ __('Create User') }}</h5>
                        <p class="text-center">{{ __('We have created database tables. Now we will create the first user and generate initial data.') }}</p>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required/>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" required/>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" required/>
                            <small>{{ __('Remember, it is the password you will use to log in, not the database password.') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" required/>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-lg bg-primary-gradient d-grid w-100 text-white" id="btn-install"
                                    type="submit">{{ __('Continue') }}</button>
                        </div>

                    </form>

                    @break

                    @case('4')

                    <p class="text-center">
                        <strong>{{__('Congratulations!')}}</strong>
                    </p>
                    <p class="text-center">
                        {{__('Installation is complete.')}}
                    </p>
                    <button class="btn btn-lg bg-primary-gradient mt-3 text-white w-100 text-center" id="btn_continue">
                        {{__('Go to Login')}}
                    </button>

                    @break

                @endswitch


            </div>
        </div>
    </div>
@endsection
@section('base_scripts')
    <script>
        (function () {
            "use strict";
            document.addEventListener('DOMContentLoaded', () => {
                const btn_continue = document.getElementById('btn_continue');
                if (btn_continue) {
                    btn_continue.addEventListener('click', function () {
                        btn_continue.disabled = true;
                        btn_continue.innerHTML = '{{__('Please wait...')}}';
                        fetch('{{config('app.url')}}/app/login').then((response) => {
                            setTimeout(function () {
                                window.location.href = '{{config('app.url')}}/app/login';
                            }, 3000);
                        });
                    });
                }
            });
        })();
    </script>
@endsection
