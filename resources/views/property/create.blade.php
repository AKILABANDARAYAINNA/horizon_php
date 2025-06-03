@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <section class="container mx-auto my-16">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-lg mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Post a New Property</h2>

            @if(session('message'))
                <p class="text-lg text-center {{ str_contains(session('message'), 'successfully') ? 'text-green-500' : 'text-red-500' }}">
                    {{ session('message') }}
                </p>
            @endif

            <form method="POST" action="{{ route('property.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Title:</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Description:</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-4 py-2" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Price (LKR):</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Location:</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded px-4 py-2" required>
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Area (sq. ft.):</label>
                    <input type="text" name="area" value="{{ old('area') }}" class="w-full border border-gray-300 rounded px-4 py-2">
                    @error('area') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Property Type:</label>
                    <select name="property_type" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        <option value="">-- Select Type --</option>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="land">Land</option>
                        <option value="commercial">Commercial</option>
                    </select>
                    @error('property_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">For:</label>
                    <select name="for_sale_or_rent" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        <option value="">-- Select Purpose --</option>
                        <option value="sale">Sale</option>
                        <option value="rent">Rent</option>
                    </select>
                    @error('for_sale_or_rent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Feature on Homepage:</label>
                    <input type="checkbox" name="is_featured" class="rounded">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Images:</label>
                    <input type="file" name="images[]" multiple class="w-full border border-gray-300 rounded px-4 py-2">
                    @error('images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700">
                        Post Property
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
