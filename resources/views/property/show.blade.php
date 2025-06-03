@extends('layouts.app')

@section('content')
<main class="bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">{{ $property['title'] }}</h1>

        @if (!empty($property['images'][0]))
            <img src="{{ asset('uploads/properties/' . $property['images'][0]) }}" 
                 alt="Property Image" class="w-full h-96 object-cover mb-6 rounded">
        @endif

        <div class="space-y-2 text-lg text-gray-700 mb-6">
            <p><strong>Description:</strong> {{ $property['description'] }}</p>
            <p><strong>Price:</strong> LKR {{ number_format($property['price'], 2) }}</p>
            <p><strong>Location:</strong> {{ $property['location'] }}</p>
            <p><strong>Area:</strong> {{ $property['area'] ?? 'N/A' }} sq. ft.</p>
            <p><strong>Type:</strong> {{ ucfirst($property['property_type']) }}</p>
            <p><strong>For:</strong> {{ ucfirst($property['for_sale_or_rent']) }}</p>
        </div>

        {{-- Poster Info --}}
        @php
            $poster = \App\Models\User::find($property['user_id']);
        @endphp

        @if ($poster)
            <div class="bg-gray-100 p-6 rounded">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Contact the Poster</h3>
                <p class="text-gray-700"><strong>Name:</strong> {{ $poster->name }}</p>
                <p class="text-gray-700"><strong>Email:</strong> {{ $poster->email }}</p>
            </div>
        @endif
    </div>
</main>
@endsection
