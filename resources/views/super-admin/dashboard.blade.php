@extends('super-admin.app')
@section('content')

    <div class="row">
        <div class="col-md-4">
            <a href="{{$base_url}}/super-admin/workspaces">
                <div class="card bg-label-light shadow-none mb-3">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="bi bi-database-fill widget-icon"></i>
                        </div>
                        <h5 class="text-dark fw-bold mt-0">{{__('Workspaces')}}</h5>
                        <h3 class="mt-3">{{$workspaces_count}}</h3>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-md-4">
            <a href="{{$base_url}}/super-admin/users">
                <div class="card shadow-none bg-label-secondary mb-3">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="bi bi-people-fill widget-icon"></i>
                        </div>
                        <h5 class="text-dark fw-bold mt-0">{{__('Users')}}</h5>
                        <h3 class="mt-3">{{$users_count}}</h3>
                    </div>
                </div>
            </a>

        </div>
        <div class="col-md-4">
            <div class="card border shadow-none bg-label-primary mb-3">
                <div class="card-body">
                    <div class="float-end">
                        <i class="bi bi-people-fill widget-icon"></i>
                    </div>
                    <h5 class="text-dark fw-bold mt-0">{{__('Storage Space')}}</h5>
                    <h3 class="mt-3">{{$storage}} {{__('mb')}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="fw-bold text-dark">{{__('Transactions')}}</h5>
                    @if(empty($transactions_last_30_days))
                        <div class="alert alert-secondary">{{__('No data to display')}}</div>
                    @else
                        <div id="payments-chart"></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="fw-bold text-dark">{{__('User Acquisitions')}}</h5>
                    <div id="user-acquisitions-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body pb-0">
            <h5 class="fw-bold text-dark">{{__('Recent Users')}}</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Workspace')}}</th>
                    <th>{{__('Email')}}</th>
                    <th>{{__('Created')}}</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($recent_users as $recent_user)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success">{{$recent_user->first_name['0']}}</span>
                                </div>
                                <a class="text-dark"
                                   href="{{$base_url}}/app/contact?uuid={{$recent_user->uuid}}"><strong>{{$recent_user->first_name}} {{$recent_user->last_name}}</strong></a>

                            </div>

                        </td>
                        <td>
                            @if(!empty($workspaces[$recent_user->workspace_id]))
                                <a href="{{$base_url}}/super-admin/view-workspace/{{$workspaces[$recent_user->workspace_id]->uuid}}"><strong>{{$workspaces[$recent_user->workspace_id]->name}}</strong></a>
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            {{$recent_user->email}}
                        </td>
                        <td>
                            {{$recent_user->phone}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body pb-0">
            <h5 class="fw-bold text-dark">{{__('Recent Workspaces')}}</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-light">
                <thead class="table-light">
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('User')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Created')}}</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($recent_workspaces as $recent_workspace)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success-alt">{{$recent_workspace->name['0']}}</span>
                                </div>
                                <a href="{{$base_url}}/super-admin/view-workspace/{{$recent_workspace->uuid}}"><strong>{{$recent_workspace->name}}</strong></a>

                            </div>

                        </td>
                        <td>
                            @if(!empty($users[$recent_workspace->owner_id]))
                                <a href="{{$base_url}}/super-admin/view-user/{{$users[$recent_workspace->owner_id]->uuid}}"><strong>{{$users[$recent_workspace->owner_id]->first_name}} {{$users[$recent_workspace->owner_id]->last_name}}</strong></a>
                            @endif
                        </td>
                        <td>
                            @if($recent_workspace->is_active == 1)
                                <span class="badge bg-success">{{__('Active')}}</span>
                            @else
                                <span class="badge bg-danger">{{__('Inactive')}}</span>
                            @endif
                        </td>
                        <td>
                            {{$recent_workspace->created_at->diffForHumans()}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>



@endsection

@section('scripts')
    <script>
        (function () {
            "use strict";
            window.addEventListener('DOMContentLoaded', () => {

                createChart("#user-acquisitions-chart", {
                    type: 'line',
                    height: 323,
                    colors: ['#2E0A93'],
                    data: {
                        labels: [
                            @foreach($user_signups_last_30_days as $key => $value)
                                "{{$key}}",
                            @endforeach
                        ],
                        datasets: [
                            {
                                values: [
                                    @foreach($user_signups_last_30_days as $key => $value)
                                        "{{$value}}",
                                    @endforeach
                                ]
                            }
                        ]
                    },
                    lineOptions: {
                        regionFill: 1,
                    },
                    axisOptions: {
                        xAxisMode: 'tick',
                    },
                });

                @if(!empty($transactions_last_30_days))
                createChart("#payments-chart", {
                    type: 'bar',
                    height: 323,

                    colors: ['#2E0A93'],
                    data: {
                        labels: [
                            @foreach($transactions_last_30_days as $key => $value)
                                "{{$key}}",
                            @endforeach
                        ],
                        datasets: [
                            {
                                values: [
                                    @foreach($transactions_last_30_days as $key => $value)
                                        "{{$value}}",
                                    @endforeach
                                ]
                            }
                        ]
                    },
                    spaceRatio: 0.8,
                    axisOptions: {
                        xAxisMode: 'tick',
                    },
                });
                @endif



            });
        })();
    </script>
@endsection
