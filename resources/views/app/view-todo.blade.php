@extends(config('app.layout'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bolder">{{__('Todo of')}} {{date('d M Y',strtotime($todo->date))}}</h4>
        <div>
            <a href="{{$base_url}}/app/add-task?id={{$todo->id}}" class="btn btn-primary mb-0 float-end">{{__('Edit')}}
            </a>
        </div>
    </div>
    <div class="col-md-12 col-12 mt-4 mt-lg-0">
        <div class="card">


            <div class="card-body">
                <div class="d-flex bg-gray-100 border-radius-lg p-3 mb-4">
                    <h4 class="my-auto">
                        <span class="text-secondary text-sm me-1"></span>{{$todo->title}}<span
                                class="text-secondary text-sm ms-1"></span>

                    </h4>
                </div>
                <div class="">
                    {!! clean($todo->description) !!}

                </div>
            </div>
        </div>
    </div>
@endsection
