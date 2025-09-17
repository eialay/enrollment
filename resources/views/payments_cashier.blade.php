<x-sidebar>
    <x-slot:title>
        Cashier Payment
    </x-slot>

    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-gray-900">Pay at Cashier Instructions</h2>
        <ul class="list-disc pl-5 mb-6 text-gray-700">
            <li>Proceed to the cashier window at your campus.</li>
            <li>Present your payment reference code and required documents.</li>
            <li>Wait for your queue number to be called for payment processing.</li>
            <li>Keep your receipt for future reference.</li>
        </ul>

        @if(isset($queueNumber))
            <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <strong>Your Queue Number:</strong> {{ $queueNumber }}
            </div>
        @else
            <form method="POST" action="{{ route('payments.queue') }}">
                @csrf
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Get a Queue Number</button>
            </form>
        @endif

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2 text-gray-900">Current Queue List</h3>
            <ul class="list-decimal pl-5">
                @forelse($queueList as $item)
                    <li>
                        <span class="font-bold">{{ $item->queue_number }}</span>
                    </li>
                @empty
                    <li>No queue items yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-sidebar>
