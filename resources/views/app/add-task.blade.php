@extends(config('app.layout'))
@section('head')
    <link rel="stylesheet" href="/assets/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/lib/select2/css/select2.css">
@endsection
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bolder">{{ __('Add To-dos') }}</h4>
        <div>
            <a href="{{$base_url}}/app/todos" type="submit" class="btn btn-dark float-end">{{ __('Go to List') }}</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form action="{{$base_url}}/app/save-todos" method="post" id="save-todo" data-form="redirect"
                          data-btn-id="btn-save-todo">
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
                                   class="form-label">{{ __('Description') }}</label><span class="text-danger">*</span>
                            <textarea class="form-control" name="description" id="description"
                                      rows="3">@if (!empty($todo)) {{$todo->description}}@endif  </textarea>
                        </div>
                        @csrf
                        @if($todo)
                            <input type="hidden" name="object_id" value="{{$todo->id}}">
                        @endif
                        <button type="submit" class="btn btn-dark" id="btn-save-todo">{{ __('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header p-3">
                    <h4 class="mb-0 text-dark">{{__('To-do list')}}</h4>
                    <hr class="horizontal dark mb-0">
                </div>
                <div class="card-body p-3 pt-0">
                    <ul class="list-group list-group-flush" data-toggle="checklist">
                        @foreach($todos as $todo)
                            <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                <div class="checklist-item checklist-item-primary ps-2 ms-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input todo_checkbox" type="checkbox"
                                                   data-id="{{$todo->id}}" @if($todo->completed) checked @endif>
                                        </div>
                                        <h6 class="mb-0 text-dark font-weight-bold text-sm">{{$todo->title}}</h6>
                                        <div class="dropdown float-lg-end ms-auto pe-4">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{$base_url}}/app/add-task/?id={{$todo->id}}" type="button"
                                                   class="btn btn-icon btn-sm btn_copy_prompt btn-primary"><i
                                                            class="bi bi-pencil"></i></a>

                                                <a href="{{$base_url}}/app/delete/todo/{{$todo->uuid}}" type="button" data-delete-item="true" class=" btn-sm btn btn-icon btn-danger btn_edit_prompt"><i
                                                            class="bi bi-trash"></i></a>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center ms-4 mt-3 ps-1">
                                        <div>
                                            <span class="text-xs font-weight-bolder">
                                                {{date('d M Y',strtotime($todo->date))}}
                                            </span>
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
