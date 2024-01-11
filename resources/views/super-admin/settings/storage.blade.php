<div class="row mb-5">
    <div class="col-md-6 mt-lg-0 mt-4">
        <div class="card">
            <div class="card-body">
                <form novalidate="novalidate" action="{{route('app.save-settings')}}" method="post" id="form-storage-settings" data-form="refresh" data-btn-id="btnSaveStorage">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-4" id="basic-info">
                        <div class=" pt-0">

                            <div class="mb-4">
                                <label class="form-label" for="uploads_driver">{{__('Filesystem Disk')}}</label>
                                <select class="form-select" name="uploads_driver" id="uploads_driver">
                                    <option value="local" @if((config('filesystems.disks.uploads.driver') ?? 'local') === 'local') selected @endif>{{__('Local')}}</option>
                                    <option value="s3" @if((config('filesystems.disks.uploads.driver') ?? '') === 's3') selected @endif>{{__('S3')}}</option>
                                </select>
                            </div>

                            @if((config('filesystems.disks.uploads.driver') ?? '') === 's3')

                                <div class="mb-4">
                                    <label class="form-label" for="uploads_access_key_id">{{__('S3 Access Key Id')}}</label>
                                    <input id="uploads_access_key_id" name="uploads_access_key_id" value="{{config('filesystems.disks.uploads.key')}}"
                                           class="form-control" type="text">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="uploads_secret_access_key">{{__('S3 SECRET ACCESS KEY')}}</label>
                                    <input id="uploads_secret_access_key" name="uploads_secret_access_key" value="{{config('filesystems.disks.uploads.secret')}}"
                                           class="form-control" type="text">
                                </div>


                                <div class="row mb-4">
                                    <label class="form-label">{{__('S3 DEFAULT REGION')}}</label>

                                    <div class="input-group">
                                        <input id="uploads_default_region" name="uploads_default_region" value="{{config('filesystems.disks.uploads.region')}}"
                                               class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="form-label">{{__('S3 BUCKET')}}</label>

                                    <div class="input-group">
                                        <input id="uploads_bucket" name="uploads_bucket" value="{{config('filesystems.disks.uploads.bucket')}}"
                                               class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">{{__('S3 ENDPOINT')}}</label>

                                    <div class="input-group">
                                        <input id="uploads_endpoint" name="uploads_endpoint" value="{{config('filesystems.disks.uploads.endpoint')}}"
                                               class="form-control" type="text">
                                    </div>
                                </div>


                            @endif



                            @csrf

                            <input type="hidden" name="type" value="storage">

                            <button type="submit" id="btnSaveStorage" class="btn btn-primary float-left mb-0">{{__('Update')}} </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>



</div>




