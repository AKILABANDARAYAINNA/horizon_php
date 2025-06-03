<?php

namespace App\Http\Controllers;

use App\Services\MongoPropertyService;

class HomeController extends Controller
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
     * Show the home page with featured properties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $featuredAds = $this->propertyService->getFeaturedApprovedProperties();
        return view('home.index', compact('featuredAds'));
    }
}
