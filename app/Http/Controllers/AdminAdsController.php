<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MongoPropertyService;
use Illuminate\Support\Facades\Log;

class AdminAdsController extends Controller
{
    protected $propertyService;

    public function __construct(MongoPropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Mark a featured ad as paid
     */
    public function markAsPaid(Request $request)
    {
        $propertyId = $request->input('property_id');

        if (!$propertyId) {
            return back()->with('error', 'Missing property ID.');
        }

        // Logging
        Log::info('Mark as paid called', ['property_id' => $propertyId]);

        // Update in MongoDB
        $this->propertyService->updatePaymentStatus($propertyId, 'paid');

        return back()->with('success', 'Marked as paid.');
    }

    /**
     * Approve or reject an ad
     */
    public function updateStatus(Request $request)
    {
        $propertyId = $request->input('property_id');
        $action = $request->input('action');

        Log::info('Ad status update requested', [
            'property_id' => $propertyId,
            'action' => $action,
        ]);

        if (!$propertyId || !in_array($action, ['approve', 'reject'])) {
            return back()->with('error', 'Invalid request data.');
        }

        // Map to consistent status values
        $newStatus = $action === 'approve' ? 'approved' : 'rejected';

        // Update in MongoDB
        $this->propertyService->updateAdStatus($propertyId, $newStatus);

        return back()->with('success', "Property has been {$newStatus}.");
    }
}
