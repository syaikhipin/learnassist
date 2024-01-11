@extends(config('app.layout'))
@section('content')

    <div class=" row">
        <div class="col">
            <h4 class="text-dark fw-bolder"> {{__('Assignments')}}</h4>
        </div>
        <div class="col text-end">
            <a href="{{$base_url}}/app/create-assignment" type="button"
               class="btn btn-primary text-white">{{__('Create Assignment ')}}</a>
            <a href="{{$base_url}}/app/assignment-list" type="button"
               class="btn btn-dark text-white">{{__('Assignments List')}}</a>
        </div>
    </div>
    <div class="row g-4 mt-2">
        @foreach($projects as $project)

            <div class="col-xl-4 col-lg-6 col-md-6">
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
                            <div class="ms-auto">
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-outline-dark btn-icon btn-sm rounded-pill dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                        <li><a class="dropdown-item"
                                               href="{{$base_url}}/app/view-assignment?id={{$project->id}}">{{__('Open')}}</a>
                                        </li>
                                        <li>
                                        <li><a class="dropdown-item"
                                               href="{{$base_url}}/app/create-assignment?id={{$project->id}}">{{__('Edit')}}</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" data-delete-item="true"
                                               href="{{$base_url}}/app/delete/project/{{$project->uuid}}">{{__('Delete')}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
                                        <span class="avatar-initial rounded-circle bg-label-light">
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
                                <a href="javascript:"><span
                                            class="badge bg-label-secondary"> {{date('d M Y',strtotime($project->end_date))}}</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
