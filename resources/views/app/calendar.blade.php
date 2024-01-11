@extends(config('app.layout'))
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bolder">{{__('Calendar')}}</h4>
        <div>
            <button class="btn btn-primary" id="btn_add_event">{{__('Add Event')}}</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <div id="app-calendar"
                 data-events-source-url="{{$base_url}}/app/calendar-events"
                 data-events-save-url="{{$base_url}}/app/save-event"
                 data-first-day="1"
            ></div>
        </div>
    </div>

    @include('app.common.add-event')

@endsection
