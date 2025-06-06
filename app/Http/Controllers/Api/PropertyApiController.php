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
            'images.*' => 'nullable|image|max:2048',
        ]);

        $user = $request->user(); // Authenticated via token
        $imageFilenames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/properties'), $filename);
                $imageFilenames[] = $filename;
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
}
