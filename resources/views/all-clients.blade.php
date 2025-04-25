@extends('layouts.app')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Email
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bookings
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Joined Date
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total Spent
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($clients as $client)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                                <span class="text-indigo-600 font-bold">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                                            </div>
                                                            <div class="ml-3">
                                                                <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $client->bookingsCount ?? 0 }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                        {{ is_object($client->created_at) ? $client->created_at->format('M d, Y') : $client->created_at }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            ${{ number_format($client->totalSpent ?? 0, 2) }}
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
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('admin_dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection