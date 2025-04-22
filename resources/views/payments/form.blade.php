@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment for Reservation #{{ $reservation->id }}</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <h5 class="text-blue-800 font-medium">Reservation Details</h5>
                        <p class="mt-2"><span class="font-medium">Apartment:</span> {{ $reservation->appartement->title }}</p>
                        <p class="mt-1"><span class="font-medium">Check-in:</span> {{ $reservation->start_date }}</p>
                        <p class="mt-1"><span class="font-medium">Check-out:</span> {{ $reservation->end_date }}</p>
                        <p class="mt-1"><span class="font-medium">Total Price:</span> €{{ number_format($reservation->total_price, 2) }}</p>
                    </div>

                    <form id="payment-form">
                        <div id="payment-element" class="mb-6">
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                        <button type="submit" id="submit-button" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Pay Now
                        </button>
                        <div id="payment-message" class="hidden mt-3 text-center"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Your Stripe public key
        const stripe = Stripe('{{ $stripeKey }}');
        let elements;
        let paymentElement;
        let form = document.getElementById('payment-form');
        let submitButton = document.getElementById('submit-button');
        let paymentMessage = document.getElementById('payment-message');

        // Initialize Stripe Elements
        async function initialize() {
            try {
                // Show loading message
                showMessage('Loading payment form...');
                
                // Create a payment intent on the server
                const response = await fetch('{{ route("payments.process", $reservation->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method: 'card'
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log('Payment intent created:', data);
                
                if (!data.clientSecret) {
                    throw new Error('No client secret returned from server');
                }
                
                const clientSecret = data.clientSecret;

                const appearance = {
                    theme: 'stripe',
                    variables: {
                        colorPrimary: '#2563eb', // Tailwind blue-600
                    }
                };

                elements = stripe.elements({
                    appearance,
                    clientSecret
                });

                paymentElement = elements.create("payment");
                paymentElement.mount("#payment-element");

                // Clear loading message
                paymentMessage.classList.add('hidden');
                
                // Enable the submit button once Elements is loaded
                submitButton.disabled = false;
            } catch (error) {
                console.error('Error initializing payment:', error);
                showMessage('Failed to initialize payment form: ' + error.message);
            }
        }

        // Handle form submission
        async function handleSubmit(e) {
            e.preventDefault();
            
            if (!elements) {
                showMessage('Payment form not initialized yet. Please wait or refresh the page.');
                return;
            }
            
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
            showMessage('Processing payment...');

            try {
                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{ route("payments.confirm", $reservation->id) }}'
                    }
                });

                if (error) {
                    console.error('Payment error:', error);
                    showMessage(error.message || 'An error occurred with your payment');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Pay Now';
                }
                // If no error, the page will redirect to the return_url
            } catch (e) {
                console.error('Exception during payment:', e);
                showMessage('Payment failed: ' + e.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Pay Now';
            }
        }

        // Display message to the user
        function showMessage(messageText) {
            paymentMessage.classList.remove('hidden');
            paymentMessage.textContent = messageText;
            paymentMessage.className = 'mt-3 text-center text-gray-600';
            
            // Don't hide error or processing messages automatically
            if (messageText === 'Loading payment form...') {
                // Only auto-hide loading messages
                setTimeout(function () {
                    if (paymentMessage.textContent === messageText) {
                        paymentMessage.classList.add('hidden');
                    }
                }, 10000);
            }
        }

        // Add event listener to the form
        if (form) {
            form.addEventListener('submit', handleSubmit);
            console.log('Submit event listener attached to payment form');
        } else {
            console.error('Payment form element not found in the document');
        }

        // Initialize the payment form
        initialize();
    });
</script>
@endpush