@extends(config('app.layout'))
@section('content')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 bg-gray-100">
                    <div class="user-profile-header-banner">
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class=" mt-5 ms-4 ">
                            <div class="avatar rounded avatar-lg">
                                    <span class="avatar-initial rounded bg-label-success">
                                        {{$contact->first_name[0]}} {{$contact->last_name[0]}}
                                    </span>

                            </div>
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column ">

                                <div class="user-profile-info">

                                    <h4 class="text-dark fw-bolder">{{$contact->first_name}} {{$contact->last_name}}</h4>

                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item fw-semibold">
                                            <i class="bi bi-envelope-fill"></i> {{$contact->email}}
                                        </li>
                                        <li class="list-inline-item fw-semibold">
                                            <i class="bi bi-bookmark-check-fill"></i> {{$contact->title}}
                                        </li>

                                        <li class="list-inline-item fw-semibold">
                                            <i class="bi bi-globe-central-south-asia"></i> {{$contact->state}}
                                        </li>
                                        <li class="list-inline-item fw-semibold">
                                            <i class='bx bx-calendar-alt'></i>{{date('d M Y',strtotime($contact->created_at))}}</li>
                                    </ul>
                                </div>
                                <a href="{{$base_url}}/app/contact?uuid={{$contact->uuid}}" class="btn btn-dark text-nowrap">
                                    <i class='bx bx-user-check me-1'></i>{{_('Edit Contact')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Profile Content -->
        <div class="row">
            <div class="col-md-5">
                <!-- About User -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="text-dark fw-bold"> {{__('Contact Details')}}</h5>
                    </div>
                    <div class="card-body">

                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3 fw-bold"><span class="fw-semibold text-dark mx-2">{{__('Full Name')}}:</span> <span>{{$contact->first_name}} {{$contact->last_name}}</span></li>
                            <li class="d-flex align-items-center mb-3 fw-bold"><i class="bx bx-check"></i><span class="fw-semibold text-dark mx-2">{{__('Added By')}}:</span> <span>@if(!empty($users[$contact->user_id]))
                                        {{$users[$contact->user_id]->first_name}} {{$users[$contact->user_id]->last_name}}
                                    @endif</span></li>

                            <li class="d-flex align-items-center mb-3"><i class="bx bx-phone"></i><span class="fw-semibold text-dark mx-2">{{__('Phone')}}:</span> <span>{{$contact->phone}}</span></li>


                            <li class="d-flex align-items-center mb-3"><i class="bx bx-star"></i><span class="fw-semibold text-dark mx-2">{{__('Title')}}:</span> <span>{{$contact->title}}</span></li>
                            <li class="d-flex align-items-center mb-3"><i class="bx bx-flag"></i><span class="fw-semibold text-dark mx-2">{{__('Address')}}:</span> <span>{{$contact->address}}</span></li>
                            <li class="d-flex align-items-center mb-3"><i class="bx bx-flag"></i><span class="fw-semibold text-dark mx-2">{{__('City')}}:</span> <span>{{$contact->city}}</span></li>
                            <li class="d-flex align-items-center mb-3"><i class="bx bx-flag"></i><span class="fw-semibold text-dark mx-2">{{__('State')}}:</span> <span>{{$contact->state}}</span>
                            <li class="d-flex align-items-center mb-3"><i class="bx bx-flag"></i><span class="fw-semibold text-dark mx-2">{{__('Zip')}}:</span> <span>{{$contact->zip}}</span></li>

                        </ul>
                    </div>
                </div>
                <!--/ About User -->



            </div>

            <div class="col-md-7 ">
                <div class="card card-action mb-4 bg-label-yellow">
                    <div class="card-header align-items-center">
                        <h5 class="text-dark fw-bolder mb-0"><i class='bx bx-list-ul me-2'></i>{{__('Notes')}}</h5>
                    </div>
                    <div class="card-body">
                        {{$contact->notes}}
                    </div>
                </div>
            </div>

        </div>
        <!--/ User Profile Content -->
@endsection
