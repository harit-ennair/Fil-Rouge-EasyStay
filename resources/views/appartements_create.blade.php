<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyStay - Create Apartment Listing</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                        <p class="text-white/80 mt-2">List your property and start earning</p>
                    </div>
                    <div class="space-y-4 animate__animated animate__fadeInUp">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Reach thousands of potential guests</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Maximize your property's earning potential</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="p-8 md:p-12" x-data="{ currentField: 0 }">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">Create Apartment Listing</h2>
                    <p class="text-gray-600 mt-2">Let's get your property ready for guests</p>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="animate-field" style="animation-delay: 0.1s">
                        <label class="block text-gray-700 font-medium mb-2">Apartment Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Enter apartment name" @focus="currentField = 0">
                    </div>
                    
                    <div class="animate-field" style="animation-delay: 0.2s">
                        <label class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Describe your apartment" @focus="currentField = 1"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="animate-field" style="animation-delay: 0.3s">
                            <label class="block text-gray-700 font-medium mb-2">Price per Night</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">$</span>
                                <input type="number" name="price" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="0.00" @focus="currentField = 2">
                            </div>
                        </div>
                        
                        <div class="animate-field" style="animation-delay: 0.3s">
                            <label class="block text-gray-700 font-medium mb-2">Location</label>
                            <input type="text" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="City, Country" @focus="currentField = 3">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="animate-field" style="animation-delay: 0.4s">
                            <label class="block text-gray-700 font-medium mb-2">Bedrooms</label>
                            <select name="bedrooms" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" @focus="currentField = 4">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="animate-field" style="animation-delay: 0.4s">
                            <label class="block text-gray-700 font-medium mb-2">Bathrooms</label>
                            <select name="bathrooms" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" @focus="currentField = 5">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="animate-field" style="animation-delay: 0.5s">
                        <label class="block text-gray-700 font-medium mb-2">Amenities</label>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="wifi" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">Wi-Fi</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="parking" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">Parking</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="pool" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">Pool</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="ac" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">Air Conditioning</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="kitchen" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">Kitchen</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="amenities[]" value="tv" class="text-teal-500 focus:ring-teal-500">
                                <span class="text-gray-700">TV</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="animate-field" style="animation-delay: 0.6s">
                        <label class="block text-gray-700 font-medium mb-2">Apartment Images</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-500 hover:text-teal-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                        <span>Upload files</span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="animate-field w-full bg-gradient-to-r from-teal-500 to-blue-500 text-white py-3 px-4 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1" style="animation-delay: 0.7s">
                        Create Apartment Listing
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html></svg>}