<x-guest-layout>
    @include('layouts.header') <!-- ✅ Your custom header -->
    <main class="bg-gray-100">
        <section class="container mx-auto my-16">
            <div class="bg-white shadow-md rounded-lg p-8 max-w-lg mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">Login</h2>

                @if (session('status'))
                    <p class="text-lg text-center text-green-500">{{ session('status') }}</p>
                @endif

                @if ($errors->any())
                    <ul class="mb-4 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Email:</label>
                        <input type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Password:</label>
                        <input type="password" name="password" required autocomplete="current-password"
                               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="block mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" class="rounded">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="text-center">
                        <button type="submit"
                                class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-800">
                            Login
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        @if (Route::has('password.request'))
                            <a class="text-blue-500 hover:underline text-sm" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <div class="text-center mt-2">
                        <p>Don't have an account?
                            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register here</a>.
                        </p>
                    </div>
                </form>
            </div>
        </section>
    </main>
    @include('layouts.footer') <!-- ✅ Your custom footer -->
</x-guest-layout>
