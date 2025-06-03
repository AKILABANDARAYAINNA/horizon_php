<?php

namespace App\Http\Controllers;

use App\Services\MongoPropertyService;
use Illuminate\Http\Request;

class RentalsController extends Controller
{
    protected $propertyService;

    /**
     * Inject the MongoPropertyService into the controller.
     */
    public function __construct(MongoPropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Display all properties available for rent.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $properties = $this->propertyService->getPropertiesForRent();
        return view('rentals.rentals', compact('properties'));
    }
}
