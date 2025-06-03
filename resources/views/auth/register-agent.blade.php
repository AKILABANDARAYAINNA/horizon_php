@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <section class="container mx-auto my-16">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-lg mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Register as an Agent</h2>

            {{-- Success Message --}}
            @if (session('success'))
                <p class="text-green-500 text-center mb-4">{{ session('success') }}</p>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <p class="text-red-500 text-center mb-4">{{ session('error') }}</p>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-disc text-red-600 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Registration Form --}}
            <form method="POST" action="{{ route('register.agent') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Password:</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Confirm Password:</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Contact Number:</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Business Area:</label>
                    <input type="text" name="business_area" value="{{ old('business_area') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2"
                           placeholder="e.g., Colombo, Gampaha" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Profile Image:</label>
                    <input type="file" name="profile_image"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Payment Status:</label>
                    <p class="text-sm text-gray-600">LKR 500 payment is simulated as "Paid".</p>
                </div>

                <div class="text-center">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
