<div class="modal fade" id="create_prompt">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Prompt')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate="novalidate" method="post" action="/app/ai-prompts/save-prompt" id="form-prompt"
                      data-form="refresh" data-btn-id="btn-save-prompt">

                    <div class="mb-3">
                        <label for="input_prompt">{{__('Prompt')}}</label>
                        <textarea rows="6" id="input_prompt" name="prompt" class="form-control"></textarea>
                    </div>

                    <input type="hidden" name="uuid" id="prompt_id" value="">

                    <button type="submit" class="btn btn-primary" id="btn-save-prompt">{{__('Save')}}</button>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ai_prompt_create_document">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{__('Prompt')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate="novalidate" method="post" action="/app/ai-prompts/create-document"
                      id="form-create-document" data-form="redirect" data-btn-id="btn-create-document">

                    <div class="mb-3">
                        <textarea rows="6" id="input_prompt_ai_prompt_create_document" name="prompt"
                                  class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-create-document">{{__('Create')}}</button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        "use strict";
        document.addEventListener('DOMContentLoaded', () => {

            const ai_prompt_wrapper = document.getElementById('ai_prompt_wrapper');

            const createDocumentModal = new bootstrap.Modal('#ai_prompt_create_document', {
                keyboard: false
            });

            const editPromptModal = new bootstrap.Modal('#create_prompt', {
                keyboard: false
            });

            ai_prompt_wrapper.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn_copy_prompt') || e.target.closest('.btn_copy_prompt')) {

                    let prompt = e.target.closest('.single_prompt').querySelector('.prompt_text').innerText;

                    //Create a textarea element
                    const el = document.createElement('textarea');
                    // Set value (string to be copied)
                    el.value = prompt;
                    // Set non-editable to avoid focus and move outside of view
                    el.setAttribute('readonly', '');
                    el.style = {position: 'absolute', left: '-9999px'};
                    document.body.appendChild(el);
                    // Select text inside element
                    el.select();
                    // Copy text to clipboard
                    document.execCommand('copy');

                    notyf.success({
                        message: '{{__('Copied')}}',
                    });

                } else if (e.target.classList.contains('btn_ai_prompt_create_document_using_this_prompt') || e.target.closest('.btn_ai_prompt_create_document_using_this_prompt')) {

                    document.getElementById('input_prompt_ai_prompt_create_document').value = e.target.closest('.single_prompt').querySelector('.prompt_text').innerText;

                    createDocumentModal.show();

                } else if (e.target.classList.contains('btn_edit_prompt') || e.target.closest('.btn_edit_prompt')) {

                    let prompt = e.target.closest('.single_prompt').querySelector('.prompt_text').innerText;
                    let prompt_id = e.target.closest('.single_prompt').querySelector('.prompt_text').getAttribute('data-prompt-id');

                    document.getElementById('input_prompt').value = prompt;
                    document.getElementById('prompt_id').value = prompt_id;

                    editPromptModal.show();

                }
            });

        });
    })();
</script>
