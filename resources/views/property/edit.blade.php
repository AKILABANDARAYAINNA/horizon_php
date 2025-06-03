@extends('layouts.app')

@section('content')
<main class="bg-gray-100 min-h-screen">
    <section class="container mx-auto py-16">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Edit Property</h2>

            @if(session('success'))
                <p class="text-green-600 text-center mb-4">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul class="list-disc px-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('property.update', ['id' => $property['_id']]) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $property['title']) }}"
                        class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" required
                        class="w-full border border-gray-300 rounded px-4 py-2">{{ old('description', $property['description']) }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Price (LKR)</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price', $property['price']) }}"
                        class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $property['location']) }}"
                        class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Area (sq. ft.)</label>
                    <input type="text" name="area" value="{{ old('area', $property['area'] ?? '') }}"
                        class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Property Type</label>
                    <select name="property_type" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        <option value="">-- Select Type --</option>
                        <option value="house" {{ old('property_type', $property['property_type']) === 'house' ? 'selected' : '' }}>House</option>
                        <option value="apartment" {{ old('property_type', $property['property_type']) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="land" {{ old('property_type', $property['property_type']) === 'land' ? 'selected' : '' }}>Land</option>
                        <option value="commercial" {{ old('property_type', $property['property_type']) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">For</label>
                    <select name="for_sale_or_rent" class="w-full border border-gray-300 rounded px-4 py-2" required>
                        <option value="">-- Select Purpose --</option>
                        <option value="sale" {{ old('for_sale_or_rent', $property['for_sale_or_rent']) === 'sale' ? 'selected' : '' }}>Sale</option>
                        <option value="rent" {{ old('for_sale_or_rent', $property['for_sale_or_rent']) === 'rent' ? 'selected' : '' }}>Rent</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Replace Images</label>
                    <input type="file" name="images[]" multiple class="w-full border border-gray-300 rounded px-4 py-2">
                    @if (!empty($property['images']))
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Current Images:</p>
                            <ul class="list-disc pl-5">
                                @foreach($property['images'] as $img)
                                    <li>{{ $img }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-800">
                        Update Property
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
