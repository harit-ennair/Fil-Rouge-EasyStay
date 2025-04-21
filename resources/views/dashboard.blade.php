@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">EasyStay Admin Portal</h1>
                <p class="text-gray-500">System administration and management</p>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-2 flex items-center">
                <span class="text-sm text-gray-500">Welcome,</span>
                <span class="ml-2 font-semibold">{{ auth()->user()->name ?? 'Administrator' }}</span>
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
                            <div class="text-2xl font-bold">{{ $Total_Properties }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-purple-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Total Users</h2>
                            <div class="text-2xl font-bold">{{ $Total_Users }}</div>
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
                            <h2 class="font-semibold text-gray-600">Total Revenue</h2>
                            <div class="text-2xl font-bold">
                                €{{ number_format($totalRevenue, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-red-500 rounded-full p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="font-semibold text-gray-600">Active Bookings</h2>
                            <div class="text-2xl font-bold">{{ $Active_Bookings }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- System Performance Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg lg:col-span-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Booking & Apartment Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Booking Statistics -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-2">Booking Activity</h4>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">Active Bookings</p>
                                    <p class="text-lg font-bold">{{ $Active_Bookings }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Revenue</p>
                                    <p class="text-lg font-bold">€{{ number_format($totalRevenue, 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <canvas id="bookingChart" width="100%" height="120"></canvas>
                            </div>
                        </div>
                        
                        <!-- Property Statistics -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-2">Property Overview</h4>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">Total Properties</p>
                                    <p class="text-lg font-bold">{{ $Total_Properties }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Owners</p>
                                    <p class="text-lg font-bold">{{ $allOwners->count() }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <canvas id="propertyChart" width="100%" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Monthly Revenue Chart -->
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-800 mb-2">Revenue Trends</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <canvas id="revenueChart" width="100%" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Summary -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Platform Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <div>
                                <h4 class="font-medium">Property Owners</h4>
                                <p class="text-sm text-gray-500">{{ $allOwners->count() }} active</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                +{{ $ownerGrowthRate }}% growth
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                            <div>
                                <h4 class="font-medium">Active Guests</h4>
                                <p class="text-sm text-gray-500">{{ $topClients->count() }} users</p>
                            </div>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">Active</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                            <div>
                                <h4 class="font-medium">System Status</h4>
                                <p class="text-sm text-gray-500">All services {{ $systemStatus['status'] }}</p>
                            </div>
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-medium">{{ $systemStatus['uptime'] }} uptime</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Owners List -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Property Owners</h3>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">View All Owners</a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properties</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">profil</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topOwners as $owner)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-bold">{{ strtoupper(substr($owner->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $owner->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $owner->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $owner->propertiesCount }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $owner->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        €{{ number_format($owner->totalRevenue, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('owner_profile', $owner->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View profile</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No property owners found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
<!-- Client List -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Top Clients</h3>
            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">View All Clients</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View profile</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topClients as $client)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $client->bookingsCount }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $client->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                €{{ number_format($client->totalSpending, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('client_profile', $client->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View profile</a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No clients found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
        <!-- Quick Actions & System Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Administrative Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('owner.properties') }}" class="block p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition text-center">
                                <svg class="h-8 w-8 text-indigo-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium text-indigo-700">Manage Properties</span>
                            </a>
                        <a href="{{ route('all_properties') }}" class="block p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition text-center">
                                <svg class="h-8 w-8 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium text-emerald-700">All Properties</span>
                            </a>

                    </div>
                </div>
            </div>
            
            <!-- <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Alerts</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Payment gateway error rate above threshold</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>2.3% of transactions are failing. Check integration status.</p>
                                    </div>
                                    <div class="mt-1">
                                        <div class="-mx-2 flex">
                                            <a href="#" class="px-2 py-1.5 text-sm font-medium text-red-800 hover:text-red-900">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Database server load high</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Server DB-02 showing 87% CPU utilization for last 30 minutes.</p>
                                    </div>
                                    <div class="mt-1">
                                        <div class="-mx-2 flex">
                                            <a href="#" class="px-2 py-1.5 text-sm font-medium text-yellow-800 hover:text-yellow-900">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Booking Chart
        const bookingCtx = document.getElementById('bookingChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $Active_Bookings }}, 
                        {{ $reservations->where('status', 'completed')->count() }}, 
                        {{ $reservations->where('status', 'pending')->count() }}, 
                        {{ $reservations->where('status', 'cancelled')->count() }}
                    ],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // Property Chart
        const propertyCtx = document.getElementById('propertyChart').getContext('2d');
        new Chart(propertyCtx, {
            type: 'pie',
            data: {
                labels: [
                    // Get 5 most common locations
                    @foreach(collect($allApartments)->groupBy('location')->take(5) as $location => $properties)
                    "{{ $location }}",
                    @endforeach
                    "Other"
                ],
                datasets: [{
                    data: [
                        @foreach(collect($allApartments)->groupBy('location')->take(5) as $properties)
                        {{ $properties->count() }},
                        @endforeach
                        {{ collect($allApartments)->groupBy('location')->skip(5)->sum(function($items) { return $items->count(); }) }}
                    ],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        
        // Calculate monthly revenue for the last 6 months
        const months = [];
        const monthlyRevenue = [];
        const monthlyProperties = [];
        
        @for ($i = 5; $i >= 0; $i--)
            months.push('{{ \Carbon\Carbon::now()->subMonths($i)->format("M Y") }}');
            
            // Revenue for this month
            monthlyRevenue.push({{ 
                $reservations->where('created_at', '>=', \Carbon\Carbon::now()->subMonths($i)->startOfMonth())
                ->where('created_at', '<=', \Carbon\Carbon::now()->subMonths($i)->endOfMonth())
                ->sum('total_price') 
            }});
            
            // New properties this month
            monthlyProperties.push({{ 
                collect($allApartments)->where('created_at', '>=', \Carbon\Carbon::now()->subMonths($i)->startOfMonth())
                ->where('created_at', '<=', \Carbon\Carbon::now()->subMonths($i)->endOfMonth())
                ->count()
            }});
        @endfor
        
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Monthly Revenue (€)',
                        data: monthlyRevenue,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'New Properties',
                        data: monthlyProperties,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush