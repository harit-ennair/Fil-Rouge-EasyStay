@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center mb-6">
            <a href="{{ route('owner_dashboard') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Owner Profile</h1>
        </div>

        <!-- Owner Profile Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 bg-white">
                <div class="flex items-start">
                    <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600">
                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $owner->name }}</h2>
                        <p class="text-gray-500">{{ $owner->email }}</p>
                        <div class="mt-4 space-y-2">
                            <div class="flex">
                                <span class="text-gray-600 w-32">Joined Date:</span>
                                <span class="font-medium">{{ $owner->created_at->format('F d, Y') }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Properties:</span>
                                <span class="font-medium">{{ $properties->count() }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Total Revenue:</span>
                                <span class="font-medium text-green-600">€{{ number_format($totalRevenue, 2) }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Active Bookings:</span>
                                <span class="font-medium">{{ $activeBookings }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats & Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Stats Cards -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <h4 class="font-medium">Total Bookings</h4>
                            </div>
                            <span class="font-semibold">{{ $totalBookings }}</span>
                        </div>
                        
                        <div class="flex justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <h4 class="font-medium">Active Bookings</h4>
                            </div>
                            <span class="font-semibold">{{ $activeBookings }}</span>
                        </div>
                        
                        <div class="flex justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <h4 class="font-medium">Pending Bookings</h4>
                            </div>
                            <span class="font-semibold">{{ $pendingBookings }}</span>
                        </div>
                        
                        <div class="flex justify-between p-3 bg-purple-50 rounded-lg">
                            <div>
                                <h4 class="font-medium">Completed Bookings</h4>
                            </div>
                            <span class="font-semibold">{{ $completedBookings }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg lg:col-span-2">
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue</h3>
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Properties Section -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Properties ({{ $properties->count() }})</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($properties as $property)
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            <div class="h-48 bg-gray-200 relative">
                                @if($property->photos->isNotEmpty())
                                <div class="h-48 bg-gray-200">
                                    <img src="/storage/{{ $property->photos[0]->photo_path ?? 'default.jpg' }}" 
                                        alt="{{ $property->title }}" class="w-full h-full object-cover">
                                </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                        No Image
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 px-2 py-1 bg-white rounded text-xs font-medium">
                                    {{ $property->status }}
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold text-lg mb-1">{{ $property->title }}</h4>
                                <p class="text-gray-500 text-sm mb-2">{{ Str::limit($property->description, 100) }}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-indigo-600 font-medium">€{{ $property->price }} / night</span>
                                    <a href="{{ route('appartements_show', $property->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-6 text-gray-500">
                            No properties found for this owner.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Bookings</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reservations->take(10) as $reservation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reservation->appartement->title ?? 'Unknown Property' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $reservation->user->name ?? 'Unknown User' }}</div>
                                    <div class="text-xs text-gray-400">{{ $reservation->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }} -
                                    {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        €{{ number_format($reservation->total_price, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reservation->status == 'confirmed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    @elseif($reservation->status == 'pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($reservation->status == 'completed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Completed
                                        </span>
                                    @elseif($reservation->status == 'cancelled')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No bookings found for this owner's properties.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Actions -->
        @if(!auth()->user()->role_id == 1)
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('user_delete', $owner->id) }}" class="block w-full text-center py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none" onclick="return confirm('Are you sure you want to delete this client?')">
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>    
</div>

<!-- JavaScript for Chart -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revenueData = @json($monthlyRevenueJson);
        const parsedData = JSON.parse(revenueData);
        const labels = Object.keys(parsedData);
        const values = Object.values(parsedData);
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Revenue ($)',
                    data: values,
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
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
@endsection