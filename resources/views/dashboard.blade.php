<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Create Invoice
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Revenue Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Paid Invoice Revenue (Omzet)</h3>
                    <div class="flex space-x-2">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'daily']) }}" class="px-3 py-1 text-xs font-semibold rounded-md {{ $filter == 'daily' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Daily (30 Days)</a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'monthly']) }}" class="px-3 py-1 text-xs font-semibold rounded-md {{ $filter == 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Monthly (This Year)</a>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Cards Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Income Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-green-500 relative group">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Today's Income</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
                    <div class="text-xs text-green-600 mt-1 font-medium bg-green-50 inline-block px-2 py-1 rounded-md">Real-time payments received</div>
                </div>

                <!-- Card 2: Monthly Income -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Monthly Revenue</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                    <div class="text-xs text-blue-600 mt-1 font-medium bg-blue-50 inline-block px-2 py-1 rounded-md">Total revenue this month</div>
                </div>

                <!-- Card 3: Unpaid (Piutang) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-red-500">
                    <div class="text-gray-500 text-sm uppercase font-semibold">Unpaid Invoices</div>
                    <div class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</div>
                    <div class="text-xs text-red-600 mt-1 font-medium bg-red-50 inline-block px-2 py-1 rounded-md">{{ $pendingCount + $partialCount }} invoices waiting payment</div>
                </div>
            </div>

            <!-- Recent Invoices & Production Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
                        <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            View All <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-100">
                            @forelse($recentInvoices as $invoice)
                                <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition p-2 rounded-lg -mx-2">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $invoice->customer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $invoice->invoice_number }} &bull; {{ $invoice->date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-gray-800">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                                        <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-full {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-center py-8 flex flex-col items-center">
                                    <div class="bg-gray-100 p-3 rounded-full mb-3">
                                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <p>No recent transactions.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Production / Status Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Invoice Status Overview</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Pending -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Pending (Not Fully Paid)</span>
                                    <span class="font-bold text-gray-800">{{ $pendingCount }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-yellow-400 h-3 rounded-full transition-all duration-500" style="width: {{ ($pendingCount + $partialCount + $paidCount) > 0 ? ($pendingCount / ($pendingCount + $partialCount + $paidCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Partially Paid -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Partially Paid (DP)</span>
                                    <span class="font-bold text-gray-800">{{ $partialCount }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-blue-500 h-3 rounded-full transition-all duration-500" style="width: {{ ($pendingCount + $partialCount + $paidCount) > 0 ? ($partialCount / ($pendingCount + $partialCount + $paidCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>

                            <!-- Paid (Done) -->
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Paid (Completed)</span>
                                    <span class="font-bold text-gray-800">{{ $paidCount }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: {{ ($pendingCount + $partialCount + $paidCount) > 0 ? ($paidCount / ($pendingCount + $partialCount + $paidCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <h4 class="font-semibold text-blue-800 text-sm mb-2 flex items-center">
                                <i data-lucide="lightbulb" class="w-4 h-4 mr-2"></i> Quick Tip
                            </h4>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Use the <strong>"Create Invoice"</strong> button at the top to start a new transaction. Remember to input DP amount if the customer pays partially.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Gradient fill
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); // Blue 500 equivalent
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Paid (Rp)',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#2563eb', // Blue 600
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4, // Smooth curve
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumSignificantDigits: 3 }).format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
