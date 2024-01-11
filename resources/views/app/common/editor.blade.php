@if(!empty($super_settings['openai_api_key']))
    <div class="modal fade" id="modal_ask_ai_to_write">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{__('Ask AI to Write')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="mb-3">
                        <label for="input_ai_content">{{__('What would you like me to write?')}}</label>
                        <input type="text" class="form-control" required id="input_ai_content"
                               placeholder="{{__('Ask me anything...')}}" name="content">
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-ask-ai-to-write">{{__('Write')}}</button>


                </div>
            </div>
        </div>
    </div>
@endif

<script src="/assets/lib/ckeditor/ckeditor.js?v=4"></script>
<script>
    (function () {
        "use strict";
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('app-document-editor')) {

                const app_editor_content = document.getElementById('app-editor-content');

                DecoupledDocumentEditor
                    .create(document.querySelector('#app-document-editor'), {
                        toolbar: {
                            items: [
                                'undo', 'redo', '|',
                                'heading', '|',
                                'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                'bulletedList', 'numberedList', 'todoList', '|',
                                'imageUpload', 'imageInsert', '|',
                                'outdent', 'indent', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'alignment', '|',
                                'link', 'blockQuote', 'insertTable', 'codeBlock', '|',
                                'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                'exportPdf', 'exportWord', 'print', 'preview',
                            ],
                            shouldNotGroupWhenFull: true,
                        },
                        simpleUpload: {
                            // The URL that the images are uploaded to.
                            uploadUrl: '{{$base_url}}/app/upload-document-image',

                            // Enable the XMLHttpRequest.withCredentials property.
                            withCredentials: true,

                            // Headers sent along with the XMLHttpRequest to the upload server.
                            headers: {
                                'X-CSRF-TOKEN': window.csrf_token,
                            }
                        },
                    })
                    .then(editor => {
                        const toolbarContainer = document.querySelector('#toolbar-container');

                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                        // on change
                        editor.model.document.on('change:data', () => {
                            app_editor_content.value = editor.getData();
                        });

                        window.app_editor = editor;

                    })
                    .catch(error => {
                        console.error(error);
                    });

                @if(!empty($super_settings['openai_api_key']))

                const modal_ask_ai_to_write = new bootstrap.Modal('#modal_ask_ai_to_write', {
                    keyboard: false
                });

                const btn_ask_ai_to_write = document.getElementById('btn-ask-ai-to-write');

                btn_ask_ai_to_write.addEventListener('click', function (e) {
                    e.preventDefault();
                    let ai_content = document.getElementById('input_ai_content').value;
                    if (ai_content.length > 0) {
                        btn_ask_ai_to_write.innerHTML = '{{__('Writing...')}}';
                        btn_ask_ai_to_write.disabled = true;
                        axios.post('/app/ask-ai-to-write', {
                            content: ai_content,
                        })
                            .then(function (response) {
                                if (response.data.status === 'success') {
                                    document.getElementById('app-editor-content').value = response.data.data;
                                    document.getElementById('app-document-editor').innerHTML = response.data.data;
                                    modal_ask_ai_to_write.hide();
                                }
                                btn_ask_ai_to_write.innerHTML = '{{__('Write')}}';
                                btn_ask_ai_to_write.disabled = false;
                                modal_ask_ai_to_write.hide();

                                document.getElementById('input_ai_content').value = '';

                                if (window.app_editor) {
                                    let result = response.data.result;
                                    if (result) {
                                        result = app_editor_content.value + result;
                                        app_editor_content.value = result;
                                        window.app_editor.setData(result);
                                    }
                                }

                            })
                            .catch(function (error) {
                                console.log(error);
                            })
                    }
                });

                @endif

            }


        });
    })();
</script>
