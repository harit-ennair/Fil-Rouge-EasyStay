<!-- Navigation Component for EasyStay -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Primary Navigation -->
            <div class="flex items-center">
                <a href="{{ route('appartements_index') }}" class="flex-shrink-0 flex items-center">
                    <span class="text-xl font-bold bg-gradient-to-r from-teal-500 to-blue-600 bg-clip-text text-transparent">EasyStay</span>
                </a>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('appartements_index') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Home
                    </a>
                    <!-- <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Explore
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        About
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Contact
                    </a> -->
                </div>
            </div>
            
            <!-- Right side menu -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center" x-data="{ profileMenuOpen: false }">
                @auth
                    <!-- User dropdown menu -->
                    <div class="ml-3 relative">
                        <div>
                            <button @click="profileMenuOpen = !profileMenuOpen" class="flex items-center space-x-2 bg-white rounded-full p-1 text-gray-700 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-teal-400 to-blue-500 flex items-center justify-center text-white overflow-hidden">
                                    <span class="text-sm font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm">{{ auth()->user()->name ?? 'Account' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div x-show="profileMenuOpen" 
                             @click.away="profileMenuOpen = false"
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            
                            @if(auth()->user() && auth()->user()->role_id == 1)
                            <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            @endif
                            
                            @if(auth()->user() && auth()->user()->role_id == 2)
                                <a href="{{ route('owner_dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Owner Portal</a>
                            @endif

                            @if(auth()->user() && auth()->user()->role_id == 2)
                                <a href="{{ route('owner_profile', auth()->user()->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            @elseif(auth()->user())
                                <a href="{{ route('client_profile', auth()->user()->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            @endif
                            
                            <!-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Trips</a> -->
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login / Register -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-teal-500 to-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-teal-600 hover:to-blue-600 transition shadow-sm">
                            Sign Up
                        </a>
                        
                    </div>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden" x-data="{ mobileMenuOpen: false }">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <!-- Mobile menu -->
                <div x-show="mobileMenuOpen" class="absolute top-16 inset-x-0 z-50 p-2 transition transform origin-top-right md:hidden" style="display: none;">
                    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
                        <div class="pt-5 pb-6 px-5 space-y-6">
                            <div class="space-y-1">
                                <a href="{{ route('appartements_index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                                    Home
                                </a>
                                <!-- <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                                    Explore
                                </a>
                                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                                    About
                                </a>
                                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:bg-gray-100">
                                    Contact
                                </a> -->
                            </div>
                            
                            @auth
                                <div class="mt-6 border-t border-gray-200 pt-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-teal-400 to-blue-500 flex items-center justify-center text-white overflow-hidden">
                                            <span class="text-sm font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                            <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 space-y-1">
                            
                                        
                                        @if(auth()->user() && auth()->user()->role_id == 1)
                                        <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                        @endif
                                        @if(auth()->user() && auth()->user()->role_id == 2)
                                            <a href="{{ route('owner_dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                                Owner Portal
                                            </a>
                                        @endif
                                        @if(auth()->user() && auth()->user()->role_id == 2)
                                            <a href="{{ route('owner_profile', auth()->user()->id) }}" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                                Profile
                                            </a>
                                        @elseif(auth()->user())
                                            <a href="{{ route('client_profile', auth()->user()->id) }}" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                                Profile
                                            </a>
                                        @endif
                                        
                                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                            Settings
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-900 hover:bg-gray-100">
                                            Your Trips
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:bg-gray-100">
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="mt-6 border-t border-gray-200 pt-4">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-gradient-to-r from-teal-500 to-blue-500 hover:from-teal-600 hover:to-blue-600">
                                        Sign In
                                    </a>
                                    <p class="mt-2 text-center text-base font-medium text-gray-500">
                                        New user?
                                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">
                                            Sign up
                                        </a>
                                    </p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>