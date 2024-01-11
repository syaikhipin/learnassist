@extends(config('app.layout'))
@section('head')
    <link rel="stylesheet" href="/assets/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/lib/select2/css/select2.css">
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bolder">{{__('To-do list')}}</h4>
        <div class="float-end">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                {{__('Add To-dos')}}
            </button>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('Add Task')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="">
                            <form action="{{$base_url}}/app/save-todos" method="post" id="save-todo"
                                  data-form="redirect" data-btn-id="btn-save-todo">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="list-unstyled">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="example-date-input"
                                           class="form-control-label form-label">{{ __('Deadline') }}</label>
                                    <input class="form-control" name="date" value="{{date('Y-m-d')}}" id="date">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="example-text-input"
                                           class="form-control-label form-label">{{ __('Task') }}</label><span
                                            class="text-danger">*</span>
                                    <input class="form-control" name="title" type="text" id="title"
                                           @if (!empty($todo)) value="{{$todo->title}}"@endif>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="example-text-input" class="form-label">
                                        {{__('Priority')}}
                                    </label><span class="text-danger">*</span>
                                    <select class="form-select" aria-label="Default select example" name="status">
                                        <option value="High"
                                                @if(($todo->status ?? null) === 'High') selected @endif>{{__('High')}}</option>
                                        <option value="Medium"
                                                @if(($todo->status ?? null) === 'Medium') selected @endif>{{__('Medium')}}</option>
                                        <option value="Low"
                                                @if(($todo->status ?? null) === 'Low') selected @endif>{{__('Low')}}</option>
                                    </select>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="exampleFormControlTextarea1"
                                           class="form-label">{{ __('Description') }}</label><span
                                            class="text-danger">*</span>
                                    <textarea class="form-control" name="description" id="description"
                                              rows="3">@if (!empty($todo)) {{$todo->description}}@endif  </textarea>
                                </div>

                                @csrf
                                @if($todo)
                                    <input type="hidden" name="id" value="{{$todo->id}}">
                                @endif
                                <button type="submit" class="btn btn-primary"
                                        id="btn-save-todo">{{ __('Save') }}</button>

                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="mb-4">
            <div class="card h-100">
                <div class="mt-2">
                    <ul class="list-group list-group-flush" data-toggle="checklist">
                        @foreach($todos as $todo)
                            <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                <div class="checklist-item checklist-item-primary ps-2 ms-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input todo_checkbox" type="checkbox"
                                                   data-id="{{$todo->id}}"

                                                   @if($todo->completed) checked @endif

                                            >
                                        </div>
                                        <a href="{{$base_url}}/app/view-todo/?id={{$todo->id}}">
                                            <h6 class="mb-0 me-2 text-dark font-weight-bold text-sm">{{$todo->title}}</h6>
                                        </a>
                                        @if($todo->status == 'High')
                                            <span class="badge bg-label-danger"> {{$todo->status}}</span>

                                        @elseif($todo->status == 'Medium')
                                            <span class="badge bg-label-yellow">{{$todo->status}}</span>
                                        @elseif($todo->status == 'Low')
                                            <span class="badge bg-label-success">{{$todo->status}}</span>
                                        @endif


                                        <div class="dropdown float-lg-end ms-auto pe-4">

                                            <div class="btn-group" role="group" aria-label="Basic example"><a
                                                        class="btn btn-link text-dark px-3 mb-0"
                                                        href="{{$base_url}}/app/add-task/?id={{$todo->id}}"><i
                                                            class="fas fa-pencil-alt text-dark me-2"
                                                            aria-hidden="true"></i><i class="bi bi-pencil"></i></a>
                                                <a class="btn btn-link text-dark px-3 mb-0" data-delete-item="true"
                                                   href="{{$base_url}}/app/delete/todo/{{$todo->uuid}}"><i
                                                            class="bi bi-trash3-fill"></i></a>

                                            </div>


                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center ms-4 mt-3 ps-1">
                                        <div>

                                            {{__('Finish by')}}: <span
                                                    class="text-xs font-weight-bolder">{{date('d M Y',strtotime($todo->date))}}</span>
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




@endsection

@section('scripts')

    <script src="/assets/lib/flatpickr/flatpickr.js"></script>

    <script>
        (function () {
            "use strict";
            document.addEventListener('DOMContentLoaded', () => {
                flatpickr("#date", {

                    dateFormat: "Y-m-d",
                });


            });
        })();
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
                        axios.post('{{$base_url}}/app/todos/change-status', data)
                            .catch(function (error) {
                                console.log(error);
                            });
                    });
                });


            });
        })();
    </script>
@endsection




