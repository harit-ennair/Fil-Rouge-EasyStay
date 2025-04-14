<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyStay - Find Your Perfect Apartment</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header Banner -->
    <div class="bg-pattern relative">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-500/80 to-blue-600/80"></div>
        <div class="relative container mx-auto px-6 py-16">
            <div class="max-w-4xl animate__animated animate__fadeIn">
                <h1 class="text-4xl md:text-5xl font-bold text-white">Find Your Perfect Stay</h1>
                <p class="text-white/90 text-lg mt-4 max-w-xl">Discover beautiful apartments in your favorite destinations with all the comforts you need</p>
                
                <!-- Search Form -->
                <div class="mt-8 bg-white p-4 rounded-xl shadow-lg animate__animated animate__fadeInUp">
                    <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Location</label>
                            <input type="text" name="location" placeholder="City or destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="w-full md:w-40">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Check-in</label>
                            <input type="date" name="check_in" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="w-full md:w-40">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Check-out</label>
                            <input type="date" name="check_out" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="w-full md:w-auto md:self-end">
                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-teal-500 to-blue-500 text-white py-2 px-6 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all shadow-md">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-12">
        <!-- Filters & Sorting -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 animate__animated animate__fadeIn">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">{{ count($appartements ?? []) }} Apartments Available</h2>
            <div class="flex space-x-4">
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="recommended">Recommended</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                </select>
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filters</span>
                </button>
            </div>
        </div>

        <!-- Apartments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($appartements ?? [] as $index => $appartement)
                <div class="bg-white rounded-xl shadow-md overflow-hidden animate-item" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="relative h-48 bg-gray-200">
                        <!-- <img src="{{ $appartement->image_url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' }}" 
                            alt="{{ $appartement->title ?? 'Apartment' }}" class="w-full h-full object-cover"> -->
                        <div class="absolute top-4 right-4">
                            <button class="p-2 bg-white rounded-full shadow hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <!-- <h3 class="font-bold text-lg text-gray-800">{{ $appartement->title ?? 'Modern Apartment' }}</h3>
                                <p class="text-gray-600 mt-1">{{ $appartement->location ?? 'City Center' }}</p> -->
                            </div>
                            <div class="flex items-center bg-blue-50 px-2 py-1 rounded text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <!-- <span class="text-sm font-medium">{{ $appartement->rating ?? '4.8' }}</span> -->
                            </div>
                        </div>
                        
                        <div class="mt-4 flex flex-wrap gap-2">
                            <!-- <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $appartement->bedrooms ?? '2' }} Bedrooms</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $appartement->bathrooms ?? '1' }} Bathroom</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $appartement->area ?? '75' }} m²</span> -->
                        </div>
                        
                        <div class="mt-6 flex justify-between items-center">
                            <div>
                                <!-- <span class="text-xl font-bold text-gray-800">${{ $appartement->price_per_night ?? '120' }}</span> -->
                                <span class="text-gray-600">/night</span>
                            </div>
                            <!-- <a href="{{ route('appartements.show', $appartement->id ?? 1) }}" class="px-4 py-2 bg-gradient-to-r from-teal-500 to-blue-500 text-white rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all shadow hover:shadow-md"> -->
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-800">No apartments available</h3>
                    <p class="mt-2 text-gray-600 max-w-md mx-auto">We couldn't find any apartments matching your criteria. Try adjusting your filters or check back later.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <div class="inline-flex rounded-md shadow">
                <a href="#" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-l-md hover:bg-gray-50">Previous</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">1</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">2</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-teal-500 text-white hover:bg-teal-600">3</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">4</a>
                <a href="#" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-r-md hover:bg-gray-50">Next</a>
            </div>
        </div>
    </div>
</body>
</html></svg></div>