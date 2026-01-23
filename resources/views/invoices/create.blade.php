<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="invoiceForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('invoices.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left: Invoice Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Items Card -->
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Order Items</h3>
                            
                            <template x-for="(item, index) in items" :key="index">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4 relative border border-gray-200 dark:border-gray-600 group">
                                    <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 p-1 bg-white rounded-full shadow-sm text-red-500 hover:text-red-700 hover:bg-red-50 transition z-10" x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <!-- Product Type -->
                                        <div class="md:col-span-4">
                                            <x-input-label value="Product Type" />
                                            <select :name="`items[${index}][product_type]`" x-model="item.product_type" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                                <option value="Cetak">Cetak (Banner, Sticker)</option>
                                                <option value="Apparel">Apparel (Kaos, Topi)</option>
                                                <option value="Custom">Custom / Jasa</option>
                                            </select>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="md:col-span-2">
                                            <x-input-label value="Qty" />
                                            <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                        </div>

                                        <!-- Price -->
                                        <div class="md:col-span-3">
                                            <x-input-label value="Price @" />
                                            <input type="number" :name="`items[${index}][price]`" x-model="item.price" min="0" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                        </div>

                                        <!-- Total -->
                                        <div class="md:col-span-3">
                                            <x-input-label value="Total" />
                                            <div class="mt-2 font-bold text-gray-900 dark:text-gray-100" x-text="formatRupiah(item.quantity * item.price)"></div>
                                        </div>
                                        
                                        <!-- Specifications (Optional JSON) -->
                                        <div class="md:col-span-12">
                                            <x-input-label value="Specifications (Ket: Bahan, Ukuran, Finishing)" />
                                            <input type="text" :name="`items[${index}][specifications][note]`" placeholder="Contoh: Bahan Flexy 280gr, Ukuran 2x1m, Finishing Mata Ayam" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="addItem()" class="mt-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                                + Add Item
                            </button>
                        </div>
                    </div>

                    <!-- Right: Summary & Customer -->
                    <div class="space-y-6">
                        <!-- Customer Info -->
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Customer Info</h3>
                            
                            <div class="mb-4">
                                <x-input-label for="invoice_number" value="Invoice Number" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full bg-gray-100" value="{{ $invoiceNumber }}" readonly />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="date" value="Date" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="customer_id" value="Select Customer" />
                                <div class="flex gap-2">
                                    <select id="customer_id" name="customer_id" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                        <option value="">-- Choose Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                        @endforeach
                                    </select>
                                    <button type="button" @click="showCustomerModal = true" class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="New Customer">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Payment</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-bold" x-text="formatRupiah(calculateSubtotal())"></span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span>Discount</span>
                                    <select name="discount_type" x-model="discountType" class="text-xs border-gray-300 rounded p-1">
                                        <option value="nominal">Rp</option>
                                        <option value="percent">%</option>
                                    </select>
                                    <input type="number" name="discount_value" x-model="discountValue" class="w-20 text-xs border-gray-300 rounded p-1 text-right">
                                </div>

                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span>Grand Total</span>
                                    <span class="text-indigo-600" x-text="formatRupiah(calculateTotal())"></span>
                                </div>

                                <div class="pt-2">
                                    <x-input-label value="Down Payment (DP)" />
                                    <input type="number" name="dp_amount" x-model="dpAmount" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-right" placeholder="0">
                                </div>

                                <div class="flex justify-between text-base font-bold text-red-600 pt-2">
                                    <span>Remaining</span>
                                    <span x-text="formatRupiah(calculateRemaining())"></span>
                                </div>

                                 <div class="pt-2">
                                    <x-input-label value="Payment Method (For DP)" />
                                    <select name="payment_method" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <option value="cash">Cash / Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-primary-button class="w-full justify-center text-lg py-3">
                                    {{ __('Process Invoice') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Customer Modal -->
        <div x-show="showCustomerModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showCustomerModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full ring-1 ring-black ring-opacity-5">
                    
                    <!-- Header -->
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-blue-600 p-2 rounded-lg text-white mr-3">
                                <i data-lucide="user-plus" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 leading-6" id="modal-title">
                                Add New Customer
                            </h3>
                        </div>
                    </div>
                        
                    <!-- Body -->
                    <div class="px-6 py-6 bg-white">
                        <div class="space-y-5">
                            <div>
                                <label for="new_name" class="block text-sm font-semibold text-gray-700 mb-1">Customer Name</label>
                                <input type="text" x-model="newCustomer.name" id="new_name" placeholder="e.g. John Doe" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="new_phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" x-model="newCustomer.phone" id="new_phone" placeholder="e.g. 081234567890" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="new_address" class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                                <textarea x-model="newCustomer.address" id="new_address" rows="2" placeholder="Full address" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"></textarea>
                            </div>
                            <div>
                                <label for="new_notes" class="block text-sm font-semibold text-gray-700 mb-1">Notes (Optional)</label>
                                <textarea x-model="newCustomer.notes" id="new_notes" rows="2" placeholder="Additional notes..." class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="button" @click="saveCustomer()" :disabled="isSavingCustomer" class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all min-w-[100px]">
                            <span x-text="isSavingCustomer ? 'Saving...' : 'Save Customer'"></span>
                        </button>
                        <button type="button" @click="showCustomerModal = false" class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function invoiceForm() {
            return {
                items: [
                    { product_type: 'Cetak', quantity: 1, price: 0, specifications: {} }
                ],
                discountType: 'nominal',
                discountValue: 0,
                dpAmount: 0,
                
                // Customer Modal State
                showCustomerModal: false,
                isSavingCustomer: false,
                newCustomer: {
                    name: '',
                    phone: '',
                    address: '',
                    notes: '' // Added notes
                },
                
                addItem() {
                    this.items.push({ product_type: 'Cetak', quantity: 1, price: 0, specifications: {} });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                calculateSubtotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                },
                calculateTotal() {
                    let subtotal = this.calculateSubtotal();
                    let discount = 0;
                    if (this.discountType === 'nominal') {
                        discount = Number(this.discountValue);
                    } else {
                        discount = subtotal * (Number(this.discountValue) / 100);
                    }
                    return Math.max(0, subtotal - discount);
                },
                calculateRemaining() {
                    let total = this.calculateTotal();
                    let dp = Number(this.dpAmount);
                    return Math.max(0, total - dp);
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },
                
                // Save Customer Logic
                async saveCustomer() {
                    this.isSavingCustomer = true;
                    try {
                        const response = await fetch("{{ route('customers.storeAjax') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newCustomer)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Add new option to select
                            const select = document.getElementById('customer_id');
                            const option = new Option(result.customer.name + ' (' + (result.customer.phone || '-') + ')', result.customer.id, true, true);
                            select.add(option, undefined);
                            
                            // Reset and close
                            this.newCustomer = { name: '', phone: '', address: '', notes: '' };
                            this.showCustomerModal = false;
                        } else {
                            alert('Failed to save customer. Please check inputs.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while saving the customer.');
                    } finally {
                        this.isSavingCustomer = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
