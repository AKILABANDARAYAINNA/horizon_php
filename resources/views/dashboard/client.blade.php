@extends('layouts.app')

@section('content')
<main class="bg-gray-100 min-h-screen">
    <section class="container mx-auto py-16">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Client Dashboard</h1>

        {{-- Post Property Button --}}
        <div class="text-center mb-12">
            <a href="{{ auth()->check() && in_array(auth()->user()->role, ['client', 'agent']) 
                ? route('property.create') 
                : route('login') }}"
                class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
                Post a Property
            </a>
    </section>

        </div>

        {{-- Profile Section --}}
        <div class="bg-white shadow-md rounded-lg p-8 mb-12 max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-700 mb-6">Your Profile</h2>
            <div>
                <p class="text-lg"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                <p class="text-lg"><strong>Email:</strong> {{ Auth::user()->email }}</p>

                <a href="{{ route('profile.edit') }}"
                   class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
                    Edit Profile
                </a>
            </div>
        </div>

        {{-- Your Ads Section --}}
        <div class="bg-white shadow-md rounded-lg p-8 max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-700 mb-6">Your Ads</h2>
            @if(count($properties))
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Title</th>
                            <th class="border px-4 py-2">Type</th>
                            <th class="border px-4 py-2">Price (LKR)</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($properties as $ad)
                            <tr>
                                <td class="border px-4 py-2">{{ $ad['title'] }}</td>
                                <td class="border px-4 py-2">{{ $ad['property_type'] }}</td>
                                <td class="border px-4 py-2">{{ number_format($ad['price'], 2) }}</td>
                                <td class="border px-4 py-2 capitalize">{{ $ad['status'] }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('property.edit', ['id' => $ad['property_id']]) }}"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                                    </a>
                                    <a href="{{ route('property.delete', ['id' => $ad['_id'] ?? $ad['property_id']]) }}"
                                    onclick="return confirm('Are you sure you want to delete this ad?');"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-gray-700">You have not posted any ads yet.</p>
            @endif
        </div>
    </section>
</main>
@endsection
