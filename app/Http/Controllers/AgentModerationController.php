<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MongoAgentService;

class AgentModerationController extends Controller
{
    protected $agentService;

    public function __construct(MongoAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function approve(Request $request)
    {
        $this->agentService->updateVerificationStatus($request->agent_id, 'approved');
        return back()->with('success', 'Agent approved.');
    }

    public function reject(Request $request)
    {
        $this->agentService->updateVerificationStatus($request->agent_id, 'rejected');
        return back()->with('success', 'Agent rejected.');
    }
}
