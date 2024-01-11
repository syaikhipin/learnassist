@extends(config('app.layout'))
@section('content')

    <form novalidate="novalidate" method="post" action="/app/flashcards/save-card" id="form-document"
          data-form="refresh" data-btn-id="btn-save-document">

        <input type="hidden" name="uuid" value="{{$card->uuid}}">
        <textarea id="app-editor-content" name="description"
                  class="d-none">{!! app_clean_html_content($card->content) !!}</textarea>

        <div class="mb-3">
            <input class="form-control" name="title" placeholder="{{__('Title')}}" value="{{$card->title}}">
        </div>

        <input type="hidden" name="collection_id" id="card_collection_id" value="{{$card->collection_id}}">

        <button type="submit" class="btn btn-primary mb-3" id="btn-save-document">{{__('Save')}}</button>

        <div id="toolbar-container"></div>
        <div class="app-document-canvas">
            <div class="app-document" id="app-document-editor">
                {!! app_clean_html_content($card->description) !!}
            </div>
        </div>

    </form>


    @include('app.common.editor')
@endsection
