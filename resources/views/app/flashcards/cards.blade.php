@extends(config('app.layout'))
@section('content')

    <div class="row">
        <div class="col-md-8">
            <h4 class="text-dark fw-bolder">{{$collection->title}}</h4>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#create_card">{{__('Add Card')}}</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="fs-5">{{__('Manage Cards')}}</h3>
            <table id="app-data-table" class="table table-light">
                <thead>
                <tr>
                    <th>{{__('Title')}}</th>
                    <th>{{__('Last Update')}}</th>
                    <th class="text-end">{{__('Manage')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cards as $card)
                    <tr>
                        <td>
                            <a class="text-dark fw-bold" href="{{$base_url}}/app/flashcards/card?uuid={{$card->uuid}}">{{$card->title}}</a>
                        </td>
                        <td @if($card->updated_at) data-order="{{$card->updated_at->getTimestamp()}}" @endif>
                            @if($card->updated_at) {{$card->updated_at->diffForHumans()}} @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button type="button"
                                        class="btn btn-sm btn-outline-dark btn-icon rounded-pill dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                    <li><a class="dropdown-item"
                                           href="{{$base_url}}/app/flashcards/card?uuid={{$card->uuid}}">{{__('Edit')}}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" data-delete-item="true"
                                           href="{{$base_url}}/app/delete/flashcard/{{$card->uuid}}">{{__('Delete')}}</a>
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


    <div class="modal fade" id="create_card">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{__('New Card')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form novalidate="novalidate" method="post" action="/app/flashcards/save-card" id="form-card"
                          data-form="redirect" data-btn-id="btn-save-card">

                        <div class="mb-3">
                            <label for="card_input_title">{{__('Title')}}</label>
                            <input type="text" class="form-control" required id="card_input_title" name="title">
                        </div>

                        <input type="hidden" name="collection_id" id="card_collection_id" value="{{$collection->id}}">

                        <button type="submit" class="btn btn-primary" id="btn-save-card">{{__('Save')}}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
