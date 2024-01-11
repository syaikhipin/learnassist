@extends('super-admin.app')
@section('content')

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form novalidate="novalidate" method="post" action="{{route('super-admin.save-subscription-plan')}}"
                          id="form-contact" data-form="redirect" data-btn-id="btn-save-form">

                        <div class="mb-3">
                            <label for="name" class="form-label">{{__('Name')}}</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                   value="{{$subscription_plan->name ?? ''}}">
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_free" value="1" role="switch"
                                       id="plan-is-free" {{isset($subscription_plan) && $subscription_plan->is_free ? 'checked' : ''}}>
                                <label class="form-check-label" for="plan-is-free">{{__('Free?')}}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                       role="switch"
                                       id="plan-is-featured" {{isset($subscription_plan) && $subscription_plan->is_featured ? 'checked' : ''}}>
                                <label class="form-check-label" for="plan-is-featured">{{__('Featured?')}}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                       role="switch"
                                       id="plan-is-default" {{isset($subscription_plan) && $subscription_plan->is_default ? 'checked' : ''}}>
                                <label class="form-check-label" for="plan-is-default">{{__('Default?')}}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price-monthly" class="form-label">{{__('Price Monthly')}}</label>
                                    <input type="text" class="form-control" id="price-monthly" name="price_monthly"
                                           value="{{formatCurrency($subscription_plan->price_monthly ?? '',getWorkspaceCurrency($super_settings))}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price-yearly" class="form-label">{{__('Price Yearly')}}</label>
                                    <input type="text" class="form-control" id="price-yearly" name="price_yearly"
                                           value="{{formatCurrency($subscription_plan->price_yearly ?? '',getWorkspaceCurrency($super_settings))}}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="input_file_space_limit">{{__('File Space Limit')}} <small>(mb)</small></label>
                            <input class="form-control" name="file_space_limit" id="input_file_space_limit"
                                   @if(isset($subscription_plan) && $subscription_plan->file_space_limit != 0) value="{{$subscription_plan->file_space_limit}}" @endif>
                            <small>{{__('Leave empty for unlimited. 1000 for 1GB.')}}</small>
                        </div>
                        <div class="mb-3">
                            <label for="input_token_limit">{{__('Text Token Limit')}} <small></small></label>
                            <input class="form-control" name="text_token_limit" id="input_text_token_limit"
                                   @if(isset($subscription_plan) && $subscription_plan->text_token_limit != 0) value="{{$subscription_plan->text_token_limit}}" @endif>
                            <small>{{__('Leave empty for unlimited.')}}</small>
                        </div>
                        <div class="mb-3">
                            <label for="input_token_limit">{{__('Image Token Limit')}} <small></small></label>
                            <input class="form-control" name="image_token_limit" id="input_image_token_limit"
                                   @if(isset($subscription_plan) && $subscription_plan->image_token_limit != 0) value="{{$subscription_plan->image_token_limit}}" @endif>
                            <small>{{__('Leave empty for unlimited.')}}</small>
                        </div>

                        <h5>{{__('Available Gateways')}}</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_paypal_plan_id_monthly">{{__('Paypal Plan ID Monthly')}}</label>
                                    <input class="form-control" name="paypal_plan_id_monthly"
                                           id="input_paypal_plan_id_monthly"
                                           value="{{$subscription_plan->paypal_plan_id_monthly ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_paypal_plan_id_yearly">{{__('Paypal Plan ID Yearly')}}</label>
                                    <input class="form-control" name="paypal_plan_id_yearly"
                                           id="input_paypal_plan_id_yearly"
                                           value="{{$subscription_plan->paypal_plan_id_yearly ?? ''}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_stripe_plan_id_monthly">{{__('Stripe Plan ID Monthly')}}</label>
                                    <input class="form-control" name="stripe_plan_id_monthly"
                                           id="input_stripe_plan_id_monthly"
                                           value="{{$subscription_plan->stripe_plan_id_monthly ?? ''}}">
                                    <small>{{__('Enter the monthly price id')}}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_stripe_plan_id_yearly">{{__('Stripe Plan ID Yearly')}}</label>
                                    <input class="form-control" name="stripe_plan_id_yearly"
                                           id="input_stripe_plan_id_yearly"
                                           value="{{$subscription_plan->stripe_plan_id_yearly ?? ''}}">
                                    <small>{{__('Enter the yearly price id')}}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_paddle_plan_id_monthly">{{__('Paddle Plan ID Monthly')}}</label>
                                    <input class="form-control" name="paddle_plan_id_monthly"
                                           id="input_paddle_plan_id_monthly"
                                           value="{{$subscription_plan->paddle_plan_id_monthly ?? ''}}">
                                    <small>{{__('Enter the monthly price id')}}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="input_paddle_plan_id_yearly">{{__('Paddle Plan ID Yearly')}}</label>
                                    <input class="form-control" name="paddle_plan_id_yearly"
                                           id="input_paddle_plan_id_yearly"
                                           value="{{$subscription_plan->paddle_plan_id_yearly ?? ''}}">
                                </div>
                            </div>
                        </div>

                        <h5>{{__('Modules')}}</h5>

                        @foreach($available_modules as $key => $value)

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="modules[]" value="{{$key}}"
                                           role="switch"
                                           id="module_{{$key}}" {{isset($subscription_plan) && in_array($key, ($subscription_plan->modules ?? [])) ? 'checked' : ''}}>
                                    <label class="form-check-label" for="module_{{$key}}">{{$value}}</label>
                                </div>
                            </div>

                        @endforeach

                        <h5>{{__('Features')}}</h5>

                        <button class="btn btn-primary mb-3" id="add-feature" type="button"><i
                                    class="bi bi-plus-lg"></i></button>

                        <div id="features">
                            @if(!empty($subscription_plan->features))
                                @foreach($subscription_plan->features as $feature)
                                    <div class="row feature-item">
                                        <div class="col-md-9">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" name="features[]"
                                                       value="{{$feature}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <button class="btn btn-primary ms-2 mb-3 remove-feature" type="button"><i
                                                        class="bi bi-dash-lg"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>


                        <input type="hidden" name="uuid" value="{{$subscription_plan->uuid ?? ''}}">

                        <button class="btn btn-primary" id="btn-save-form" type="submit">{{__('Save')}}</button>

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
            window.addEventListener('DOMContentLoaded', () => {

                const add_feature = document.getElementById('add-feature');

                const features = document.getElementById('features');

                add_feature.addEventListener('click', () => {

                    const new_feature = document.createElement('div');
                    new_feature.classList.add('row');
                    new_feature.classList.add('feature-item');
                    new_feature.innerHTML = `
                        <div class="col-md-9">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="features[]">
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <button class="btn btn-primary ms-2 mb-3 remove-feature" type="button"><i class="bi bi-dash-lg"></i></button>
                                </div>
                    `;
                    features.appendChild(new_feature);
                });


                features.addEventListener('click', (e) => {
                    if (e.target.classList.contains('remove-feature') || e.target.closest('.remove-feature')) {
                        e.target.closest('.feature-item').remove();
                    }
                });

            });
        })();
    </script>
@endsection
