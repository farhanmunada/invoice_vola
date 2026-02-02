<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports') }}
            </h2>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Print Report
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filters -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg print:hidden">
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
                    
                    <!-- Filter Type -->
                    <div>
                        <x-input-label for="filter" :value="__('Filter Type')" />
                        <select id="filter" name="filter" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" onchange="this.form.submit()">
                            <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily (Today)</option>
                            <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly (This Week)</option>
                            <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                        </select>
                    </div>

                    <!-- Monthly Filter Extras -->
                    <div x-data="{ show: '{{ request('filter') }}' === 'monthly' }" x-show="show" style="display: none;">
                        <x-input-label for="month" :value="__('Month')" />
                        <select id="month" name="month" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Year Selector (for Monthly & Yearly) -->
                    <div x-data="{ show: ['monthly', 'yearly'].includes('{{ request('filter') }}') }" x-show="show" style="display: none;">
                        <x-input-label for="year" :value="__('Year')" />
                        <select id="year" name="year" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                            @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Custom Date Range -->
                    <div x-data="{ show: '{{ request('filter') }}' === 'custom' }" x-show="show" style="display: none;" class="flex gap-2">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date')" />
                        </div>
                    </div>

                    <x-primary-button>{{ __('Filter') }}</x-primary-button>
                </form>
            </div>

            <!-- Report Table -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="print:block hidden mb-4">
                    <h1 class="text-2xl font-bold text-center">Sales Report</h1>
                    <p class="text-center text-gray-600">
                        Filter: {{ ucfirst(request('filter', 'All')) }} 
                        @if(request('filter') == 'monthly') - {{ DateTime::createFromFormat('!m', request('month', now()->month))->format('F') }} {{ request('year', now()->year) }} @endif
                        @if(request('filter') == 'yearly') - {{ request('year', now()->year) }} @endif
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Invoice #</th>
                                <th scope="col" class="px-6 py-3">Customer</th>
                                <th scope="col" class="px-6 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $invoice->date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4">{{ $invoice->customer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right font-mono">{{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No paid invoices found for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700">
                                <td colspan="3" class="px-6 py-4 text-right">Total:</td>
                                <td class="px-6 py-4 text-right font-mono">{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
