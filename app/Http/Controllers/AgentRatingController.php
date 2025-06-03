<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MongoAgentService;

class AgentRatingController extends Controller
{
    protected $agentService;

    public function __construct(MongoAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function rate(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $this->agentService->submitRating($request->agent_id, $request->rating);

        return back()->with('success', 'Rating submitted successfully.');
    }
}
