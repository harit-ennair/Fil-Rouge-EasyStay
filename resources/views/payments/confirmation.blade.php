@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Confirmation</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="text-center">
                        @if($reservation->payment_status === 'paid')
                            <div class="text-green-500 mb-4">
                                <svg class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="text-lg font-medium mt-2">Payment Successful!</h4>
                            </div>
                            <p class="text-gray-600 mb-2">Your payment for reservation #{{ $reservation->id }} has been processed successfully.</p>
                            <p class="text-gray-600">The property owner has confirmed your reservation.</p>
                        @elseif($reservation->payment_status === 'authorized')
                            <div class="text-blue-500 mb-4">
                                <svg class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="text-lg font-medium mt-2">Payment Authorized!</h4>
                            </div>
                            <p class="text-gray-600 mb-2">Your payment method has been authorized for reservation #{{ $reservation->id }}.</p>
                            <p class="text-gray-600 mb-2">Your card will only be charged after the property owner confirms your reservation.</p>
                            <p class="text-gray-600">You'll receive a notification when the owner responds to your reservation request.</p>
                        @else
                            <div class="text-yellow-500 mb-4">
                                <svg class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h4 class="text-lg font-medium mt-2">Payment Processing</h4>
                            </div>
                            <p class="text-gray-600 mb-2">Your payment for reservation #{{ $reservation->id }} is being processed.</p>
                            <p class="text-gray-600">Please check back later for confirmation.</p>
                        @endif

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-6 text-left">
                            <h5 class="text-blue-800 font-medium">Reservation Details</h5>
                            <p class="mt-2"><span class="font-medium">Apartment:</span> {{ $reservation->appartement->title }}</p>
                            <p class="mt-1"><span class="font-medium">Check-in:</span> {{ $reservation->start_date }}</p>
                            <p class="mt-1"><span class="font-medium">Check-out:</span> {{ $reservation->end_date }}</p>
                            <p class="mt-1"><span class="font-medium">Total Price:</span> €{{ number_format($reservation->total_price, 2) }}</p>
                            <p class="mt-1"><span class="font-medium">Payment Status:</span> {{ ucfirst($reservation->payment_status) }}</p>
                            <p class="mt-1"><span class="font-medium">Reservation Status:</span> {{ ucfirst($reservation->status) }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('appartements_index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Return to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection