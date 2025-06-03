<div>
    <div class="bg-white shadow-md rounded-lg p-6 max-w-5xl mx-auto mb-10">
        <h3 class="text-2xl font-bold text-gray-700 mb-4">Filter Properties</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Type:</label>
                <select wire:model="property_type" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">All</option>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="land">Land</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">For:</label>
                <select wire:model="for_sale_or_rent" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Both</option>
                    <option value="sale">Sale</option>
                    <option value="rent">Rent</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Location:</label>
                <input wire:model="location" type="text" placeholder="e.g. Colombo"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-1">Max Price (LKR):</label>
                <input wire:model="max_price" type="number" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>
    </div>

    @if(count($properties))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6">
            @foreach($properties as $property)
                <div class="bg-white border rounded shadow-md p-4">
                    @if(isset($property['images'][0]))
                        <img src="{{ asset('uploads/properties/' . $property['images'][0]) }}" 
                             alt="Property Image" class="w-full h-48 object-cover mb-4 rounded">
                    @endif
                    <h3 class="text-xl font-bold">{{ $property['title'] }}</h3>
                    <p class="text-gray-600">{{ $property['property_type'] }}</p>
                    <p><strong>Price:</strong> LKR {{ number_format($property['price']) }}</p>
                    <p><strong>Location:</strong> {{ $property['location'] }}</p>
                    <a href="{{ route('property.show', ['id' => $property['property_id']]) }}"
                       class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        View Details
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-700 mb-10">No properties match your filters.</p>
    @endif
</div>
