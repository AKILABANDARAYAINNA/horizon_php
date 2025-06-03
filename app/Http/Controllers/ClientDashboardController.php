<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\MongoPropertyService;

class ClientDashboardController extends Controller
{
    public function index(MongoPropertyService $propertyService)
    {
        $userId = Auth::id(); // This uses the SQLite Jetstream-authenticated user ID

        $ads = $propertyService->getPropertiesByUserId($userId); // From Mongo

        return view('dashboard.client', [
            'properties' => $ads
        ]);
    }
}
