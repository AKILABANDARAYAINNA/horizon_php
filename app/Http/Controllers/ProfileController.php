<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\MongoAgentService;

class ProfileController extends Controller
{
    protected $agentService;

    public function __construct(MongoAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function edit()
    {
        $user = Auth::user();
        $agentData = $user->role === 'agent' ? $this->agentService->getAgentByUserId($user->id) : null;

        return view('profile.edit', compact('user', 'agentData'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|confirmed|min:6',
            'contact_number' => $user->role === 'agent' ? 'required|string|max:20' : '',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update Mongo agent contact number
        if ($user->role === 'agent') {
            $this->agentService->updateContactNumber($user->id, $request->contact_number);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
