@extends(config('app.layout'))
@section('content')
    <div class="">
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="row">
                    <div class="d-flex align-items-end row">
                        <div class="">
                            <div class="card-body ">
                                <h4 class="card-title text-dark fw-bolder">{{__('Hey')}}
                                    , {{$user->first_name}} {{__('Welcome!')}}</h4>
                                <p class="mt-4 text-dark">{{__('"An investment in knowledge pays the best interest." - Benjamin Franklin')}}</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-none bg-dark">
                            <div class="card-body row gap-md-0 ">
                                <div class="col-md-12">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <h4 class="fw-bold text-white">{{$total_assignment_todos}}</h4>
                                                <small class="mb-2 text-muted">{{__('Assignments')}}</small>
                                                <h4 class="fw-bold text-white">{{$total_assignment_todos_completed}}</h4>
                                                <small class="mb-2 text-muted">{{__('Completed')}}</small>
                                            </div>
                                            <div class="d-flex flex-column float-end">
                                                <div id="usage-stats-remaining" class=""></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <div class="card">
                                <div class="card-body pb-2">
                                    <span class="d-block fw-semibold mb-1">{{__('Studied Today')}}</span>
                                    <h3 class="card-title text-dark mb-1">{{$total_studied_today_minutes}}m</h3>
                                    <div class="" id="study-time-logs"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="card shadow-none bg-primary-gradient h-100">
                                <div class="card-body row gap-md-0 gap-4">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-column">
                                                <small class="mb-2 text-white-50">{{__('Total Study Goals')}}</small>
                                                <h4 class="fw-bold text-white">{{$total_goals}}</h4>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <small class="mb-2 text-white-50">{{__('Completed')}}</small>
                                                <h4 class="fw-bold text-white">{{$total_goals_completed}}</h4>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-success">{{__('Goal Progress')}}</small>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 me-2" style="height:8px">
                                                    <div class="progress-bar bg-white"
                                                         style="width: {{$total_goals_completed_percentage}}%"
                                                         role="progressbar" aria-valuenow="74" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-success">{{$total_goals_completed_percentage }}
                                                    %</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-none h-100 bg-gray-100">
                            <div class="card-body row gap-md-0 gap-4">
                                <div class="col-md-12">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-column">
                                                <small class="mb-2 text-muted">{{__('Total Tasks')}}</small>
                                                <h4 class="fw-bold text-dark">{{$total_todos}}</h4>
                                            </div>
                                            <div class="d-flex flex-column float-end">


                                                <small class="mb-2 text-muted">{{__('Completed')}}</small>
                                                <h4 class="fw-bold text-dark">{{$total_todos_completed}}</h4>
                                            </div>


                                        </div>


                                        <div class="mb-3">
                                            <small class="text-success">{{__('Task Progress')}}</small>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 me-2" style="height:8px">
                                                    <div class="progress-bar bg-primary"
                                                         style="width: {{$total_todos_completed_percentage}}%"
                                                         role="progressbar" aria-valuenow="74" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-success">{{$total_todos_completed_percentage}}
                                                    %</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-2 ">
                    <div class="ms-2 card-header card-title mb-2">
                        <h5 class="m-0  text-dark fw-bold">{{__('Recent Assignments')}}</h5>
                        <small class="text-muted">{{__('Your recent assignments')}}</small>
                    </div>

                    @foreach($recent_projects as $project)

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="{{$base_url}}/app/view-assignment?id={{$project->id}}"
                                           class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <div class="avatar flex-shrink-0 me-3">

                                                    @if($project->title['0'] == 'A')

                                                        <span class="avatar-initial rounded bg-primary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'B')
                                                        <span class="avatar-initial rounded bg-secondary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'C')
                                                        <span class="avatar-initial rounded bg-success text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'D')
                                                        <span class="avatar-initial rounded bg-danger text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'E')
                                                        <span class="avatar-initial rounded bg-warning text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'F')
                                                        <span class="avatar-initial rounded bg-info text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'G')
                                                        <span class="avatar-initial rounded bg-dark text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'H')
                                                        <span class="avatar-initial rounded bg-light text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'I')
                                                        <span class="avatar-initial rounded bg-primary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'J')
                                                        <span class="avatar-initial rounded bg-secondary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'K')
                                                        <span class="avatar-initial rounded bg-success text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'L')
                                                        <span class="avatar-initial rounded bg-danger text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'M')
                                                        <span class="avatar-initial rounded bg-success text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'N')
                                                        <span class="avatar-initial rounded bg-info text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'O')
                                                        <span class="avatar-initial rounded bg-dark text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'P')
                                                        <span class="avatar-initial rounded bg-warning text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'Q')

                                                        <span class="avatar-initial rounded bg-info text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'R')
                                                        <span class="avatar-initial rounded bg-secondary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'S')

                                                        <span class="avatar-initial rounded bg-success text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'T')
                                                        <span class="avatar-initial rounded bg-danger text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'U')
                                                        <span class="avatar-initial rounded bg-warning text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'V')
                                                        <span class="avatar-initial rounded bg-info text-white">{{$project->title['0']}}</span>

                                                    @elseif($project->title['0'] == 'W')
                                                        <span class="avatar-initial rounded bg-dark text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'X')
                                                        <span class="avatar-initial rounded bg-light text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'Y')
                                                        <span class="avatar-initial rounded bg-primary text-white">{{$project->title['0']}}</span>
                                                    @elseif($project->title['0'] == 'Z')
                                                        <span class="avatar-initial rounded bg-secondary text-white">{{$project->title['0']}}</span>
                                                    @else
                                                        <span class="avatar-initial rounded bg-success text-white">A</span>

                                                    @endif

                                                </div>
                                            </div>
                                            <div class="me-2 text-dark fw-bold h5 mb-0">
                                                {{$project->title}}
                                            </div>
                                        </a>

                                    </div>
                                    <p>{{$project->summary}}</p>

                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="avatar-group d-flex mt-2">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <ul class="list-unstyled w-50 me-2 d-flex align-items-center avatar-group mb-0">

                                                    @if($project->members)
                                                        @foreach(json_decode($project->members) as $member)
                                                            @if(isset($users[$member]))
                                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                                    data-bs-placement="top"
                                                                    title=" {{$users[$member]->first_name}} {{$users[$member]->last_name}}"
                                                                    class="avatar pull-up">
                                                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-success-soft">
                                {{$users[$member]->first_name[0]}}{{$users[$member]->last_name[0]}}
                            </span>
                                                                    </div>
                                                                </li>

                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="ms-auto">
                                            @if($project->status == 'Started')
                                                <span class="badge bg-label-primary"> {{$project->status}}</span>

                                            @elseif($project->status == 'Pending')
                                                <span class="badge bg-label-yellow">{{$project->status}}</span>
                                            @elseif($project->status == 'Finished')
                                                <span class="badge bg-label-success">{{$project->status}}</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-header card-title mb-2 mt-4">
                    <h5 class="m-0  text-dark fw-bold">{{__('Study Goals')}}</h5>
                    <small class="text-muted">{{__('Your recent goals')}}</small>
                </div>

                <div class="mt-4">
                    <div class="card shadow-none h-100">
                        <div class="mt-2">
                            <ul class="list-group list-group-flush" data-toggle="checklist">
                                @foreach($recent_goals as $goal)
                                    <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                        <div class="checklist-item checklist-item-primary ps-2 ms-3">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input goal_checkbox" type="checkbox"
                                                           data-id="{{$goal->id}}" @if($goal->completed) checked @endif

                                                    >
                                                </div>
                                                <a href="{{$base_url}}/app/view-studygoal/?id={{$goal->id}}">
                                                    <h6 class="mb-0 me-3 text-dark font-weight-bold text-sm fw-bold">{{$goal->title}}</h6>

                                                </a>
                                                @if(!empty($categories[$goal->category_id]))
                                                    @if(isset($categories[$goal->category_id]))
                                                        <span class="badge bg-primary"> {{$categories[$goal->category_id]->name}}</span>

                                                    @endif
                                                @endif

                                                <div class="dropdown float-lg-end ms-auto pe-4">

                                                    <div class="btn-group" role="group" aria-label="Basic example"><a
                                                                class="btn btn-link text-dark px-3 mb-0"
                                                                href="{{$base_url}}/app/new-studygoal/?id={{$goal->id}}"><i
                                                                    class="fas fa-pencil-alt text-dark me-2"
                                                                    aria-hidden="true"></i><i class="bi bi-pencil"></i></a>
                                                        <a class="btn btn-link text-dark px-3 mb-0"
                                                           data-delete-item="true"
                                                           href="{{$base_url}}/app/delete/study-goal/{{$goal->uuid}}"><i
                                                                    class="bi bi-trash3-fill"></i></a>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center ms-4 mt-1 ps-1">
                                                <div>
                                                    <p class="mb-2 text-sm">{{$goal->reason}}</p>
                                                    {{__('Created')}}: <span
                                                            class="badge bg-label-success me-3">{{date('d M Y',strtotime($goal->start_date))}}</span>{{__('Finish by')}}
                                                    :
                                                    <span class="badge bg-label-danger-soft">{{date('d M Y',strtotime($goal->end_date))}}</span>


                                                </div>
                                            </div>
                                        </div>
                                        <hr class="horizontal dark mt-4 mb-0">
                                    </li>

                                @endforeach
                            </ul>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card  shadow-none bg-gray-100">
                    <div class="card-header card-title mb-2">
                        <h5 class="m-0 me-2 text-dark fw-bold">{{__('To-do List')}}</h5>
                        <small class="text-muted">{{__('Check off your todos and feel good!')}}</small>
                    </div>

                    <div class="card-body">
                        <form id="businessForm" onsubmit="return false">
                            @foreach($recent_todos as $todo)
                                <div class="form-check custom-option custom-option-basic mb-3">
                                    <label class="form-check-label custom-option-content" for="brandingCheckbox">
                                        <input class="form-check-input todo_checkbox" type="checkbox"
                                               data-id="{{$todo->id}}" id="brandingCheckbox"
                                               @if($todo->completed) checked @endif/>
                                        <span class="custom-option-header pb-0">
                <span class="text-dark">{{$todo->title}}</span>
                                        @if($todo->status == 'High')
                                                <span class="badge bg-label-danger"> {{$todo->status}}</span>

                                            @elseif($todo->status == 'Medium')
                                                <span class="badge bg-label-yellow">{{$todo->status}}</span>
                                            @elseif($todo->status == 'Low')
                                                <span class="badge bg-label-success">{{$todo->status}}</span>
                                            @endif
                                    </span>
                                    </label>
                                </div>
                            @endforeach
                        </form>
                        <hr class="horizontal dark">
                    </div>
                    <div class="h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-2">
                                <h5 class="m-0 me-2 text-dark fw-bold">{{__('Study Buddies')}}</h5>
                                <small class="text-muted">{{__('User Statistics')}}</small>
                            </div>
                        </div>
                        <div class="mb-3">

                            <div class="table-responsive text-nowrap">
                                <table class="table table-borderless ">
                                    <tbody class="bg-gray-100">
                                    @foreach($recent_users as $recent_contact)
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="avatar flex-shrink-0 me-3">

                                                        <div class="avatar flex-shrink-0 me-3">
                                                            @php
                                                                $initial = $recent_contact->first_name[0];
                                                                $bgColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'light'];
                                                                $bgIndex = array_search($initial, range('A', 'Z')) % count($bgColors);
                                                                $bgColor = $bgColors[$bgIndex]
                                                            @endphp
                                                            <span class="avatar-initial rounded bg-{{ $bgColor }} text-white">{{ $initial }}</span>
                                                        </div>

                                                    </div>
                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="">
                                                            <h6 class="mb-0 text-dark">{{$recent_contact->first_name}} {{$recent_contact->last_name}}</h6>
                                                            <small class="text-muted">{{$recent_contact->email}}</small>
                                                        </div>
                                                        <div class="user-progress">

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <a class="btn btn-sm btn-icon" data-delete-item="true"
                                                   href="{{$base_url}}/app/delete/user/{{$recent_contact->uuid}}"><i
                                                            class="bi bi-trash3-fill"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <h5 class="card-title m-0 mb-3  me-2 text-dark fw-bold">{{__('New Events')}}</h5>
                        <ul class="timeline p-2">

                            @foreach($recent_events as $event)
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-success"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">{{$event->title}}</h6>
                                            <small class="text-muted">{{$event->updated_at->diffForHumans()}}</small>
                                        </div>

                                    </div>
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>
            </div>
        </div>

        @include('app.common.recent-documents')
        @if(config('app.name') !== 'MyDesk')
            @include('app.common.add-event')
        @endif
        @include('app.common.create-document')


        @endsection

        @section('scripts')
            {!! (new App\Supports\AssetSupport)->js('lib/apexcharts/apexcharts.min') !!}

            <script>
                (function () {
                    "use strict";
                    document.addEventListener('DOMContentLoaded', () => {

                        // Get all elements with class 'todo_checkbox'
                        let goal_checkboxes = document.querySelectorAll('.goal_checkbox');

// Add event listener to each checkbox
                        goal_checkboxes.forEach((checkbox) => {
                            checkbox.addEventListener('change', function () {
                                // Prepare data based on checkbox status
                                let data = {
                                    id: this.getAttribute('data-id'),
                                    status: this.checked ? 'Completed' : 'Not Completed',
                                    _token: '{{csrf_token()}}',
                                };

                                // Send POST request using axios
                                axios.post('{{$base_url}}/app/goals/change-status', data)
                                    .catch(function (error) {
                                        console.log(error);
                                    });
                            });
                        });

                        // Get all elements with class 'todo_checkbox'
                        let checkboxes = document.querySelectorAll('.todo_checkbox');

// Add event listener to each checkbox
                        checkboxes.forEach((checkbox) => {
                            checkbox.addEventListener('change', function () {
                                // Prepare data based on checkbox status
                                let data = {
                                    id: this.getAttribute('data-id'),
                                    status: this.checked ? 'Completed' : 'Not Completed',
                                    _token: '{{csrf_token()}}',
                                };

                                // Send POST request using axios
                                axios.post('{{$base_url}}/app/todos/change-status', data)
                                    .catch(function (error) {
                                        console.log(error);
                                    });
                            });
                        });

                        new ApexCharts(document.querySelector('#usage-stats-remaining'), {
                            series: [{{$total_assignment_todos_completed_percentage}}],
                            labels: ["{{__('Completed')}}"],
                            chart: {height: 200, type: "radialBar"},
                            colors: ['#ffab00'],
                            plotOptions: {
                                radialBar: {
                                    offsetY: 0,
                                    startAngle: -140,
                                    endAngle: 140,
                                    hollow: {size: "70%"},
                                    track: {strokeWidth: "40%", background: "#ECEEF0"},
                                    dataLabels: {
                                        name: {
                                            offsetY: 60,
                                            color: '#8383c7',
                                            fontSize: "10px",
                                            fontFamily: "Open Sans"
                                        },
                                        value: {
                                            offsetY: -10,
                                            color: '#3fab6b',
                                            fontSize: "20px",
                                            fontWeight: "500",
                                            fontFamily: "Open Sans"
                                        }
                                    }
                                }
                            },
                            stroke: {lineCap: "round"},
                            grid: {padding: {bottom: -20}},
                            states: {hover: {filter: {type: "none"}}, active: {filter: {type: "none"}}}
                        }).render();

                        new ApexCharts(document.querySelector('#study-time-logs'), {
                            chart: {height: 90, type: "bar", toolbar: {show: false}},
                            plotOptions: {
                                bar: {
                                    barHeight: "80%",
                                    columnWidth: "75%",
                                    startingShape: "rounded",
                                    endingShape: "rounded",
                                    borderRadius: 2,
                                    distributed: !0
                                }
                            },
                            grid: {show: false, padding: {top: -20, bottom: -12, left: -10, right: 0}},


                            colors: ['#F2F2FD', '#D6D6FD', '#9E6CFF', '#DDDCFE', '#F2F2FD', '#D6D6FD', '#9E6CFF'],

                            dataLabels: {enabled: false},
                            series: [{
                                data: [
                                    @foreach($study_trends_last_7_days as $key => $value)
                                            {{$value}},
                                    @endforeach
                                ]
                            }],
                            legend: {show: false},
                            xaxis: {
                                categories: [
                                    @foreach($study_trends_last_7_days as $key => $value)
                                        '{{__($key)}}',
                                    @endforeach
                                ],
                                axisBorder: {show: false},
                                axisTicks: {show: false},
                                // labels: {style: {colors: t, fontSize: "13px"}}
                            },
                            yaxis: {labels: {show: false}}
                        }).render();
                    });
                })();
            </script>
@endsection

