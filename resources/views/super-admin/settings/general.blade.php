<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form novalidate="novalidate" method="post" action="{{route('app.save-settings')}}" id="form-contact"
                      data-form="refresh" data-btn-id="btn-save-contact">

                    <h4>{{__('Workspace')}}</h4>

                    <div class="mb-3">
                        <label for="workspace_name">{{__('Workspace Name')}}</label>
                        <input type="text" class="form-control" id="workspace_name" name="workspace_name"
                               value="{{$settings['workspace_name'] ?? ''}}" required>
                    </div>

                    <div class="mb-3">
                        <label for="defaultLanguage" class="form-label mt-4">{{__('Default Language')}}</label>
                        <select class="form-select" name="language" id="defaultLanguage">
                            @foreach($available_languages as $key => $value)
                                <option value="{{$key}}" @if(($settings['language'] ?? null)===$key) selected @endif >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mt-4">{{__('Landing Page Language')}}</label>
                        <select class="form-select" name="frontend_language" id="choices-language">
                            @foreach($available_languages as $key => $value)
                                <option value="{{$key}}" @if(($settings['frontend_language'] ?? null)===$key) selected @endif >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

{{--                    <div class="form-check form-switch">--}}
{{--                        <input class="form-check-input" type="checkbox" role="switch" id="userRequiresEmailValidation" name="user_requires_email_validation"--}}
{{--                               @if(($super_settings['user_requires_email_validation'] ?? null) == 1) checked @endif--}}
{{--                        >--}}
{{--                        <label class="form-check-label" for="userRequiresEmailValidation">--}}
{{--                            {{__('User Requires Email Validation')}}--}}
{{--                        </label>--}}
{{--                    </div>--}}

                    <div class="mb-3">
                        <label for="defaultFreeTrialDays" class="form-label mt-4">{{__('Default Free Trial Days')}}</label>
                        <input class="form-control" type="number" name="free_trial_days" id="defaultFreeTrialDays" value="{{$settings['free_trial_days'] ?? config('app.free_trial_days')}}">
                        <p>
                            {{__('Enter the number of free trial days. Example: 7, 14, 30 etc.')}}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="defaultCurrency" class="form-label mt-4">{{__('Default Currency')}}</label>
                        <input class="form-control" name="currency" id="defaultCurrency" value="{{$settings['currency'] ?? config('app.currency')}}">
                        <p>
                            {{__('Enter the currency iso code. Example: USD, EUR, GBP etc.')}}
                        </p>
                    </div>


                    <input type="hidden" name="type" value="general">

                    <button type="submit" class="btn btn-primary" id="btn-save-contact">{{__('Save')}}</button>

                </form>

                <form method="post" action="{{route('app.save-settings')}}" enctype="multipart/form-data">
                    <div class="mt-4 mb-3">
                        <label for="formFileBackendLogo" class="form-label">{{__('Backend Logo')}}</label>
                        <input class="form-control" type="file" accept="image/*" name="backend_logo"
                               id="formBackendLogo">
                        <input type="hidden" name="type" value="backend_logo">
                        @csrf
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </form>


                <form method="post" action="{{route('app.save-settings')}}" enctype="multipart/form-data">
                    <div class="mt-4 mb-3">
                        <label for="formFile" class="form-label">{{__('Frontend Logo')}}</label>
                        <input class="form-control" type="file" accept="image/*" name="logo" id="formFile">
                        <input type="hidden" name="type" value="logo">
                        @csrf
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </form>
                <form method="post" action="{{route('app.save-settings')}}" enctype="multipart/form-data">
                    <div class="mt-4 mb-3">
                        <label for="formFavicon" class="form-label">{{__('Favicon')}}</label>
                        <input class="form-control" type="file" accept="image/*" name="favicon"
                               id="formFavicon">
                        <input type="hidden" name="type" value="favicon">
                        @csrf
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
