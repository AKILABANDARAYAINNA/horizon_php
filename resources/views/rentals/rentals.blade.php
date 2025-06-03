@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <section class="container mx-auto my-16">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Properties for Rent</h2>

        @if(count($properties))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white border rounded shadow-md p-4">
                        <img src="{{ asset('uploads/properties/' . ($property['images'][0] ?? 'default.jpg')) }}" alt="Property Image"
                             alt="Property Image"
                             class="w-full h-48 object-cover mb-4">

                        <h3 class="text-xl font-bold">{{ $property->title }}</h3>
                        <p class="text-gray-600">{{ $property->description }}</p>
                        <p><strong>Price:</strong> LKR {{ number_format($property->price, 2) }}</p>
                        <p><strong>Location:</strong> {{ $property->location }}</p>
                        <p><strong>Type:</strong> {{ $property->property_type }}</p>

                        <div class="mt-4 text-center">
                            <a href="{{ route('property.show', ['id' => $property['property_id']]) }}"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-700">No properties available for rent at the moment.</p>
        @endif
    </section>
</main>
@endsection
