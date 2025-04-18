<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyStay - Owner Dashboard</title>
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
            background-image: url('https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Property Owner Portal</h1>
                <p class="text-gray-500">Manage your properties and maximize your revenue</p>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-2 flex items-center">
                <span class="text-sm text-gray-500">Welcome back,</span>
                <span class="ml-2 font-semibold">{{ $user->name ?? 'Property Owner' }}</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-indigo-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Total Properties</h2>
                            <div class="text-2xl font-bold">{{ $totalProperties }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-red-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Occupancy Rate</h2>
                            <div class="text-2xl font-bold">{{ $occupancyRate }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-emerald-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Monthly Revenue</h2>
                            <div class="text-2xl font-bold">${{ number_format($monthlyRevenue, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-amber-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Active Bookings</h2>
                            <div class="text-2xl font-bold">{{ $activeBookings }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg lg:col-span-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Performance</h3>
                    <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
                        <!-- Chart Placeholder - In a real app, you'd use a chart library -->
                        <div class="text-center">
                            <p class="text-gray-500">Total Revenue: ${{ number_format($totalRevenue, 0) }}</p>
                            <p class="text-xs text-gray-400">Monthly Revenue: ${{ number_format($monthlyRevenue, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Summary -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Property Summary</h3>
                    <div class="space-y-4">
                        @forelse($ownerApartments as $apartment)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <h4 class="font-medium">{{ $apartment->title }}</h4>
                                    <p class="text-sm text-gray-500">{{ $apartment->location }}</p>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">${{ $apartment->price }}/night</span>
                            </div>
                        @empty
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-500 text-center">No properties listed yet</p>
                            </div>
                        @endforelse
                        
                        <a href="{{ route('appartements_create') }}" class="block text-center py-2 px-4 border border-indigo-500 text-indigo-500 rounded-md hover:bg-indigo-500 hover:text-white transition mt-4">
                            {{ $ownerApartments->count() > 0 ? 'Add New Property' : 'List Your First Property' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Bookings</h3>
                    <span class="text-sm text-indigo-600">{{ $activeBookings + $pendingBookings }} total bookings</span>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-purple-600 font-bold">{{ substr($booking->user->name ?? 'Guest', 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'Guest User' }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->user->email ?? 'No email available' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->appartement->title ?? 'Unknown Property' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ $booking->total_price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->status == 'confirmed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    @elseif($booking->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('reservation.confirm', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                                        </form>
                                        <form action="{{ route('reservation.decline', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">Decline</button>
                                        </form>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="text-green-600">Confirmed</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="text-red-600">Declined</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No bookings found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & Calendar -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('appartements_create') }}" class="block p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition text-center">
                            <svg class="h-8 w-8 text-indigo-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="font-medium text-indigo-700">Add Property</span>
                        </a>
                        <a href="#" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition text-center">
                            <svg class="h-8 w-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium text-green-700">Manage Bookings</span>
                        </a>
                        <a href="#" class="block p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition text-center">
                            <svg class="h-8 w-8 text-amber-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-amber-700">View Earnings</span>
                        </a>
                        <a href="#" class="block p-4 bg-red-50 rounded-lg hover:bg-red-100 transition text-center">
                            <svg class="h-8 w-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="font-medium text-red-700">Edit Listings</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Stats</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 font-medium">New Bookings</span>
                                <span class="text-indigo-600 font-bold">
                                    {{ $recentBookings->where('created_at', '>=', now()->startOfMonth())->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    $bookingPercentage = min(100, $recentBookings->where('created_at', '>=', now()->startOfMonth())->count() * 10);
                                @endphp
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $bookingPercentage }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 font-medium">Revenue</span>
                                <span class="text-green-600 font-bold">${{ number_format($monthlyRevenue, 0) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    $targetRevenue = 5000; // Example target
                                    $revenuePercentage = min(100, ($monthlyRevenue / $targetRevenue) * 100);
                                @endphp
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $revenuePercentage }}%"></div>
                            </div>
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 font-medium">Occupancy</span>
                                <span class="text-amber-600 font-bold">{{ $occupancyRate }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-amber-600 h-2.5 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>