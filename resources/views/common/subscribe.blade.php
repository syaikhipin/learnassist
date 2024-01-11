@section('head')
    @if(!empty($payment_gateways['stripe']) && !empty($payment_gateways['stripe']->api_key))
        <script src="https://js.stripe.com/v3/"></script>
    @endif
    @if(!empty($payment_gateways['paypal']) && !empty($payment_gateways['paypal']->api_key))
        <script src="https://www.paypal.com/sdk/js?client-id={{$payment_gateways['paypal']->api_key}}&vault=true&intent=subscription"></script>
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                    <h4>{{$subscription_plan->name}}</h4>

                    @if(empty($payment_gateways))
                        <div class="alert alert-warning">
                            {{__('No payment gateway is configured.')}}
                        </div>
                    @endif

                    @if(!empty($payment_gateways['stripe']) && !empty($payment_gateways['stripe']->api_key))
                        <div class="mb-4">
                            <h5>{{__('Pay with Credit or Debit Card')}}</h5>
                            <form action="{{$base_url}}/app/payment-stripe" method="post" id="payment-form">

                                <div class="form-row mb-3">
                                    <label for="card-element">
                                        {{__('Credit or debit card')}}
                                    </label>
                                    <div id="card-element" class="form-control">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>

                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="inputAddress">{{__('Address')}}</label>
                                    <input type="text" id="inputAddress" name="address" class="form-control" value="{{$user->address ?? ''}}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="inputCity">{{__('City')}}</label>
                                    <input type="text" name="city" id="inputCity" class="form-control" value="{{$user->city ?? ''}}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="inputState">{{__('State')}}</label>
                                            <input type="text" name="state" id="inputState" class="form-control" value="{{$user->state ?? ''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="inputZip">{{__('Zip')}}</label>
                                            <input type="text" name="zip" id="inputZip" class="form-control" value="{{$user->zip}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="inputCountry">{{__('Country')}}</label>
                                    <select class="form-select" name="country" required id="inputCountry">
                                        <option value="">{{__('Select Country')}}</option>
                                        @foreach(countries() as $value)
                                            <option value="{{$value['iso_3166_1_alpha2']}}" @if(($user->country ?? null) == $value['iso_3166_1_alpha2']) selected @endif>{{$value['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="plan_id" value="{{$subscription_plan->uuid}}">
                                <input type="hidden" name="term" value="{{$term}}">

                                @csrf

                                <button class="btn btn-primary mt-3"
                                        id="btnStripeSubmit">{{__('Submit Payment')}}</button>

                            </form>
                        </div>
                    @endif

                    @if(!empty($payment_gateways['paypal']) && !empty($payment_gateways['paypal']->api_key))
                        <div class="mb-4">
                            <h5>{{__('Pay with PayPal')}}</h5>
                            <div id="paypal-button-container"></div>
                        </div>
                    @endif


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
                @if(!empty($payment_gateways['stripe']) && !empty($payment_gateways['stripe']->api_key))
                // Dynamic JS for Stripe
                const cardElement = document.getElementById('card-element');

                if(cardElement)
                {
                    const stripe = Stripe('{{$payment_gateways['stripe']->api_key}}');
                    const elements = stripe.elements();
                    const style = {
                        base: {
                            color: '#32325d',
                            lineHeight: '18px',
                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        },
                        invalid: {
                            color: '#fa755a',
                            iconColor: '#fa755a'
                        }
                    };
                    const card = elements.create('card', {style: style});
                    card.mount('#card-element');
                    card.addEventListener('change', function (event) {
                        var displayError = document.getElementById('card-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });
                    const form = document.getElementById('payment-form');
                    const btnStripeSubmit = document.getElementById('btnStripeSubmit');

                    form.addEventListener('submit', function(event) {
                        event.preventDefault();
                        btnStripeSubmit.disabled = true;

                        // Call your server to create a PaymentIntent
                        fetch('/app/stripe-create-payment-intent', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                'plan_id': '{{$subscription_plan->uuid}}',
                                'term': '{{$term}}',
                                '_token': '{{csrf_token()}}',
                                'address': document.getElementById('inputAddress').value,
                                'city': document.getElementById('inputCity').value,
                                'state': document.getElementById('inputState').value,
                                'zip': document.getElementById('inputZip').value,
                                'country': document.getElementById('inputCountry').value,
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                const clientSecret = data.client_secret;

                                // Now confirm the payment with the obtained client_secret
                                stripe.confirmCardPayment(clientSecret, {
                                    payment_method: {
                                        card: card
                                    },
                                    setup_future_usage: 'off_session',
                                }).then(function(confirmResult) {
                                    if (confirmResult.error) {
                                        let errorElement = document.getElementById('card-errors');
                                        errorElement.textContent = confirmResult.error.message;
                                        btnStripeSubmit.disabled = false;
                                    } else {
                                        if (confirmResult.paymentIntent.status === 'succeeded') {
                                            stripePaymentIntentHandler(confirmResult.paymentIntent.id);
                                        }
                                    }
                                });
                            })
                            .catch(error => {
                                console.error("Error creating payment intent:", error);
                            });
                    });


                    function stripePaymentIntentHandler(paymentIntentId) {
                        console.log("PaymentIntent ID:", paymentIntentId);
                        let hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'payment_intent_id');
                        hiddenInput.setAttribute('value', paymentIntentId);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                }

                @endif

                @if(!empty($payment_gateways['paypal']) && !empty($payment_gateways['paypal']->api_key))

                    const paypalButtonContainer = document.getElementById('paypal-button-container');

                    if(paypalButtonContainer)
                    {
                        paypal.Buttons({
                            createSubscription: function (data, actions) {
                                return actions.subscription.create({
                                    'plan_id': @if($term == 'monthly') '{{$subscription_plan->paypal_plan_id_monthly}}'
                                    @else '{{$subscription_plan->paypal_plan_id_yearly}}' @endif
                                });
                            },
                            onApprove: function (data, actions) {
                                window.location = '{{$base_url}}/app/validate-paypal-subscription?subscription_id=' + data.subscriptionID;
                            }
                        }).render('#paypal-button-container'); // Renders the PayPal button
                    }

                @endif

            });
        })();
    </script>
@endsection
