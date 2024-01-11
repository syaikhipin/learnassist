@extends(config('app.layout'))
@section('head')
    <link rel="stylesheet" href="/assets/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/lib/select2/css/select2.css">
@endsection
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="text-dark fw-bolder">{{__('New Study Goal')}}</h4>
            </div>

            <div class="card-body">
                <form action="{{$base_url}}/app/save-studygoal" method="post" id="studygoal" data-form="redirect"
                      data-btn-id="btn-studygoal">
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
                        <label for="example-text-input"
                               class="form-control-label">{{__('What topic do you want to learn')}}</label><span
                                class="text-danger">*</span>
                        <input class="form-control" type="text" name="title"
                               @if (!empty($to_learn)) value="{{$to_learn->title}}" @endif id="example-text-input">
                    </div>

                    <div class="form-group mt-4">
                        <label for="example-text-input" class="form-control-label">{{__('Studying for')}}</label><span
                                class="text-danger">*</span>
                        <input class="form-control" type="text" name="reason"
                               @if (!empty($to_learn)) value="{{$to_learn->reason}}" @endif id="example-text-input">
                    </div>
                        <div class="form-group mt-4">
                            <label for="example-text-input" class="form-control-label">{{__('Choose a Category')}}</label><span
                                    class="text-danger">*</span>
                            <select class="form-control" name="category_id" id="choices-category-edit">
                                <option value="0">{{__('None')}}</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}"
                                            @if (!empty($to_learn))
                                            @if ($to_learn->category_id === $to_learn->id)
                                            selected
                                            @endif
                                            @endif
                                    >{{$category->name}} </option>
                                @endforeach
                            </select>

                        </div>


                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-date-input" class="form-control-label">{{__('Start Date')}}</label>
                                <input class="form-control" name="start_date" id="start_date"
                                       @if(!empty($to_learn))
                                       value="{{$to_learn->start_date}}"
                                       @else
                                       value="{{date('Y-m-d')}}"
                                        @endif>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-date-input" class="form-control-label">{{__('End Date')}}</label>
                                <input class="form-control" name="end_date" id="end_date" @if(!empty($to_learn))
                                value="{{$to_learn->end_date}}"
                                       @else
                                       value="{{date('Y-m-d')}}"
                                        @endif>
                            </div>

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
                            {!! app_clean_html_content($to_learn->description ?? '') !!}
                        </div>
                    </div>

                    @csrf
                    @if($to_learn)
                        <input type="hidden" name="object_id" value="{{$to_learn->id}}">
                    @endif
                    <button type="submit" id="btn-studygoal" class="btn btn-dark mt-4">{{__('Save')}}</button>

                </form>

            </div>

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




