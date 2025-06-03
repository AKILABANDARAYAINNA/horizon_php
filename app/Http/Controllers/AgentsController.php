<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MongoAgentService;

class AgentsController extends Controller
{
    protected $agentService;

    public function __construct(MongoAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    /**
     * Display a list of verified (approved) agents with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $businessArea = $request->input('business_area');
        $minRating = $request->input('min_rating');

        // Retrieve only approved agents with filters
        $agents = $this->agentService->getFilteredAgents($businessArea, $minRating);


        return view('agents.agents', [
            'agents' => $agents,
            'businessArea' => $businessArea,
            'minRating' => $minRating,
        ]);
    }
}
