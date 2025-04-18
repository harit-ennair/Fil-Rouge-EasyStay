<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyStay - Edit Apartment Listing</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Google Maps JavaScript API with Places library -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=Function.prototype"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-field {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
        }
        .bg-pattern {
            background-image: url('https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
        /* Style for address predictions dropdown */
        .pac-container {
            border-radius: 0.5rem;
            margin-top: 0.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 9999 !important;
        }
        .pac-item {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .pac-item:hover {
            background-color: #f3f4f6;
        }
        .pac-item-selected {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden animate__animated animate__fadeIn">
            <!-- Left Side - Image -->
            <div class="hidden md:block bg-pattern relative">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-500/70 to-blue-600/70 flex flex-col justify-between p-12">
                    <div class="animate__animated animate__fadeInDown">
                        <h1 class="text-3xl font-bold text-white">EasyStay</h1>
                        <p class="text-white/80 mt-2">Update your property listing</p>
                    </div>
                    <div class="space-y-4 animate__animated animate__fadeInUp">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Keep your listing up to date</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Optimize your listing for more bookings</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="p-8 md:p-12" x-data="{ currentField: 0 }">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Apartment Listing</h2>
                    <p class="text-gray-600 mt-2">Update your property information</p>
                </div>
                
                <form method="POST" action="{{ route('appartements_update', $appartements->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="animate-field" style="animation-delay: 0.1s">
                        <label class="block text-gray-700 font-medium mb-2">Apartment Name</label>
                        <input type="text" name="title" value="{{ $appartements->title }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Enter apartment name" @focus="currentField = 0">
                    </div>
                    
                    <div class="animate-field" style="animation-delay: 0.2s">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Describe your apartment" @focus="currentField = 1">{{ $appartements->description }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="animate-field" style="animation-delay: 0.3s">
                            <label class="block text-gray-700 font-medium mb-2">Price per Night</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">$</span>
                                <input type="number" name="price" value="{{ $appartements->price }}" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="0.00" @focus="currentField = 2">
                            </div>
                        </div>
                        
                        <div class="animate-field" style="animation-delay: 0.3s">
                            <label class="block text-gray-700 font-medium mb-2">Location</label>
                            <input id="location-input" type="text" name="location" value="{{ $appartements->location }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Enter full address" @focus="currentField = 3">
                            <!-- Hidden fields for coordinates -->
                            <input type="hidden" name="latitude" id="latitude" value="{{ $appartements->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $appartements->longitude }}">
                        </div>
                    </div>
                    
                    <div class="flex justify-between gap-4">
                        <a href="{{ route('appartements_show', $appartements->id) }}" class="inline-block py-3 px-4 border border-red-500 text-red-500 rounded-lg font-medium hover:bg-red-500 hover:text-white transition-colors duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="animate-field flex-grow bg-gradient-to-r from-teal-500 to-blue-500 text-white py-3 px-4 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1" style="animation-delay: 0.7s">
                            Update Apartment Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Google Places Autocomplete after the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initAutocomplete();
        });

        function initAutocomplete() {
            // Create the autocomplete object
            const input = document.getElementById('location-input');
            const options = {
                types: ['address'], // Restrict to addresses for accurate geocoding
                componentRestrictions: {}, // No country restriction
                fields: ['address_components', 'geometry', 'formatted_address'] // What fields to return
            };
            
            const autocomplete = new google.maps.places.Autocomplete(input, options);
            
            // When a place is selected, fill in the coordinate fields
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                
                if (!place.geometry) {
                    // User entered the name of a place that was not suggested
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }
                
                // Fill in the latitude and longitude fields
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
                
                // Set the location input to the full formatted address
                input.value = place.formatted_address;
            });
        }
    </script>
</body>
</html>