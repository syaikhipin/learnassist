@extends(config('app.layout'))
@section('content')
    <div class=" row">
        <div class="col">
            <h4 class="text-dark fw-bolder">
                {{__('Study Goals')}}
            </h4>
        </div>
        <div class="col text-end">
            <a href="{{$base_url}}/app/new-studygoal" type="button" class="btn btn-primary">
                {{__('Create New Studygoal')}}
            </a>
            <a href="{{$base_url}}/app/goal-categories" type="button" class="btn btn-dark">
                {{__('Categories')}}
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="mb-4">
            <div class="card h-100">
                <div class="">
                    <ul class="list-group list-group-flush" data-toggle="checklist">
                        @foreach($to_learns as $todo)
                            <li class="list-group-item border-0 flex-column align-items-start ps-0 py-0 mb-3">
                                <div class="checklist-item checklist-item-primary ps-2 ms-3">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input todo_checkbox" type="checkbox"
                                                   data-id="{{$todo->id}}" @if($todo->completed) checked @endif>
                                        </div>
                                        <a href="{{$base_url}}/app/view-studygoal/?id={{$todo->id}}">
                                            <h6 class="mb-0 me-3 text-dark font-weight-bold text-sm fw-bold">{{$todo->title}}</h6>
                                        </a>
                                        @if(!empty($categories[$todo->category_id]))
                                            @if(isset($categories[$todo->category_id]))
                                                <span class="badge bg-primary"> {{$categories[$todo->category_id]->name}}</span>

                                            @endif
                                        @endif

                                        <div class="dropdown float-lg-end ms-auto pe-4">
                                            <div class="btn-group" role="group" aria-label="Basic example"><a
                                                        class="btn btn-link text-dark px-3 mb-0"
                                                        href="{{$base_url}}/app/new-studygoal/?id={{$todo->id}}"><i
                                                            class="fas fa-pencil-alt text-dark me-2"
                                                            aria-hidden="true"></i><i class="bi bi-pencil"></i></a>
                                                <a class="btn btn-link text-dark px-3 mb-0" data-delete-item="true"
                                                   href="{{$base_url}}/app/delete/study-goal/{{$todo->uuid}}"><i
                                                            class="bi bi-trash3-fill"></i></a>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center ms-4 mt-1 ps-1">
                                        <div>
                                            <p class="mb-2 font-weight-bold text-sm">{{$todo->reason}}</p>{{__('Created')}}: <span class="badge bg-label-success me-3">{{date('d M Y',strtotime($todo->start_date))}}</span>{{__('Finish by')}}:
                                            <span class="badge bg-label-secondary">{{date('d M Y',strtotime($todo->end_date))}}</span>
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
                        axios.post('{{$base_url}}/app/goals/change-status', data)
                            .catch(function (error) {
                                console.log(error);
                            });
                    });
                });


            });
        })();
    </script>
@endsection





