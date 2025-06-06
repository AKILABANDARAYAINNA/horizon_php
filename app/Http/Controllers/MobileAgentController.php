<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MongoDB\BSON\UTCDateTime;
use App\Services\MongoAgentService;

class MobileAgentController extends Controller
{
    public function index()
    {
        $agents = (new MongoAgentService)->getApprovedAgents(); // returns approved agents
        return response()->json($agents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255',
            'password'         => 'required|string|min:6|confirmed',
            'contact_number'   => 'required|string',
            'business_area'    => 'required|string',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('uploads/agents', 'public');
        }

        (new MongoAgentService)->insertAgent([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => bcrypt($request->password),
            'contact_number'     => $request->contact_number,
            'business_area'      => $request->business_area,
            'profile_image'      => $imagePath ? asset('storage/' . $imagePath) : null,
            'verification_status'=> 'pending',
            'created_at'         => new UTCDateTime(now()),
        ]);

        return response()->json(['message' => 'Agent registration submitted'], 201);
    }
}
