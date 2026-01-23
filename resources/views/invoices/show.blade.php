<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoice Details') }} : {{ $invoice->invoice_number }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    {{ __('Print A5') }}
                </a>
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="p-8">
                    
                    <!-- Header -->
                    <div class="flex justify-between border-b pb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">INVOICE</h1>
                            <p class="text-gray-500">{{ $invoice->invoice_number }}</p>
                            <p class="text-sm mt-2">
                                <span class="px-2 py-1 rounded {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xl font-bold">{{ $invoice->customer->name }}</h3>
                            <p class="text-gray-500">{{ $invoice->customer->phone }}</p>
                            <p class="mt-4 text-sm text-gray-400">Date: {{ $invoice->date->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="mt-8">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="pb-4">Item Description</th>
                                    <th class="pb-4 text-center">Qty</th>
                                    <th class="pb-4 text-right">Price</th>
                                    <th class="pb-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                    <tr class="border-b">
                                        <td class="py-4">
                                            <div class="font-bold">{{ $item->product_type }}</div>
                                            @if(!empty($item->specifications['note']))
                                                <div class="text-sm text-gray-500">{{ $item->specifications['note'] }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 text-center">{{ $item->quantity }}</td>
                                        <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="py-4 text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="mt-8 flex justify-end">
                        <div class="w-1/2 space-y-2">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($invoice->discount_value > 0)
                            <div class="flex justify-between text-red-500">
                                <span>Discount</span>
                                <span>- Rp {{ number_format($invoice->discount_type == 'nominal' ? $invoice->discount_value : ($invoice->subtotal * $invoice->discount_value / 100), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between font-bold text-xl border-t pt-2 dark:text-white">
                                <span>Total</span>
                                <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Paid (DP)</span>
                                <span>Rp {{ number_format($invoice->dp_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                <span>Remaining</span>
                                <span>Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Add Payment Section (Only if remaining > 0) -->
                @if($invoice->remaining_amount > 0)
                <div class="bg-gray-50 dark:bg-gray-700 px-8 py-6 border-t border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Payment / Pelunasan</h3>
                    
                    @if (session('status') === 'Payment added successfully.')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-4 text-sm text-green-600 dark:text-green-400">
                            {{ __('Payment recorded successfully.') }}
                        </div>
                    @endif

                    <form action="{{ route('invoices.payment', $invoice) }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Amount to Pay</label>
                            <input type="number" name="amount" value="{{ $invoice->remaining_amount }}" max="{{ $invoice->remaining_amount }}" class="mt-1 block w-48 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Method</label>
                            <select name="method" class="mt-1 block w-40 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Confirm payment?')">
                            Pay Now
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
