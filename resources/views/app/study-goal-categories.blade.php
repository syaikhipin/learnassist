@extends(config('app.layout'))
@section('content')

    <div class=" row mb-2">
        <div class="col">
            <h4 class=" text-dark fw-bolder">
                {{__('Studygoal Categories')}}
            </h4>

        </div>
        <div class="col text-end">

            <button type="button" class="btn btn-dark mb-3" id="btn_add_new_category"><i class="fas fa-plus"></i>&nbsp;&nbsp; {{__(' Add New Category')}}</button>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="">
                <div class="table-responsive text-nowrap">
                    <table id="app-data-table" class="table table-light table-borderless table-responsive">
                        <thead>
                        <tr>
                            <th>{{__('NAME')}}</th>
                            <th>{{__('CREATED')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="text-dark fw-bolder">
                                    <div class="d-flex">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary">{{$category->name['0']}}</span>
                                        </div>
                                        <strong> {{$category->name}}</strong>

                                    </div>

                                </td>
                                <td> {{date('d M Y',strtotime($category->created_at))}}</td>
                                <td>
                                    <div class="float-lg-end ms-auto pe-4">
                                        <a class="btn btn-link text-dark ms-4 mb-0 category_edit"
                                           href="#" data-id="{{$category->id}}">

                                            <i class="bi bi-pencil"></i>

                                        </a>
                                        <a class="btn btn-link text-dark  mb-0"  data-delete-item="true"
                                           href="{{$base_url}}/app/delete/goal-category/{{$category->uuid}}">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="app_modal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title" id="modal_title">{{__('Add New Category')}}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="sp_result_div"></div>
                    <form method="post" action="{{$base_url}}/app/save-studygoal-category" id="form_main" class="">
                        <!-- Form -->
                        <div class="form-group mb-4">
                            <label for="email">{{__('Name')}}</label>
                            <div class="input-group">

                                <input type="text" name="name" class="form-control"  id="input_name" autofocus required>
                            </div>
                        </div>
                        <!-- End of Form -->

                        @csrf
                        <button  type="submit" id="btn_submit" class="btn btn-dark">{{__('Save')}}</button>
                        <input type="hidden" name="category_id" id="category_id" value="">

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
                const app_body = document.getElementById('app_body');

                const myModal = new bootstrap.Modal(document.getElementById('app_modal'), {
                    keyboard: false
                });

                const modal_title = document.getElementById('modal_title');

                const btn_add_new_category = document.getElementById('btn_add_new_category');

                btn_add_new_category.addEventListener('click', () => {
                    document.getElementById('input_name').value = '';
                    document.getElementById('category_id').value = '';
                    modal_title.innerHTML = '{{__('Add New Category')}}';
                    myModal.show();
                });

                app_body.addEventListener('click', (event) => {

                    //Check if edit button is clicked or parent of edit button is clicked

                    if (event.target.matches('.category_edit') || event.target.matches('.category_edit *') ) {

                        let category_id = event.target.dataset.id ?? event.target.parentElement.dataset.id;

                        axios.get('/app/get-study-goal-category?category_id='+category_id)
                            .then(function (response) {

                                let data = response.data;

                                if(data.status === 'success'){

                                    let category = data.category;

                                    document.getElementById('input_name').value = category.name;
                                    document.getElementById('category_id').value = category.id;

                                    modal_title.innerHTML = '{{__('Edit Category')}}';

                                    myModal.show();

                                }

                            })
                            .catch(function (error) {
                                console.log(error);
                            });


                        myModal.show();

                    }

                });

            });
        })();
    </script>

@endsection



