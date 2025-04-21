@extends('layouts.app')

@section('content')
<!-- Main Content -->
<div class="container mx-auto px-6 py-8">
    <!-- Apartment Header -->
    <div class="animate__animated animate__fadeIn">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">{{ $appartement->title }}</h1>
        <div class="flex flex-wrap items-center mt-3 text-gray-600">
            <div class="flex items-center mr-6 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $appartement->location }}</span>
            </div>
            <div class="flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    @php
                        $latestReservation = $appartement->reservations()->orderBy('end_date', 'desc')->first();
                    @endphp
                    
                    @if(!$latestReservation || strtotime($latestReservation->end_date) < time())
                        Available now
                    @else
                        Available until {{ date('d M Y', strtotime($latestReservation->end_date)) }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Photo Gallery -->
    <div class="mt-8 animate-item" style="animation-delay: 0.1s">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="h-80 md:h-96 bg-gray-200 rounded-xl overflow-hidden">
                <img src="/storage/{{ $appartement->photos[0]->photo_path ?? 'default.jpg' }}" 
                     alt="{{ $appartement->title }}" 
                     class="w-full h-full object-cover">
            </div>
            <div class="grid grid-cols-2 gap-4">
                @for ($i = 1; $i < min(5, count($appartement->photos ?? [])); $i++)
                    <div class="h-40 md:h-48 bg-gray-200 rounded-xl overflow-hidden">
                        <img src="/storage/{{ $appartement->photos[$i]->photo_path }}" 
                             alt="{{ $appartement->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                @endfor
                @if(empty($appartement->photos) || count($appartement->photos) < 1)
                    @for ($i = count($appartement->photos ?? []); $i < 5; $i++)
                        <div class="h-40 md:h-48 bg-gray-200 rounded-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                 alt="Placeholder" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Left Column: Details -->
        <div class="lg:col-span-2">
            <!-- Host & Booking Info -->
            <div class="bg-white p-6 rounded-xl shadow-md animate-item" style="animation-delay: 0.2s">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Hosted by {{ $appartement->user->name ?? 'EasyStay Host' }}</h2>
                        <p class="text-gray-600 mt-1">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $appartement->user->email ?? 'host@easystay.com' }}
                            </span>
                        </p>
                        <p class="text-gray-600 mt-1">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Member since {{ $appartement->user->created_at ? $appartement->user->created_at->format('M Y') : 'Jan 2023' }}
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-teal-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($appartement->user->name ?? 'EH', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6 bg-white p-6 rounded-xl shadow-md animate-item" style="animation-delay: 0.3s">
                <h2 class="text-xl font-bold text-gray-800">About this place</h2>
                <div class="mt-4 text-gray-700 space-y-4">
                    <p>{{ $appartement->description ?? 'A beautiful and comfortable apartment in a prime location. Perfect for your stay, offering all the amenities you need for a relaxing vacation or business trip.' }}</p>
                    <p>This bright and spacious apartment features modern furnishings, high-quality appliances, and is located in a safe neighborhood with easy access to local attractions, restaurants, and public transportation.</p>
                </div>
            </div>

            <!-- Location -->
            <div class="mt-6 bg-white p-6 rounded-xl shadow-md animate-item" style="animation-delay: 0.5s">
                <h2 class="text-xl font-bold text-gray-800">Location</h2>
                <div class="mt-4 h-60 bg-gray-100 rounded-lg overflow-hidden">
                    <div id="map" class="w-full h-full"></div>
                </div>
                <p class="mt-4 text-gray-700">
                    {{ $appartement->location }}
                </p>
                
                <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" defer></script>

                <script>
                    function initMap() {
                        // Get stored coordinates if available
                        @if($appartement->latitude && $appartement->longitude)
                            const propertyPosition = { 
                                lat: {{ $appartement->latitude }}, 
                                lng: {{ $appartement->longitude }} 
                            };
                            
                            // Create the map centered at the property's position
                            const map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 15,
                                center: propertyPosition,
                            });
                            
                            // Add a marker
                            const marker = new google.maps.Marker({
                                map: map,
                                position: propertyPosition,
                                title: "{{ $appartement->title }}"
                            });
                            
                            // Add info window
                            const infoWindow = new google.maps.InfoWindow({
                                content: "<strong>{{ $appartement->title }}</strong>"
                            });
                            
                            marker.addListener("click", () => {
                                infoWindow.open(map, marker);
                            });
                            
                            // Open info window by default
                            infoWindow.open(map, marker);
                        @else
                            // Default coordinates (fallback)
                            const defaultPosition = { lat: 48.8566, lng: 2.3522 };
                            
                            // Create the map centered at default position
                            const map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 14,
                                center: defaultPosition,
                            });
                            
                            const locationStr = "{{ $appartement->location }}";
                            
                            // Geocode the location
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ address: locationStr }, (results, status) => {
                                if (status === "OK" && results[0]) {
                                    const position = results[0].geometry.location;
                                    
                                    // Center map on the geocoded location
                                    map.setCenter(position);
                                    
                                    // Add a marker
                                    const marker = new google.maps.Marker({
                                        map: map,
                                        position: position,
                                        title: "{{ $appartement->title }}"
                                    });
                                    
                                    // Add info window
                                    const infoWindow = new google.maps.InfoWindow({
                                        content: "<strong>{{ $appartement->title }}</strong>"
                                    });
                                    
                                    marker.addListener("click", () => {
                                        infoWindow.open(map, marker);
                                    });
                                    
                                    // Open info window by default
                                    infoWindow.open(map, marker);
                                }
                            });
                        @endif
                    }
                </script>
            </div>

            <!-- Reservation -->
            <div class="mt-6 bg-white p-6 rounded-xl shadow-md animate-item" style="animation-delay: 0.6s">
                <h2 class="text-xl font-bold text-gray-800">Reservation</h2>
                <div class="mt-4 space-y-6">
                    @php
                        $reservations = App\Models\Reservation::where('appartement_id', $appartement->id)
                            ->with('user')->take(4)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    @if($reservations->count() > 0)
                        @foreach($reservations as $reservation)
                            <div class="border-b border-gray-200 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-400 to-blue-500 flex items-center justify-center text-white font-medium">
                                            {{ substr($reservation->user->name ?? 'U', 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $reservation->user->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $reservation->created_at->format('F Y') }}</p>
                                        <div class="mt-2">
                                            <p class="text-gray-700">
                                                Stayed from {{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }} 
                                                to {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-4 text-center text-gray-600">
                            No one yet
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Booking Widget -->
        <div class="lg:col-span-1">
            <div class="sticky top-8 bg-white p-6 rounded-xl shadow-md animate-item" style="animation-delay: 0.3s">
                <div class="mb-4">
                    <span class="text-2xl font-bold text-gray-800">${{ $appartement->price }}</span>
                    <span class="text-gray-600">/night</span>
                </div>

                <form method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-in</label>
                        <input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Set minimum date to today
                                const today = new Date().toISOString().split('T')[0];
                                document.querySelector('input[name="start_date"]').min = today;
                                
                                // Reset value if a past date was somehow selected
                                document.querySelector('input[name="start_date"]').addEventListener('change', function() {
                                    if(this.value < today) {
                                        this.value = today;
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Check-out</label>
                        <input type="date" name="end_date" min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const checkInInput = document.querySelector('input[name="start_date"]');
                                const checkOutInput = document.querySelector('input[name="end_date"]');
                                
                                // Update check-out min date when check-in changes
                                checkInInput.addEventListener('change', function() {
                                    checkOutInput.min = this.value;
                                    
                                    // Reset check-out value if it's before the new check-in date
                                    if(checkOutInput.value && checkOutInput.value < this.value) {
                                        checkOutInput.value = this.value;
                                    }
                                });
                            });
                        </script>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between font-bold border-gray-200 pt-2 mt-2">
                            <span>Total</span>
                            <span id="total-price">${{ $appartement->price }}</span>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const checkInInput = document.querySelector('input[name="start_date"]');
                                    const checkOutInput = document.querySelector('input[name="end_date"]');
                                    const totalPriceElement = document.getElementById('total-price');
                                    const pricePerNight = {{ $appartement->price }};
                                    
                                    function calculateTotal() {
                                        if (checkInInput.value && checkOutInput.value) {
                                            const checkIn = new Date(checkInInput.value);
                                            const checkOut = new Date(checkOutInput.value);
                                            
                                            if (checkOut > checkIn) {
                                                const nights = Math.floor((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                                                const total = nights * pricePerNight;
                                                totalPriceElement.textContent = '$' + total;
                                                let totalPriceInput = document.querySelector('input[name="total_price"]');
                                                if (totalPriceInput) {
                                                    totalPriceInput.value = total;
                                                }
                                            }
                                        }
                                    }
                                    
                                    checkInInput.addEventListener('change', calculateTotal);
                                    checkOutInput.addEventListener('change', calculateTotal);
                                });
                                </script>
                            <input type="hidden" name="appartement_id" value="{{ $appartement->id }}">
                            <input type="hidden" name="total_price" value="">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-blue-500 text-white py-3 px-6 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all shadow-md">
                        Reserve
                    </button>
                </form>

                <div class="mt-4 text-center text-gray-600 text-sm">
                    You won't be charged yet
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Listings -->
    <div class="mt-16 animate-item" style="animation-delay: 0.7s">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Similar Listings</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($similarAppartements as $similar)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="h-48 bg-gray-200">
                        <img src="/storage/{{ $similar->photos[0]->photo_path ?? 'default.jpg' }}" 
                            alt="{{ $similar->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-gray-800">{{ $similar->title }}</h3>
                        <p class="text-gray-600 mt-1">{{ $similar->location }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <span class="text-xl font-bold text-gray-800">${{ $similar->price }}</span>
                                <span class="text-gray-600">/night</span>
                            </div>
                            <a href="{{ route('appartements_show', $similar->id) }}" class="text-teal-500 font-medium hover:text-teal-600">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p class="text-gray-600">No similar listings found at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-item {
        animation: fadeIn 0.4s ease-out forwards;
        opacity: 0;
    }
    .bg-pattern {
        background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
    }
</style>
@endsection