@extends(config('app.layout'))
@section('title',__('Contact'))
@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="">
                <!-- HTML5 Inputs -->
                <div class="card mb-4">
                    <h4 class="card-header text-dark fw-bolder">{{__('Contact Details')}}</h4>
                    <form novalidate="novalidate" method="post" action="{{route('app.contact')}}" id="form-contact" data-form="redirect" data-btn-id="btn-save-contact">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">{{__('First Name')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="first_name"  value="{{$contact->first_name ?? ''}}" required id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">{{__('Last Name')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="last_name"  value="{{$contact->last_name ?? ''}}" required id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">{{__('Title')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="title" value="{{$contact->title ?? ''}}" required id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-email-input" class="col-md-2 col-form-label">{{__('Email')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="email" name="email"  value="{{$contact->email ?? ''}}" id="html5-email-input" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="html5-tel-input" class="col-md-2 col-form-label">{{__('Phone')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="tel" name="phone"  value="{{$contact->phone ?? ''}}" id="html5-tel-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-tel-input" class="col-md-2 col-form-label">{{__('Address')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="address"  value="{{$contact->address ?? ''}}" id="html5-password-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-tel-input" class="col-md-2 col-form-label">{{__('City')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="city"  value="{{$contact->city ?? ''}}" id="html5-password-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-tel-input" class="col-md-2 col-form-label">{{__('State')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="state" value="{{$contact->state ?? ''}}" id="html5-password-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-number-input" class="col-md-2 col-form-label">{{__('Zip')}}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="zip" value="{{$contact->zip ?? ''}}" id="html5-number-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-number-input" class="col-md-2 col-form-label">{{__('Notes')}}</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{$contact->notes ?? ''}}</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="uuid" value="{{$contact->uuid ?? ''}}">
                            <div class="mb-3 row">
                                <label for="html5-number-input" class="col-md-2 col-form-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-primary" id="btn-save-contact">{{__('Save')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- File input -->
            </div>
        </div>
    </div>
@endsection
