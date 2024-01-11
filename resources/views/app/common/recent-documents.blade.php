<div class="mb-3">
    <div class="d-flex  justify-content-between align-items-center mb-3">
        <div class="card-title mb-2">
            <h5 class="m-0 me-2 text-dark fw-bold">{{__('Recent Notes')}}</h5>
            <small class="text-muted">{{__('Check recent notes')}}</small>
        </div>


        <div>
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#create_document">
                {{__('New Note')}}
            </button>
        </div>
    </div>
    <div class="row ">
        <div class="card-group shadow-none">
            @foreach($recent_documents as $recent_document)

                <div class="col-md-3">
                    <a href="{{$base_url}}/app/document?uuid={{$recent_document->uuid}}">
                        <div class="card  h-100">

                            <div class="card-body  p-5 text-dark">
                                <div class="d-flex">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-light">{{$recent_document->title['0']}}</span>
                                    </div>
                                    <div class="mb-2 fw-bold">{{$recent_document->title}}</div>


                                </div>
                                <div class="mb-2 mt-2 text-xss">{!! app_html_to_text($recent_document->content, 120) !!}
                                    .........
                                </div>
                                <hr class="my-2">

                                <div>
                                    <div class="d-flex">
                                        <div class="me-1">
                                            <span class=" bg-label-primary"><i class="bi bi-file-earmark-text-fill"></i></span>
                                        </div>
                                        <div class="mb-1"><span
                                                    class="text-muted">{{__('Opened')}}:</span> {{$recent_document->last_opened_at->diffForHumans()}}
                                        </div>
                                    </div>

                                    @if(!empty($users[$recent_document->user_id]))
                                        <div>
                                            <span class="text-muted">{{__('By')}}:</span> {{$users[$recent_document->user_id]->first_name}} {{$users[$recent_document->user_id]->last_name}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</div>
