<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MongoPropertyService;
use Illuminate\Support\Facades\File;
use MongoDB\BSON\ObjectId;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(MongoPropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Show property posting form.
     */
    public function create()
    {
        return view('property.create');
    }

    /**
     * Handle property submission.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'property_type' => 'required|string',
            'for_sale_or_rent' => 'required|string|in:sale,rent',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $user = $request->user(); // Correct for Sanctum token auth

        $imageFilenames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('public/uploads/properties', $fileName);
                    $imageFilenames[] = 'storage/uploads/properties/' . $fileName;
                }
            }
        }


        $propertyId = time();

        $this->propertyService->createProperty([
            'property_id' => $propertyId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'location' => $validated['location'],
            'area' => $validated['area'] ?? null,
            'property_type' => $validated['property_type'],
            'for_sale_or_rent' => $validated['for_sale_or_rent'],
            'is_featured' => $request->has('is_featured') ? 'yes' : 'no',
            'payment_status' => $request->has('is_featured') ? 'pending' : 'paid',
            'status' => 'pending',
            'user_id' => (int) $user->id,
            'images' => $imageFilenames,
            'created_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Property posted successfully',
                'property_id' => $propertyId
            ], 201);
        }
    }

    public function edit($id)
    {
        $property = $this->propertyService->getPropertyFlexible($id);

        if (!$property) {
            return abort(404, 'Property not found.');
        }

        // Only allow the owner or admin
        $user = Auth::user();
        if ($user->role !== 'admin' && $property['user_id'] !== (int)$user->id) {
            abort(403, 'Unauthorized');
        }

        return view('property.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = $this->propertyService->getPropertyFlexible($id);
        if (!$property) {
            return abort(404, 'Property not found.');
        }

        $user = Auth::user();
        if ($user->role !== 'admin' && $property['user_id'] !== (int)$user->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'property_type' => 'required|string',
            'for_sale_or_rent' => 'required|string|in:sale,rent',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $imageFilenames = $property['images'] ?? [];

        if ($request->hasFile('images')) {
        // Delete old files
        foreach ($imageFilenames as $img) {
            $path = public_path($img); // Must delete from `public_path($img)` since you saved as 'storage/uploads/...'
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        // Upload new files
        $imageFilenames = [];
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/uploads/properties', $fileName);
                $imageFilenames[] = 'storage/uploads/properties/' . $fileName;
            }
        }
    

        }

        $this->propertyService->updateProperty($id, [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'location' => $validated['location'],
            'area' => $validated['area'] ?? null,
            'property_type' => $validated['property_type'],
            'for_sale_or_rent' => $validated['for_sale_or_rent'],
            'images' => $imageFilenames,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Property updated successfully.');
    }

    public function show($id)
    {
        $property = $this->propertyService->getPropertyFlexible($id);

        if (!$property) {
            abort(404);
        }

        return view('property.show', compact('property'));
    }
    public function destroy($id)
    {
        $deleted = $this->propertyService->deleteProperty($id);

        if ($deleted->getDeletedCount() === 0) {
            return back()->with('error', 'Property could not be deleted.');
        }

        return redirect()->back()->with('success', 'Property deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = [];

        if ($request->property_type && $request->property_type !== 'all') {
            $query['property_type'] = $request->property_type;
        }

        if ($request->for_sale_or_rent && $request->for_sale_or_rent !== 'both') {
            $query['for_sale_or_rent'] = $request->for_sale_or_rent;
        }

        if ($request->location) {
            $query['location'] = new \MongoDB\BSON\Regex($request->location, 'i');
        }

        if ($request->max_price) {
            $query['price'] = ['$lte' => (float) $request->max_price];
        }

        $matchedProperties = $this->propertyService->searchProperties($query);

        // Fetch featured separately to keep UI same
        $featuredProperties = $this->propertyService->getFeaturedApprovedProperties();

        return view('home', [
            'featuredProperties' => $featuredProperties,
            'searchResults' => $matchedProperties,
        ]);
    }
}
