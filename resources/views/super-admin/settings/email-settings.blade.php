<div class="row mb-5">
        <div class="col-md-6 mt-lg-0 mt-4">
            <div class="card">
                <div class="card-body">
                    <form novalidate="novalidate" action="{{route('app.save-settings')}}" method="post" id="form-email-settings" data-form="refresh" data-btn-id="btnSaveEmailSettings">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-4" id="basic-info">
                            <div class=" pt-0">

                                <div class="mb-4">
                                    <label class="form-label" for="mail_from_address">{{__('Mail From Address')}}</label>
                                    <input id="mail_from_address" name="mail_from_address" value="{{config('mail.from.address')}}"
                                           class="form-control" type="text" required="required">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="mail_from_name">{{__('Mail From Name')}}</label>
                                    <input id="mail_from_name" name="mail_from_name" value="{{config('mail.from.name')}}"
                                           class="form-control" type="text" required="required">
                                </div>


                                <div class="row mb-4">
                                    <label class="form-label">{{__('SMTP Host')}}</label>

                                    <div class="input-group">
                                        <input id="host" name="smtp_host" value="{{config('mail.mailers.smtp.host')}}"
                                               class="form-control" type="text" required="required">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="form-label">{{__('SMTP Username')}}</label>

                                    <div class="input-group">
                                        <input id="username" name="smtp_username" value="{{config('mail.mailers.smtp.username')}}"
                                               class="form-control" type="text" required="required">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">{{__('SMTP Password')}}</label>

                                    <div class="input-group">
                                        <input id="password" name="smtp_password" value="{{config('mail.mailers.smtp.password')}}"
                                               class="form-control" type="password" required="required">
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <label class="form-label">{{__('SMTP Port')}}</label>

                                    <div class="input-group">
                                        <input id="port" name="smtp_port" value="{{config('mail.mailers.smtp.port')}}"
                                               class="form-control" type="number" required="required">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="mail_encryption">{{__('Mail Encryption')}}</label>
                                    <select class="form-select" name="mail_encryption" id="mail_encryption">
                                        <option value="null" @if((config('mail.mailers.smtp.encryption') ?? null) === 'null') selected @endif>None</option>
                                        <option value="tls" @if((config('mail.mailers.smtp.encryption') ?? null) === 'tls') selected @endif>TLS</option>
                                        <option value="ssl" @if((config('mail.mailers.smtp.encryption') ?? null) === 'ssl') selected @endif>SSL</option>
                                    </select>
                                </div>

                                @csrf

                                <input type="hidden" name="type" value="email-settings">

                                <button type="submit" id="btnSaveEmailSettings" class="btn btn-primary float-left mb-0">{{__('Update')}} </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>{{__('Test Email Settings')}}</h4>
                    <form novalidate="novalidate" action="{{route('app.save-settings')}}" method="post" id="formTestEmail" data-form="refresh" data-btn-id="btnTestEmail">
                        <div class="mb-4">
                            <label>{{__('Send an email to')}}</label>
                            <input class="form-control" name="email" type="email" required="required">
                        </div>
                        @csrf
                        <input type="hidden" name="type" value="test-email">
                        <button id="btnTestEmail" type="submit" class="btn btn-primary  float-left mb-0">{{__('Send Test Email')}} </button>
                    </form>
                </div>
            </div>
        </div>

    </div>




