@extends(config('app.layout'))
@section('content')

    <div class="row">
        <div class="col">
            <h4 class="text-dark fw-bolder">{{__('Flashcards')}}</h4>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary mb-3" id="btn_new_collection" data-bs-toggle="modal"
                    data-bs-target="#create_collection">
                {{__('Add New Collection')}}
            </button>
        </div>
    </div>

    <div class="row">
        @foreach($collections as $collection)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bolder text-dark">{{$collection->title}}</h5>
                        <p>{{$collection->description}}</p>
                        <a href="/app/flashcards/learn?uuid={{$collection->uuid}}"
                           class="btn btn-dark mb-2">{{__('Learn')}}</a>
                        <button type="button" class="btn btn-secondary mb-2 btn_add_card"
                                data-collection-id="{{$collection->id}}" data-bs-toggle="modal"
                                data-bs-target="#create_card">{{__('Add Card')}}</button>
                        <a href="/app/flashcards/cards?uuid={{$collection->uuid}}"
                           class="btn btn-primary btn-icon mb-2"><i class="bi bi-stack"></i> </a>
                        <button data-collection-id="{{$collection->uuid}}"
                                data-collection-title="{{$collection->title}}" data-bs-toggle="modal"
                                data-bs-target="#create_collection"
                                class="btn btn-dark btn-icon btn_edit_collection mb-2"><i
                                    class="bi bi-pencil btn_edit_collection" data-collection-id="{{$collection->uuid}}"
                                    data-collection-title="{{$collection->title}}"></i></button>
                        <a data-delete-item="true"
                           href="{{$base_url}}/app/delete/flashcard-collection/{{$collection->uuid}}"
                           class="btn btn-danger btn-icon mb-2"><i class="bi bi-trash3"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="create_collection">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{__('Collection')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form novalidate="novalidate" method="post" action="/app/flashcards/save-collection"
                          id="form-collection" data-form="refresh" data-btn-id="btn-save-collection">

                        <div class="mb-3">
                            <label for="input_title">{{__('Title')}}</label>
                            <input type="text" class="form-control" required id="input_title" name="title">
                        </div>

                        <input type="hidden" name="uuid" id="collection_id" value="">

                        <button type="submit" class="btn btn-primary" id="btn-save-collection">{{__('Save')}}</button>

                    </form>
                </div>
            </div>
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

                        <input type="hidden" name="collection_id" id="card_collection_id" value="">

                        <button type="submit" class="btn btn-primary" id="btn-save-card">{{__('Save')}}</button>

                    </form>
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

                let btn_add_card = document.querySelectorAll('.btn_add_card');

                btn_add_card.forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        let input_collection_id = document.querySelector('#card_collection_id');
                        input_collection_id.value = e.target.getAttribute('data-collection-id');
                    });
                });

                let btn_edit_collection = document.querySelectorAll('.btn_edit_collection');

                btn_edit_collection.forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        let collection_id = e.target.getAttribute('data-collection-id');
                        let input_title = document.querySelector('#input_title');
                        input_title.value = e.target.getAttribute('data-collection-title');
                        let input_collection_id = document.querySelector('#collection_id');
                        input_collection_id.value = collection_id;
                    });
                });

                let btn_new_collection = document.querySelector('#btn_new_collection');

                btn_new_collection.addEventListener('click', (e) => {
                    let input_title = document.querySelector('#input_title');
                    input_title.value = '';
                    let input_collection_id = document.querySelector('#collection_id');
                    input_collection_id.value = '';
                });

            });
        })();
    </script>
@endsection
