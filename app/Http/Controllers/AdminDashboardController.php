<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\MongoAgentService;
use App\Services\MongoPropertyService;
use App\Services\MongoMessageService;

class AdminDashboardController extends Controller
{
    protected $agentService;
    protected $propertyService;
    protected $messageService;

    public function __construct(
        MongoAgentService $agentService,
        MongoPropertyService $propertyService,
        MongoMessageService $messageService
    ) {
        $this->agentService = $agentService;
        $this->propertyService = $propertyService;
        $this->messageService = $messageService;
    }

    public function index()
    {
        // Auth check (optional since middleware handles it)
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pendingAgents = $this->agentService->getPendingAgents();
        $allAgents = $this->agentService->getAllAgents();
        $pendingAds = $this->propertyService->getAdsByStatus('pending');
        $approvedAds = $this->propertyService->getAdsByStatus('approved');
        $messages = $this->messageService->getAllMessages();

        return view('dashboard.admin', [
            'pendingAgents' => $pendingAgents,
            'allAgents' => $allAgents,
            'pendingAds' => $pendingAds,
            'approvedAds' => $approvedAds,
            'contactMessages' => $messages,
        ]);
    }
}
