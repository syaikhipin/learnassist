@extends(config('app.layout'))
@section('content')

    @include('app.common.recent-documents')

    <div class="card">
        <div class="card-body">
            <h4 class="text-dark fw-bolder">{{__('Documents')}}</h4>
            <table id="app-data-table" class="table table-light">
                <thead>
                <tr>
                    <th>{{__('Title')}}</th>
                    <th>{{__('Author')}}</th>
                    <th>{{__('Last Update')}}</th>
                    <th class="text-end">{{__('Manage')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td class="fw-bold text-dark">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary">{{$document->title['0']}}</span>
                                </div>
                                <a class="text-dark"
                                   href="{{$base_url}}/app/document?uuid={{$document->uuid}}">{{$document->title}}</a>


                            </div>
                        </td>

                        <td>
                            @if(!empty($users[$document->user_id]))
                                {{$users[$document->user_id]->first_name}} {{$users[$document->user_id]->last_name}}
                            @endif
                        </td>
                        <td @if($document->updated_at) data-order="{{$document->updated_at->getTimestamp()}}" @endif>
                            @if($document->updated_at) {{$document->updated_at->diffForHumans()}} @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button type="button"
                                        class="btn btn-outline-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                    <li><a class="dropdown-item"
                                           href="{{$base_url}}/app/document?uuid={{$document->uuid}}">{{__('Open')}}</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                           data-app-modal="/app/app-modal/share-document?uuid={{$document->uuid}}"
                                           data-app-modal-title="{{$document->title}}" href="#">{{__('Share')}}</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item"
                                           href="{{$base_url}}/app/download-document?uuid={{$document->uuid}}&access_key={{$document->access_key}}&type=pdf">{{__('PDF')}}</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                           href="{{$base_url}}/app/download-document?uuid={{$document->uuid}}&access_key={{$document->access_key}}&type=docx">{{__('Word')}}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" data-delete-item="true"
                                           href="{{$base_url}}/app/delete/document/{{$document->uuid}}">{{__('Delete')}}</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('app.common.create-document')

@endsection
