@extends(config('app.layout'))
@section('head')
    <link rel="stylesheet" href="/assets/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/lib/select2/css/select2.css">
@endsection

@section('content')
    <div class="row">
        <div class="col">
        </div>
        <!-- Modal -->
        <div class="col text-end">
            <a href="{{$base_url}}/app/assignments" type="button"
               class="btn btn-primary text-white">{{__('Assignments')}}</a>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <form action="{{$base_url}}/app/save-project" id="project-form" method="post" data-form="redirect"
                  data-btn-id="btn-save-project">
                @if ($errors->any())
                    <div class="alert bg-pink-light text-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-lg-9 col-12 mx-auto">
                    <div class="">
                        <h4 class="mb-0 fw-bolder text-dark ">{{__('New Assignment/Study plan')}}</h4>
                        <p class="text-sm mb-0">{{__('Create new assignment')}}</p>
                        <hr class="horizontal dark my-3">
                        <div>
                            <label for="projectName" class="form-label">{{__('Title')}}</label><label
                                    class="text-danger">*</label>
                        </div>

                        <input type="text" value="{{$project->title ?? old('title') ?? ''}}" name="title"
                               class="form-control" id="projectName">
                        <div class=" row mt-3">

                            <div class="col-md-6">
                                <label for="exampleFormControlInput1" class="form-label">{{__('Related Goal')}}</label>
                                <select class="form-select form-select-solid fw-bolder" id="contact"
                                        aria-label="Floating label select example" name="goal_id">
                                    <option value="0">{{__('None')}}</option>
                                    @foreach ($studygoals as $goal)
                                        <option value="{{$goal->id}}"
                                                @if (!empty($project))
                                                @if ($project->goal_id === $goal->id)
                                                selected
                                                @endif
                                                @endif
                                        >{{$goal->title}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="example-text-input" class="form-label">
                                        {{__('Status')}}
                                    </label><span class="text-danger">*</span>
                                    <select class="form-select" aria-label="Default select example" name="status">
                                        <option value="Pending"
                                                @if(($project->status ?? null) === 'Pending') selected @endif>{{__('Pending')}}</option>
                                        <option value="Started"
                                                @if(($project->status ?? null) === 'Started') selected @endif>{{__('Started')}}</option>
                                        <option value="Finished"
                                                @if(($project->status ?? null) === 'Finished') selected @endif>{{__('Finished')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <label class="mt-4 form-label mb-0">{{__('Assignment Summary')}}</label>

                        <div class="form-group">
                            <textarea name="summary" class="form-control" rows="4"
                                      id="editor">{{$project->summary ?? old('summary') ?? ''}}</textarea>
                            <p class="form-text text-primary text-xs ms-1">
                                {{__('Write a short summary.')}}
                            </p>
                        </div>


                        <div class="col-md-12 mt-3">
                            <div>
                                <label for="exampleFormControlInput1"
                                       class="form-label">{{__('Select Team /Group Members /Study buddies')}}</label><span
                                        class="text-danger">*</span>
                                <select class="form-control select2" multiple id="" name="members[]">
                                    @foreach ($other_users as $other_user)
                                        <option value="{{$other_user->id}}"
                                                @if($members)

                                                @if(in_array($other_user->id,$members)) selected @endif
                                                @endif
                                        >{{$other_user->first_name}} {{$other_user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <label class="form-label">{{__('Start Date')}}</label>
                                <input class="form-control" name="start_date" id="start_date"
                                       @if(!empty($project))value="{{$project->start_date}}"
                                       @else
                                       value="{{date('Y-m-d')}}"
                                        @endif >
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{__('End Date')}}</label>
                                <input class="form-control" name="end_date" id="end_date" @if(!empty($project))
                                value="{{$project->end_date}}"
                                       @else
                                       value="{{date('Y-m-d')}}"
                                        @endif>
                            </div>
                        </div>
                        <label class="mt-4 text-sm mb-0 form-label">{{__('Project Description')}}</label>
                        <p class="form-text text-primary text-xs ms-1">
                            {{__('Write description about your project.')}}
                        </p>
                        <div class="form-group">
                            <textarea rows="10" id="app-editor-content" name="description" class="d-none"></textarea>

                        </div>
                        <div id="toolbar-container"></div>
                        <div class="app-document-canvas">
                            <div class="app-document" id="app-document-editor">
                                {!! app_clean_html_content($project->description ?? '') !!}
                            </div>
                        </div>

                        @csrf
                        @if($project)
                            <input type="hidden" name="object_id" value="{{$project->id}}">
                        @endif
                        <div class="d-flex mt-4">
                            <button type="submit" id="btn-save-project" class="btn btn-primary
                             m-0 ">
                                {{__('Save')}}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('app.common.editor')
@endsection

@section('scripts')
    <script src="/assets/lib/flatpickr/flatpickr.js"></script>
    <script>
        (function () {
            "use strict";
            document.addEventListener('DOMContentLoaded', () => {
                flatpickr("#start_date", {

                    dateFormat: "Y-m-d",
                });

                flatpickr("#end_date", {

                    dateFormat: "Y-m-d",
                });
            });
        })();
    </script>
@endsection

