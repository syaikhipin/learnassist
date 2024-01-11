@extends(config('app.layout'))
@section('content')

    <form novalidate="novalidate" method="post" action="{{route('app.save-document')}}" id="form-document"
          data-form="refresh" data-btn-id="btn-save-document">

        <input type="hidden" name="uuid" value="{{$document->uuid}}">
        <textarea id="app-editor-content" name="content"
                  class="d-none">{!! app_clean_html_content($document->content) !!}</textarea>

        <div class="mb-3">
            <input class="form-control" name="title" placeholder="{{__('Document Name')}}" value="{{$document->title}}">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="mb-3 btn-group">

                @if($document->type == 'word')

                    <div class="btn-group">
                        @if($document->type == 'word')
                            @if(!empty($super_settings['openai_api_key']))
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modal_ask_ai_to_write">{{__('Ask AI to Write')}}</button>
                            @endif
                        @endif
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            {{__('Download')}}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                   href="{{$base_url}}/app/download-document?uuid={{$document->uuid}}&access_key={{$document->access_key}}&type=pdf">{{__('PDF')}}</a>
                            </li>
                            <li><a class="dropdown-item"
                                   href="{{$base_url}}/app/download-document?uuid={{$document->uuid}}&access_key={{$document->access_key}}&type=docx">{{__('Word')}}</a>
                            </li>
                        </ul>
                    </div>
                @elseif($document->type == 'spreadsheet')
                    <button type="button" id="btn-download-xlsx" class="btn btn-primary d-none"
                            data-file-name="{{$document->title}}.xlsx">{{__('Download XLSX')}}</button>
                @endif

                <button type="button" class="btn btn-secondary"
                        data-app-modal="/app/app-modal/share-document?uuid={{$document->uuid}}"
                        data-app-modal-title="{{$document->title}}">{{__('Share')}}</button>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" id="btn-save-document">{{__('Save')}}</button>

            </div>
        </div>
        <div>

            @switch($document->type)

                @case('word')
                <div id="toolbar-container"></div>
                <div class="app-document-canvas">
                    <div class="app-document" id="app-document-editor">
                        {!! app_clean_html_content($document->content) !!}
                    </div>
                </div>

                @break

                @case('spreadsheet')

                <div id="loading_state">{{__('Loading')}}...</div>
                <div class="table-responsive">
                    <div id="app-spreadsheet-editor"
                         data-load-url="{{$base_url}}/app/load-document?uuid={{$document->uuid}}&access_key={{$document->access_key}}"
                         data-save-url="{{$base_url}}/app/save-document?uuid={{$document->uuid}}"></div>
                </div>

                @break
            @endswitch
        </div>
    </form>

    @if($document->type == 'word')
        @include('app.common.editor')
    @elseif($document->type == 'spreadsheet')
        <script src="/assets/lib/xlsx.full.min.js?v=4"></script>
    @endif

@endsection
