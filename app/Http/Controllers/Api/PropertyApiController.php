<?php

// app/Http/Controllers/API/PropertyApiController.php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MongoPropertyService;
use Illuminate\Support\Facades\Auth;

class PropertyApiController extends Controller
{
    protected $propertyService;

    public function __construct(MongoPropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'area' => 'nullable|string',
            'property_type' => 'required|string',
            'for_sale_or_rent' => 'required|string|in:sale,rent',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $user = $request->user(); // Authenticated via token
        $imageFilenames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/properties');
                $imageFilenames[] = basename($path); // Only need the stored filename
            }
        }

        $this->propertyService->createProperty([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'location' => $validated['location'],
            'area' => $validated['area'] ?? null,
            'property_type' => $validated['property_type'],
            'for_sale_or_rent' => $validated['for_sale_or_rent'],
            'is_featured' => $request->input('is_featured') === '1' ? 'yes' : 'no',
            'payment_status' => $request->input('is_featured') === '1' ? 'pending' : 'paid',
            'status' => 'pending',
            'user_id' => (int) $user->id,
            'images' => $imageFilenames,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Property posted successfully'], 201);
    }

    public function index()
    {
        $properties = $this->propertyService->getApprovedProperties();
        return response()->json($properties);
    }

    public function search(Request $request)
    {
        $filters = [
            'location' => $request->input('location'),
            'price' => $request->input('price'),
            'property_type' => $request->input('property_type'),
            'for_sale_or_rent' => $request->input('for_sale_or_rent'),
        ];

        $properties = $this->propertyService->searchProperties($filters);

        return response()->json([
            'properties' => $properties
        ], 200);
    }
}
