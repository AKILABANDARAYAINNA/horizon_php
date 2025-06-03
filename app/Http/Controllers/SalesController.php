<?php

namespace App\Http\Controllers;

use App\Services\MongoPropertyService;
use Illuminate\Http\Request;

class SalesController extends Controller
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
     * Display all properties available for sale.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $properties = $this->propertyService->getPropertiesForSale();
        return view('sales.sales', compact('properties'));
    }
}
