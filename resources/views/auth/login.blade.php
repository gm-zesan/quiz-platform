<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" class="login-form" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <h2 class="sign-in">Log In</h2>
            <p class="welcome">Welcome back</p>
            <x-input-label class="label-auth" for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full input-auth" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label class="label-auth" for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full input-auth"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 input-auth" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-primary-button class="mt-4 login-btn">
            {{ __('Log in') }}
        </x-primary-button>

        <div class="no-account">
            <a href="{{ route('register') }}">Don't have an account? Register</a>
        </div>

    </form>
</x-guest-layout>
