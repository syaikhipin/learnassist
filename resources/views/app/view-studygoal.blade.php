@extends(config('app.layout'))
@section('content')

    <div class="col-lg-12 col-12 mt-4 mt-lg-0">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <div class="row mb-4">
                    <div class="col-6 d-flex align-items-center">
                        <h6 class="mb-0">{{$tolearn->topic}}</h6>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{$base_url}}/app/new-studygoal?id={{$tolearn->id}}"
                           class="btn btn-primary mb-0">{{__('Edit')}}
                        </a>
                        <a href="{{$base_url}}/app/studygoals" class="btn btn-dark mb-0">
                            {{__('Study Goals')}}
                        </a>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <h4 class="my-auto text-dark fw-bolder">
                    <span class="text-dark text-sm me-1"></span>{{$tolearn->title}}<span
                            class="text-secondary text-sm ms-1"></span>
                </h4>
                <div class="d-flex bg-primary-gradient border-radius-lg p-3 mb-4 mt-3">
                    <p class="mb-2 text-white font-weight-bold text-sm">{{$tolearn->reason}}</p>

                </div>
                <div class="d-flex align-items-center mb-3 mt-1 ps-1 mt-3">
                    <div>

                        {{__('Created')}}: <span
                                class="badge bg-label-success me-3">{{date('d M Y',strtotime($tolearn->start_date))}}</span>{{__('Finish by')}}
                        : <span class="badge bg-label-secondary">{{date('d M Y',strtotime($tolearn->end_date))}}</span>


                    </div>

                </div>

                <div class="p-2">
                    {!! clean($tolearn->description) !!}

                </div>
            </div>
        </div>
    </div>
@endsection
