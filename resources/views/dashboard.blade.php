@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">EasyStay Dashboard</h1>
                <p class="text-gray-500">Manage your properties and bookings</p>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-2">
                <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-indigo-500 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Properties</h2>
                        <div class="text-xl font-bold">{{ $Total_Properties }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-purple-500 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Users</h2>
                        <div class="text-xl font-bold">{{ $Total_Users }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-emerald-500 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Revenue</h2>
                        <div class="text-xl font-bold">${{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Bookings</h2>
                        <div class="text-xl font-bold">{{ $Active_Bookings }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Owners List -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Property Owners</h3>
                    <a href="{{ route('allOwner') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Properties</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topOwners as $owner)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-bold">{{ strtoupper(substr($owner->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $owner->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $owner->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $owner->propertiesCount }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ is_object($owner->created_at) ? $owner->created_at->format('M d, Y') : $owner->created_at }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ${{ number_format($owner->totalRevenue, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('owner_profile', $owner->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-sm text-gray-500 text-center">No property owners found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Client List -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Top Clients</h3>
                    <a href="{{ route('allClients') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spent</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topClients as $client)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-bold">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $client->bookingsCount }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ is_object($client->created_at) ? $client->created_at->format('M d, Y') : $client->created_at }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        ${{ number_format($client->totalSpending, 2) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('client_profile', $client->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-sm text-gray-500 text-center">No clients found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="{{ route('owner.properties') }}" class="block p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition text-center">
                        <svg class="h-6 w-6 text-indigo-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-indigo-700">Manage Properties</span>
                    </a>
                    <a href="{{ route('all_properties') }}" class="block p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition text-center">
                        <svg class="h-6 w-6 text-emerald-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-emerald-700">All Properties</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="block p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition text-center">
                        <svg class="h-6 w-6 text-orange-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="text-orange-700">Categories</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection