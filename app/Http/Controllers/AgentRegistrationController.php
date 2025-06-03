<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\MongoAgentService;

class AgentRegistrationController extends Controller
{
    protected $agentService;

    public function __construct(MongoAgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function showForm()
    {
        return view('auth.register-agent');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'contact_number' => 'required|string|max:20',
            'business_area' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Step 1: Create SQLite user with role 'agent'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'agent',
            ]);


            // Step 2: Handle profile image upload
            $imageName = null;
            if ($request->hasFile('profile_image')) {
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->move(public_path('uploads/agents'), $imageName);
            }

            // Step 3: Store additional agent info in MongoDB
            $this->agentService->insertAgent([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'business_area' => $request->business_area,
                'profile_image' => $imageName,
                'payment_status' => 'paid',
                'verification_status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registration submitted. Await admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}
