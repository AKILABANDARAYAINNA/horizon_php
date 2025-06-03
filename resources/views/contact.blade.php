@extends('layouts.app')

@section('content')
<main class="bg-gray-100">
    <section class="container mx-auto my-16">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-lg mx-auto">
            <h1 class="text-3xl font-bold text-center mb-6">Contact Us</h1>

            @if (session('message'))
                <p class="text-green-500 text-center mb-4">{{ session('message') }}</p>
            @endif

            @if ($errors->any())
                <div class="text-red-500 text-center mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Message:</label>
                    <textarea name="message" rows="4"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>{{ old('message') }}</textarea>
                </div>
                <div class="text-center">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
