<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shop Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('status') === 'settings-updated')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="mb-4">
                            <p class="text-sm text-green-600 dark:text-green-400">{{ __('Settings saved successfully.') }}</p>
                        </div>
                    @endif

                    <form method="post" action="{{ route('settings.update') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Shop Name -->
                        <div>
                            <x-input-label for="shop_name" :value="__('Shop Name')" />
                            <x-text-input id="shop_name" name="shop_name" type="text" class="mt-1 block w-full" :value="old('shop_name', $setting->shop_name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('shop_name')" />
                        </div>

                        <!-- Shop Address -->
                        <div>
                            <x-input-label for="shop_address" :value="__('Address')" />
                            <x-text-input id="shop_address" name="shop_address" type="text" class="mt-1 block w-full" :value="old('shop_address', $setting->shop_address)" />
                            <x-input-error class="mt-2" :messages="$errors->get('shop_address')" />
                        </div>

                        <!-- Shop Phone -->
                        <div>
                            <x-input-label for="shop_phone" :value="__('Phone Number')" />
                            <x-text-input id="shop_phone" name="shop_phone" type="text" class="mt-1 block w-full" :value="old('shop_phone', $setting->shop_phone)" />
                            <x-input-error class="mt-2" :messages="$errors->get('shop_phone')" />
                        </div>

                         <!-- Shop Email -->
                         <div>
                            <x-input-label for="shop_email" :value="__('Email (Optional)')" />
                            <x-text-input id="shop_email" name="shop_email" type="email" class="mt-1 block w-full" :value="old('shop_email', $setting->shop_email)" />
                            <x-input-error class="mt-2" :messages="$errors->get('shop_email')" />
                        </div>

                        <!-- Logo -->
                        <div>
                            <x-input-label for="logo" :value="__('Shop Logo (Optional)')" />
                            @if($setting->logo_path)
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-16 w-auto object-contain bg-gray-50 border rounded p-1">
                                </div>
                            @endif
                            <input id="logo" name="logo" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                        </div>

                        <!-- Footer Note -->
                        <div>
                            <x-input-label for="footer_note" :value="__('Invoice Footer Note')" />
                            <textarea id="footer_note" name="footer_note" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('footer_note', $setting->footer_note) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('footer_note')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
