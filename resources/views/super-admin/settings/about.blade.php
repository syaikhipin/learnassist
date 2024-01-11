<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h4>{{config('app.name')}}</h4>
                <p>{{__('App Version')}}: {{config('app.version')}}</p>
                <p>{{__('Running on')}}: {{__('PHP')}}-{{PHP_VERSION ?? ''}}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4>{{__('System API')}}</h4>
                @if(empty($system_api))
                    <a href="/super-admin/system-api/generate" class="btn btn-primary mb-3">{{__('Generate')}}</a>
                @else
                    <div>
                        <div class="mb-2">
                            <input class="form-control" type="password" id="app_system_api" value="{{$system_api->api_key}}">
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-dark" id="buttonCopySystemApi">{{__('Copy')}}</button>
                            <button class="btn btn-success" id="buttonShowSystemApi">{{__('Show')}}</button>
                            <a href="/super-admin/system-api/regenerate" class="btn btn-primary">{{__('Regenerate')}}</a>
                            <a href="/super-admin/system-api/delete" class="btn btn-danger">{{__('Delete')}}</a>
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const app_system_api = document.getElementById('app_system_api');
        const buttonShowSystemApi = document.getElementById('buttonShowSystemApi')
        const buttonCopySystemApi = document.getElementById('buttonCopySystemApi')

        buttonShowSystemApi.addEventListener('click', () => {
            if (app_system_api.type === 'password') {
                app_system_api.type = 'text';
                buttonShowSystemApi.innerText = '{{__('Hide')}}';
            } else {
                app_system_api.type = 'password';
                buttonShowSystemApi.innerText = '{{__('Show')}}';
            }
        });

        buttonCopySystemApi.addEventListener('click', () => {
            const hiddenTextArea = document.createElement("textarea");
            hiddenTextArea.value = '{{$system_api->api_key ?? __('No API key found.')}}';

            // Ensure the textarea is not visible.
            hiddenTextArea.style.position = 'fixed';
            hiddenTextArea.style.left = '-9999px';

            document.body.appendChild(hiddenTextArea);
            hiddenTextArea.focus();
            hiddenTextArea.select();

            try {
                document.execCommand('copy');

                buttonCopySystemApi.innerText = '{{__('Copied')}}';

                setTimeout(() => {
                    buttonCopySystemApi.innerText = '{{__('Copy')}}';
                }, 2000);

            } catch (err) {

            }
            document.body.removeChild(hiddenTextArea);
        });

    });
</script>
