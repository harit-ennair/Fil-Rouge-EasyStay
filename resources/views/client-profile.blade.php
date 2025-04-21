@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('admin_dashboard') }}" class="flex items-center text-indigo-600 hover:text-indigo-900">
                        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Client Profile Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                            {{ strtoupper(substr($client->name, 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-800">{{ $client->name }}</h1>
                            <p class="text-gray-500">Member since {{ $client->created_at->format('F Y') }}</p>
                            <p class="text-gray-500">{{ $client->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Client Stats Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow animate-item border-l-4 border-blue-500">
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalBookings }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow animate-item border-l-4 border-green-500">
                        <p class="text-sm font-medium text-gray-500">Confirmed Bookings</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $confirmedBookings }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow animate-item border-l-4 border-yellow-500">
                        <p class="text-sm font-medium text-gray-500">Pending Bookings</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pendingBookings }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow animate-item border-l-4 border-red-500">
                        <p class="text-sm font-medium text-gray-500">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $cancelledBookings }}</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow animate-item border-l-4 border-purple-500">
                        <p class="text-sm font-medium text-gray-500">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-800">€{{ number_format($totalSpent, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Booking History -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Booking History</h2>
                        
                        @if($reservations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($reservations as $reservation)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $reservation->appartement->title ?? 'Unknown Property' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @php
                                                        $checkIn = \Carbon\Carbon::parse($reservation->start_date);
                                                        $checkOut = \Carbon\Carbon::parse($reservation->end_date);
                                                        $nights = $checkOut->diffInDays($checkIn);
                                                    @endphp
                                                    {{ $nights }} {{ Str::plural('night', $nights) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    €{{ number_format($reservation->total_price, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($reservation->status == 'confirmed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Confirmed
                                                        </span>
                                                    @elseif($reservation->status == 'pending')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                    @elseif($reservation->status == 'cancelled')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Cancelled
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ ucfirst($reservation->status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded text-center">
                                <p class="text-gray-500">No booking history found for this client</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Spending Chart -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Spending History</h2>
                        <div class="h-80">
                            <canvas id="spendingChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Client Details -->
            <div class="lg:col-span-1">
                <!-- Client Details -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Client Details</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Full Name</p>
                                <p class="text-lg font-medium text-gray-800">{{ $client->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email Address</p>
                                <p class="text-lg font-medium text-gray-800">{{ $client->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Member Since</p>
                                <p class="text-lg font-medium text-gray-800">{{ $client->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Account Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Travel Preferences -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Travel Insights</h2>
                        
                        <div class="space-y-6">
                            <!-- Visited Locations -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-700 mb-2">Visited Destinations</h3>
                                @if($visitedLocations->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($visitedLocations as $location)
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                                                {{ $location }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">No destinations visited yet</p>
                                @endif
                            </div>
                            
                            <!-- Favorite Locations -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-700 mb-2">Favorite Destinations</h3>
                                @if($favoriteLocations->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($favoriteLocations as $location => $count)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">{{ $location }}</span>
                                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded text-xs">
                                                    {{ $count }} {{ Str::plural('booking', $count) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">No favorite destinations yet</p>
                                @endif
                            </div>
                            
                            <!-- Average Stay Duration -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-700 mb-2">Average Stay Duration</h3>
                                @php
                                    $totalNights = 0;
                                    foreach ($reservations as $reservation) {
                                        $checkIn = \Carbon\Carbon::parse($reservation->start_date);
                                        $checkOut = \Carbon\Carbon::parse($reservation->end_date);
                                        $totalNights += $checkOut->diffInDays($checkIn);
                                    }
                                    $averageStay = $reservations->count() > 0 ? round($totalNights / $reservations->count()) : 0;
                                @endphp
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Average stay</span>
                                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs">
                                        {{ $averageStay }} {{ Str::plural('night', $averageStay) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Actions -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Admin Actions</h2>
                        <div class="space-y-3">
                            <a href="{{ route('user_delete', $client->id) }}" class="block w-full text-center py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none" onclick="return confirm('Are you sure you want to delete this client?')">
                                Delete Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize spending chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('spendingChart').getContext('2d');
        
        const data = {!! $monthlySpendingJson !!};
        const labels = Object.keys(data);
        const values = Object.values(data);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Spending ($)',
                    data: values,
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-field {
        animation: fadeIn 0.4s ease-out forwards;
        opacity: 0;
    }
    .animate-item:nth-child(1) { animation-delay: 0.1s; }
    .animate-item:nth-child(2) { animation-delay: 0.2s; }
    .animate-item:nth-child(3) { animation-delay: 0.3s; }
    .animate-item:nth-child(4) { animation-delay: 0.4s; }
    .animate-item:nth-child(5) { animation-delay: 0.5s; }
</style>
@endsection