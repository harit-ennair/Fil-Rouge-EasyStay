<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties - EasyStay</title>
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include Navigation Component -->
    @include('components.navigation')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">My Properties</h1>
                    <p class="text-gray-500">Manage and view all your listed properties</p>
                </div>
                <a href="{{ route('appartements_create') }}" class="bg-gradient-to-r from-teal-500 to-blue-500 text-white py-2 px-4 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all shadow-md">
                    Add New Property
                </a>
            </div>

            <!-- Message Alerts -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Property Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $index => $property)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden animate-item" style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Property Image -->
                        <div class="h-48 bg-gray-200 relative">
                            <img src="/storage/{{ $property->photos[0]->photo_path ?? 'photos/default.jpg' }}" 
                                alt="{{ $property->title }}" class="w-full h-full object-cover">
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    Active
                                </span>
                            </div>
                        </div>
                        
                        <!-- Property Content -->
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $property->title }}</h3>
                            <p class="text-gray-600 mb-4 truncate">{{ $property->location }}</p>
                            
                            <!-- Categories -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @forelse($property->categories as $category)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                        No categories
                                    </span>
                                @endforelse
                            </div>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <span class="block text-sm font-medium text-gray-500">Price</span>
                                    <span class="block text-xl font-bold text-gray-800">${{ $property->price }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-sm font-medium text-gray-500">Bookings</span>
                                    <span class="block text-xl font-bold text-gray-800">{{ $property->activeReservations }}</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-sm font-medium text-gray-500">Revenue</span>
                                    <span class="block text-xl font-bold text-gray-800">${{ $property->totalRevenue }}</span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-between items-center mt-6">
                                <a href="{{ route('appartements_show', $property->id) }}" class="text-teal-500 font-medium hover:text-teal-600">
                                    View Details
                                </a>
                                <div>
                                    <a href="{{ route('appartements_edit', $property->id,) }}" class="text-blue-500 hover:text-blue-700 mr-4">
                                        Edit
                                    </a>
                                    <a href="{{ route('owner.properties.delete', $property->id) }}" class="text-red-500 hover:text-red-700" 
                                       onclick="return confirm('Are you sure you want to delete this property?');">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 text-center bg-white rounded-xl shadow-md">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-800 mb-2">No properties listed yet</h3>
                        <p class="text-gray-600 mb-6">Start your hosting journey by adding your first property</p>
                        <a href="{{ route('appartements_create') }}" class="inline-block bg-gradient-to-r from-teal-500 to-blue-500 text-white py-2 px-6 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all shadow-md">
                            Add Your First Property
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Tips for Hosts Section -->
            <div class="mt-16 bg-white p-8 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Tips for Property Owners</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">High-Quality Photos</h3>
                        <p class="text-gray-600">Use bright, clear photos that showcase your property's best features. Quality images can increase bookings by up to 40%.</p>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Competitive Pricing</h3>
                        <p class="text-gray-600">Research similar properties in your area to set the right price. Consider adjusting rates for seasonal demand.</p>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Detailed Descriptions</h3>
                        <p class="text-gray-600">Be thorough in your property descriptions. Highlight unique features and nearby attractions to attract more guests.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>