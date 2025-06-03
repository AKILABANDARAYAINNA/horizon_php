<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\MongoPropertyService;
use App\Services\MongoAgentService;

class AgentDashboardController extends Controller
{
    protected $propertyService;
    protected $agentService;

    public function __construct(MongoPropertyService $propertyService, MongoAgentService $agentService)
    {
        $this->propertyService = $propertyService;
        $this->agentService = $agentService;
    }

    public function index()
    {
        $userId = Auth::id();

        $agent = $this->agentService->getAgentByUserId($userId);
        $ads = $this->propertyService->getPropertiesByUserId($userId);

        return view('dashboard.agent', [
            'agent' => $agent,
            'ads' => $ads
        ]);
    }
}
