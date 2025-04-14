<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyStay - Login</title>
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
            background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
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
                        <p class="text-white/80 mt-2">Find your perfect home away from home</p>
                    </div>
                    <div class="space-y-4 animate__animated animate__fadeInUp">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Join thousands of happy travelers</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Discover amazing properties worldwide</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="p-8 md:p-12" x-data="{ currentField: 0 }">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-600 mt-2">Sign in to continue your journey with EasyStay</p>
                </div>
                
                <form method="POST" class="space-y-6">
                    @csrf
                    <div class="animate-field" style="animation-delay: 0.1s">
                        <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="you@example.com" @focus="currentField = 0">
                    </div>
                    
                    <div class="animate-field" style="animation-delay: 0.2s">
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Enter your password" @focus="currentField = 1">
                    </div>
                    
                    <div class="animate-field flex items-center justify-between" style="animation-delay: 0.3s">
                        <label class="flex items-center">
                            <input type="checkbox" class="text-teal-500 focus:ring-teal-500 rounded">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-teal-500 hover:underline">Forgot password?</a>
                    </div>
                    
                    <button type="submit" name="submit" class="animate-field w-full bg-gradient-to-r from-teal-500 to-blue-500 text-white py-3 px-4 rounded-lg font-medium hover:from-teal-600 hover:to-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1" style="animation-delay: 0.4s">
                        Sign In
                    </button>
                    
                    <p class="text-center text-gray-600 animate-field" style="animation-delay: 0.5s">
                        Don't have an account? <a href="{{ route('register') }}" class="text-teal-500 hover:underline">Create account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>