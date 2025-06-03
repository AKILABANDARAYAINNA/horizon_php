<!-- resources/views/layouts/header.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizon Real Estates</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<header class="bg-green-600 text-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Horizon Real Estates</h1>
        <nav class="space-x-4">
            <a href="{{ url('/') }}" class="hover:text-yellow-300">Home</a>
            <a href="{{ url('/sales') }}" class="hover:text-yellow-300">Sales</a>
            <a href="{{ url('/rentals') }}" class="hover:text-yellow-300">Rentals</a>
            <a href="{{ url('/agents') }}" class="hover:text-yellow-300">Agents</a>

            @auth
                @if (Auth::user()->role === 'client')
                    <a href="{{ url('/dashboard/client') }}" class="hover:text-yellow-300">My Profile</a>
                @elseif (Auth::user()->role === 'agent')
                    <a href="{{ url('/agent/dashboard') }}" class="hover:text-yellow-300">My Profile</a>
                @elseif (Auth::user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="hover:text-yellow-300">Admin Portal</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 px-4 py-2 rounded shadow hover:bg-red-700">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-500">Login</a>
            @endauth
        </nav>
    </div>
</header>
