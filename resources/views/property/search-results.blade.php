@extends('layouts.app')

@section('content')
<main class="bg-gray-100 min-h-screen py-10">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-6 text-center">Search Results</h2>

        @if(count($properties))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white p-4 shadow-md rounded">
                        <img src="{{ asset('uploads/properties/' . ($property['images'][0] ?? 'default.jpg')) }}" class="h-40 w-full object-cover rounded mb-2" />
                        <h3 class="text-xl font-bold">{{ $property['title'] }}</h3>
                        <p><strong>Type:</strong> {{ $property['property_type'] }}</p>
                        <p><strong>Price:</strong> LKR {{ number_format($property['price']) }}</p>
                        <p><strong>Location:</strong> {{ $property['location'] }}</p>
                        <a href="{{ route('property.show', ['id' => $property['property_id']]) }}" class="mt-2 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Details</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-700">No matching properties found.</p>
        @endif
    </div>
</main>
@endsection
