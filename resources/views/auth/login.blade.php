<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-semibold" />
            <x-text-input id="email" class="block mt-1.5 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-3 px-4" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold" />

            <x-text-input id="password" class="block mt-1.5 w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm py-3 px-4"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-indigo-600 transition-colors">{{ __('Keep me logged in') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center mt-8 space-y-4">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30 hover:scale-[1.02]">
                {{ __('LOG IN') }}
            </button>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-500 hover:text-indigo-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
