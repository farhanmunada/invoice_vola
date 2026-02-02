<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-800 leading-tight flex items-center">
            <i data-lucide="flask-conical" class="w-6 h-6 mr-2"></i>
            {{ __('Invoice Generator (Simulation Mode)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="invoiceForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <p class="font-bold">Simulation Mode Active</p>
                <p>Invoices created here will <strong>NOT be saved</strong> to the database and will <strong>NOT affect financial reports</strong>.</p>
            </div>

            <form method="post" action="{{ route('dummy.store') }}" target="_blank">
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
                                            <x-input-label value="Specifications (Example: Bahan Flexy 280gr)" />
                                            <input type="text" :name="`items[${index}][specifications][note]`" placeholder="Detail notes..." class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
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
                                <x-input-label for="invoice_number" value="Invoice Number (Customizable)" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full bg-yellow-50" value="{{ $invoiceNumber }}" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="date" value="Date" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label value="Customer Name" />
                                <x-text-input name="customer_name" type="text" class="mt-1 block w-full" placeholder="e.g. John Doe" required />
                            </div>

                             <div class="mb-4">
                                <x-input-label value="Phone (Optional)" />
                                <x-text-input name="customer_phone" type="text" class="mt-1 block w-full" placeholder="e.g. 08123..." />
                            </div>

                             <div class="mb-4">
                                <x-input-label value="Address (Optional)" />
                                <textarea name="customer_address" class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" rows="2"></textarea>
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
                            </div>

                            <div class="mt-6">
                                <x-primary-button class="w-full justify-center text-lg py-3 bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500">
                                    {{ __('Generate Invoice (PDF)') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                }
            }
        }
    </script>
</x-app-layout>
