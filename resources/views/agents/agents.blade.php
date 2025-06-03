@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <!-- Search Bar -->
    <section class="container mx-auto my-8">
        <form method="GET" action="{{ route('agents') }}" class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-700 mb-4">Search for Agents</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Business Area:</label>
                    <input type="text" name="business_area" value="{{ old('business_area', $businessArea) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2" placeholder="e.g. Colombo">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Minimum Rating:</label>
                    <select name="min_rating" class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Select Minimum Rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $minRating == $i ? 'selected' : '' }}>
                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Search
                </button>
            </div>
        </form>
    </section>

    <!-- Agent Cards -->
    <section class="container mx-auto my-16">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Verified Agents</h2>

        @if(count($agents))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($agents as $agent)
                    <div class="bg-white border rounded shadow-md p-4">
                        <img src="{{ asset('uploads/agents/' . ($agent['profile_image'] ?? 'default_agent.png')) }}"
                             alt="Agent Image" class="w-full h-48 object-cover mb-4 rounded">

                        <h3 class="text-xl font-bold text-gray-700">{{ $agent['name'] }}</h3>
                        <p class="text-gray-600"><strong>Email:</strong> {{ $agent['email'] }}</p>
                        <p class="text-gray-600"><strong>Contact:</strong> {{ $agent['contact_number'] }}</p>
                        <p class="text-gray-600"><strong>Business Area:</strong> {{ $agent['business_area'] }}</p>

                        <div class="mt-4">
                            <p class="text-yellow-500 text-lg">
                                @php $stars = round($agent['average_rating'] ?? 0); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    {!! $i <= $stars ? '★' : '☆' !!}
                                @endfor
                            </p>
                            <p class="text-gray-500 text-sm">
                                Average Rating: {{ $agent['average_rating'] ?? 0 }} ({{ $agent['total_ratings'] ?? 0 }} ratings)
                            </p>
                        </div>

                        {{-- ✅ Rating form for logged-in users only --}}
                        @if(Auth::check())
                            <form method="POST" action="{{ route('agent.rate') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="agent_id" value="{{ $agent['_id'] }}">
                                <label for="rating" class="block text-gray-700 mb-2">Rate this agent:</label>
                                <select name="rating" class="w-full border border-gray-300 rounded px-4 py-2 mb-2" required>
                                    <option value="">Select Rating</option>
                                    @for ($r = 1; $r <= 5; $r++)
                                        <option value="{{ $r }}">{{ $r }} Star{{ $r > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Submit Rating
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-700">No agents match your search criteria.</p>
        @endif
    </section>
</main>
@endsection
