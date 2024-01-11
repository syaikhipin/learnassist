@extends(config('app.layout'))
@section('content')
    <!-- Button trigger modal -->

    <div class="modal fade" id="todoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('Add Task')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="">
                            <form action="{{$base_url}}/app/save-project-task" method="post" data-form="redirect"
                                  data-btn-id="btn-save-task" id="save-tasks">


                                <div class="form-group">
                                    <label for="example-date-input"
                                           class="form-control-label">{{ __('Due Date') }}</label>
                                    <input class="form-control" type="date" name="date" value="{{date('Y-m-d')}}"
                                           id="todo_date">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="example-text-input"
                                           class="form-control-label">{{ __('Task') }}</label><span class="text-danger">*</span>
                                    <input class="form-control" name="title" type="text" id="todo_title">
                                </div>
                                <div class="mt-3">

                                    <label for="exampleFormControlInput1" class="form-label">{{__('Assign To')}}</label>
                                    <select class="form-select form-select-solid fw-bolder" id="todo_admin_id"
                                            aria-label="Floating label select example" name="admin_id">
                                        <option value="0">{{__('None')}}</option>
                                        @foreach ($users as $taskuser)
                                            <option value="{{$taskuser->id}}"
                                                    @if (!empty($todo))
                                                    @if ($todo->admin_id === $taskuser->id)
                                                    selected
                                                    @endif
                                                    @endif
                                            >{{$taskuser->first_name}} {{$taskuser->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="exampleFormControlTextarea1">{{ __('Description') }}</label><span
                                            class="text-danger">*</span>
                                    <textarea class="form-control" name="description" id="todo_description"
                                              rows="3"> </textarea>
                                </div>

                                @csrf

                                <input type="hidden" id="selected_todo_id" name="todo_id" value="">

                                <input type="hidden" name="project_id" value="{{$project->id}}">

                                <button type="submit" class="btn btn-primary"
                                        id="btn-save-task">{{ __('Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">{{__('Close')}}</button>

                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card h-100 bg-gray-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                            <span class="avatar-initial avatar-md rounded-circle bg-dark">@if(isset($project->title))
                                    <span class="fw-bold fs-2">  {{$project->title[0]}}
</span> @endif</span>

                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-dark fw-bolder">
                                    <span class="fs-5 text-dark fw-bolder">  {{$project->title}}
</span>
                            </h6>
                            <small>{{$project->created_at->diffForHumans()}}</small>


                        </div>

                    </div>
                    <div class="float-end">
                        <button type="button" class="btn btn-dark" id="btn_add_task">
                            {{__('Add Task')}}
                        </button>
                        <a href="{{$base_url}}/app/create-assignment?id={{$project->id}}" type="button"
                           class="btn btn-primary">{{__('Edit')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-4 mt-2">
                        <div class="d-flex flex-column  ms-2">
                            <small class="text-muted text-nowrap d-block mb-2">{{__('Status')}}</small>
                            @if($project->status == 'Started')
                                <span class="badge bg-primary"> {{$project->status}}</span>

                            @elseif($project->status == 'Pending')
                                <span class="badge bg-label-yellow">{{$project->status}}</span>
                            @elseif($project->status == 'Finished')
                                <span class="badge bg-label-success">{{$project->status}}</span>
                            @endif
                        </div>
                        <div class="d-flex flex-column ms-2">
                            <small class="text-muted text-nowrap d-block mb-2">{{__('Deadline')}}</small>
                            <h6 class="mb-0">  {{date('d M Y',strtotime($project->end_date))}}</h6>
                        </div>
                        <div class="d-flex flex-column w-50 ms-2">
                            <small class="text-muted text-nowrap d-block mb-2">{{__('Start Date')}}</small>
                            <h6 class="mb-0">  {{date('d M Y',strtotime($project->start_date))}}</h6>
                        </div>


                        <div class="d-flex flex-column   flex-grow-1">
                            <small class="text-muted text-nowrap d-block mb-2">{{__('Task Completed')}}</small>
                            <div class="d-flex align-items-center">
                                <div class="progress w-100 me-3">
                                    <div class="progress-bar bg-dark" role="progressbar" style="width: {{$progress}}%"
                                         aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small>{{$progress}}%</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 my-4 py-3">
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
                                        <span class="avatar-initial rounded-circle bg-success">
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

                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link @if(($selected_nav ?? '') === 'details') active @endif "
                               aria-current="page" href="#">{{__('Details')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{$base_url}}/app/view-assignment-tasks?id={{$project->id}}">{{__('Tasks')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{$base_url}}/app/view-assignment-resources?id={{$project->id}}">{{__('Resources')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{$base_url}}/app/view-assignment-discussions?id={{$project->id}}">{{__('Discussion')}}</a>
                        </li>
                    </ul>


                </div>
            </div>
        </div>
    </div>



    <div class="row mb-5 mt-3">
        <div class="col-md-12">
            <div class="mt-lg-0 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="d-flex bg-label-success-soft border-radius-lg p-3 mb-4">
                                <p class="my-auto">
                                    <span class="text-secondary text-sm me-1">{{__('Related Goal')}}:</span>@if(isset($goals[$project->goal_id]))
                                        <span class="fs-5 text-dark fw-bolder">  {{$goals[$project->goal_id]->title}}

</span>
                                    @endif
                                    <span class="text-secondary text-sm ms-1"></span>
                                </p>
                            </div>
                        </div>
                        <h5 class="text-dark fw-bolder mt-4">{{__('Summary')}}</h5>
                        <div class="d-flex bg-gray-100 border-radius-lg p-3 mb-4">
                            <p class="my-auto">
                                <span class="text-secondary text-sm me-1"></span>{{$project->summary}}<span
                                        class="text-secondary text-sm ms-1"></span>
                            </p>
                        </div>
                        <h5 class="mt-4 text-dark fw-bolder">{{__('Description')}}</h5>
                        <div class="mt-4 text-sm mb-0">
                            {!! app_clean_html_content($project->description) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@section('scripts')

    <script>
        "use strict";
        window.addEventListener('DOMContentLoaded', () => {
            const body = document.querySelector('body');

            //on clock btn_edit_todo

            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn_edit_todo') || e.target.parentElement.classList.contains('btn_edit_todo') || e.target.parentElement.parentElement.classList.contains('btn_edit_todo')) {
                    e.preventDefault();

                    let todo_id = e.target.dataset.todoId || e.target.parentElement.dataset.todoId || e.target.parentElement.parentElement.dataset.todoId;

                    console.log(todo_id);

                    axios.post('/app/todos/get-todo?object_id=' + todo_id)
                        .then(function (response) {

                            let todo = response.data.todo;
                            document.getElementById('todo_title').value = todo.title;
                            document.getElementById('todo_description').value = todo.description;
                            document.getElementById('todo_date').value = todo.date;
                            document.getElementById('selected_todo_id').value = todo.id;
                            document.getElementById('todo_admin_id').value = todo.admin_id;

                            const todoModal = new bootstrap.Modal('#todoModal', {
                                keyboard: false
                            });

                            todoModal.show();


                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                }
            });


            const btn_add_task = document.getElementById('btn_add_task');

            btn_add_task.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('todo_title').value = '';
                document.getElementById('todo_description').value = '';
                document.getElementById('todo_date').value = '';
                document.getElementById('selected_todo_id').value = '';
                document.getElementById('todo_admin_id').value = '';

                const todoModal = new bootstrap.Modal('#todoModal', {
                    keyboard: false
                });

                todoModal.show();
            });

        });


    </script>

    <script>
        (function () {
            "use strict";
            document.addEventListener('DOMContentLoaded', () => {

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
                        axios.post('{{$base_url}}/app/todos/change-project-todo-status', data)
                            .catch(function (error) {
                                console.log(error);
                            });
                    });
                });
            });
        })();
    </script>
@endsection
