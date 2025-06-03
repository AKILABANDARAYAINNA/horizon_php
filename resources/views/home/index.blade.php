@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    {{-- Post Your Property CTA --}}
    <section class="container mx-auto my-16 text-center">
        <h2 class="text-3xl font-bold text-gray-700 mb-6">Post Your Property</h2>
        <p class="text-lg text-gray-600 mb-6">
            Have a property to sell or rent? List it on our platform to reach potential buyers and renters.
        </p>
        <a href="{{ auth()->check() && in_array(auth()->user()->role, ['client', 'agent']) 
            ? route('property.create') 
            : route('login') }}"
            class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
            Post a Property
        </a>
    </section>

    {{-- Become an Agent --}}
    <section class="container mx-auto my-16 text-center">
        <h2 class="text-3xl font-bold text-gray-700 mb-6">Become an Agent</h2>
        <p class="text-lg text-gray-600 mb-6">
            Join our platform to showcase your real estate expertise. Register as an agent and start posting properties today!
        </p>
        <a href="{{ route('register.agent') }}" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
            Register as Agent
        </a>
    </section>

    {{-- Livewire Filters --}}
    <section class="container mx-auto my-8">
        @livewire('property-filter')
    </section>

    {{-- Featured Properties --}}
    <section class="container mx-auto my-16">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Featured Properties</h2>

        @if(!empty($featuredAds) && count($featuredAds))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredAds as $property)
                    <div class="bg-white border rounded shadow-md p-4">
                        <img src="{{ asset('uploads/properties/' . ($property['images'][0] ?? 'default.jpg')) }}" 
                             alt="Property Image" 
                             class="w-full h-48 object-cover mb-4">
                        <h3 class="text-xl font-bold">{{ $property->title }}</h3>
                        <p class="text-gray-600">{{ $property->description }}</p>
                        <p><strong>Price:</strong> LKR {{ number_format($property->price) }}</p>
                        <p><strong>Location:</strong> {{ $property->location }}</p>
                        <div class="mt-4 text-center">
                            <a href="{{ route('property.show', ['id' => $property['property_id']]) }}"
                               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                               View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-700">No featured properties available at the moment.</p>
        @endif
    </section>
</main>
@endsection
