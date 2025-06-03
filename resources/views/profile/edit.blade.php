@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <section class="container mx-auto my-16">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-lg mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Edit Profile</h2>

            @if(session('success'))
                <p class="text-green-500 text-center mb-4">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul class="list-disc px-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf

                <x-input 
                    label="Name" 
                    name="name" 
                    type="text" 
                    :value="old('name', $user->name)" 
                    required 
                />

                <x-input 
                    label="Email" 
                    name="email" 
                    type="email" 
                    :value="old('email', $user->email)" 
                    required 
                />

                <x-input 
                    label="Password (optional)" 
                    name="password" 
                    type="password" 
                />

                <x-input 
                    label="Confirm Password" 
                    name="password_confirmation" 
                    type="password" 
                />

                @if($user->role === 'agent')
                    <x-input 
                        label="Contact Number" 
                        name="contact_number" 
                        type="text" 
                        :value="old('contact_number', $agentData['contact_number'] ?? '')" 
                    />
                @endif

                <div class="text-center">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
