@extends('layouts.app')

@section('content')
<main class="bg-gray-100 min-h-screen">
    <section class="container mx-auto py-16">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Admin Dashboard</h1>

        {{-- Pending Agents --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Pending Agents</h2>
            @if(count($pendingAgents))
                <table class="table-auto w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Contact</th>
                            <th class="border px-4 py-2">Payment Status</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingAgents as $agent)
                            <tr>
                                <td class="border px-4 py-2">{{ $agent['name'] }}</td>
                                <td class="border px-4 py-2">{{ $agent['email'] }}</td>
                                <td class="border px-4 py-2">{{ $agent['contact_number'] }}</td>
                                <td class="border px-4 py-2 capitalize">{{ $agent['payment_status'] }}</td>
                                <td class="border px-4 py-2">
                                    @if($agent['payment_status'] === 'pending')
                                        <button disabled class="bg-gray-400 text-white px-4 py-2 rounded">Pending Payment</button>
                                    @else
                                        <form method="POST" action="{{ route('admin.agent.approve') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="agent_id" value="{{ $agent['_id'] }}">
                                            <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Approve</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.agent.reject') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="agent_id" value="{{ $agent['_id'] }}">
                                        <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-700">No pending agents.</p>
            @endif
        </div>

        {{-- Pending Ads --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Pending Ads</h2>
            @if(count($pendingAds))
                <table class="table-auto w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Title</th>
                            <th class="border px-4 py-2">Type</th>
                            <th class="border px-4 py-2">Price</th>
                            <th class="border px-4 py-2">Featured</th>
                            <th class="border px-4 py-2">Payment</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingAds as $ad)
                            <tr>
                                <td class="border px-4 py-2">{{ $ad['title'] }}</td>
                                <td class="border px-4 py-2">{{ $ad['property_type'] }}</td>
                                <td class="border px-4 py-2">LKR {{ number_format($ad['price'], 2) }}</td>
                                <td class="border px-4 py-2">{{ $ad['is_featured'] === 'yes' ? 'Yes' : 'No' }}</td>
                                <td class="border px-4 py-2">{{ $ad['payment_status'] }}</td>
                                <td class="border px-4 py-2">
                                    @if($ad['is_featured'] === 'yes' && $ad['payment_status'] === 'pending')
                                        <form method="POST" action="{{ route('admin.ads.markPaid') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="property_id" value="{{ $ad['property_id'] }}">
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Mark as Paid</button>
                                        </form>
                                    @endif

                                    @if($ad['is_featured'] === 'no' || $ad['payment_status'] === 'paid')
                                        <form method="POST" action="{{ route('admin.ads.updateStatus') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="property_id" value="{{ $ad['property_id'] }}">
                                            <button name="action" value="approve" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Approve</button>
                                            <button name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-700">No pending ads.</p>
            @endif
        </div>

        {{-- Approved Ads --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Approved Ads</h2>
            @if(count($approvedAds))
                <table class="table-auto w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Title</th>
                            <th class="border px-4 py-2">Type</th>
                            <th class="border px-4 py-2">Price</th>
                            <th class="border px-4 py-2">For</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedAds as $ad)
                            <tr>
                                <td class="border px-4 py-2">{{ $ad['title'] }}</td>
                                <td class="border px-4 py-2">{{ $ad['property_type'] ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">LKR {{ number_format($ad['price'], 2) }}</td>
                                <td class="border px-4 py-2">{{ $ad['for_sale_or_rent'] }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('property.edit', ['id' => $ad['property_id']]) }}"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                                    <a href="{{ route('property.delete', ['id' => $ad['_id'] ?? $ad['property_id']]) }}"
                                    onclick="return confirm('Are you sure you want to delete this ad?');"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-700">No approved ads.</p>
            @endif
        </div>

        {{-- Contact Messages --}}
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Contact Messages</h2>
            @if(count($contactMessages))
                <table class="table-auto w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contactMessages as $msg)
                            <tr>
                                <td class="border px-4 py-2">{{ $msg['name'] }}</td>
                                <td class="border px-4 py-2">{{ $msg['email'] }}</td>
                                <td class="border px-4 py-2">{{ $msg['message'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-700">No messages yet.</p>
            @endif
        </div>
    </section>
</main>
@endsection
