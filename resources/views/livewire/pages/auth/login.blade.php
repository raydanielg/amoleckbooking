<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth-split')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Default route based on role; redirectIntended still respects intended URL
        $role = auth()->user()->role ?? null;
        if ($role === \App\Models\User::ROLE_ADMIN) {
            $defaultRoute = route('admin.dashboard', absolute: false);
        } elseif ($role === \App\Models\User::ROLE_DOCTOR) {
            $defaultRoute = route('doctor.dashboard', absolute: false);
        } else {
            $defaultRoute = route('patient.dashboard', absolute: false);
        }
        $this->redirectIntended(default: $defaultRoute, navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="space-y-1">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Welcome back</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Sign in to your account</p>
    </div>

    <!-- Social Buttons -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <svg class="w-5 h-5" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="M44.5 20H24v8.5h11.8C34.9 32.9 30.2 36 24 36c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.3 0 6.3 1.2 8.6 3.2l6-6C34.6 3.4 29.6 1.5 24 1.5 11.5 1.5 1.5 11.5 1.5 24S11.5 46.5 24 46.5 46.5 36.5 46.5 24c0-1.3-.1-2.7-.4-4z" fill="#FFC107"/><path d="M6.3 14.7l7 5.1C15 17 18.2 15 22 15c3.3 0 6.2 1.2 8.4 3.1l6-6C33.5 8.6 29 6.5 24 6.5c-7.4 0-13.6 4.2-17 10.4z" fill="#FF3D00"/><path d="M24 46.5c6.1 0 11.3-2 15-5.5l-7-5.7C29.9 37.3 27.2 38 24 38c-6.1 0-11.2-4.1-13-9.6l-7.1 5.5C7.2 41.9 15 46.5 24 46.5z" fill="#4CAF50"/><path d="M44.5 20H24v8.5h11.8c-1.1 3.3-3.6 6-6.8 7.3l7 5.7C40.7 38.5 44.5 31.8 44.5 24c0-1.3-.1-2.7-.4-4z" fill="#1976D2"/></svg>
            <span>Sign in with Google</span>
        </button>
        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.365 1.43c-.956.05-2.144.662-2.807 1.444-.61.72-1.16 1.92-.956 3.046 1.08.083 2.2-.55 2.85-1.33.61-.72 1.106-1.92.913-3.16zM20.16 8.773c-.042-2.14 1.758-3.167 1.838-3.223-1.004-1.466-2.567-1.668-3.113-1.688-1.326-.133-2.587.808-3.258.808-.67 0-1.71-.79-2.81-.767-1.438.025-2.772.836-3.514 2.133-1.49 2.59-.38 6.4 1.07 8.5.71 1.017 1.553 2.153 2.664 2.112 1.07-.042 1.48-.684 2.78-.684 1.3 0 1.66.684 2.81.66 1.16-.018 1.89-1.04 2.6-2.06.82-1.195 1.16-2.356 1.18-2.416-.027-.01-2.27-.87-2.29-3.374z"/></svg>
            <span>Sign in with Apple</span>
        </button>
    </div>

    <!-- Divider -->
    <div class="relative my-2">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200 dark:border-gray-700"></div></div>
        <div class="relative flex justify-center text-xs"><span class="px-2 bg-white dark:bg-gray-900 text-gray-500">or</span></div>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Phone Number -->
        <div class="space-y-1">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input wire:model="form.phone" id="phone" class="block mt-1 w-full" type="tel" name="phone" required autofocus autocomplete="tel" placeholder="07XXXXXXXX" />
            <x-input-error :messages="$errors->get('form.phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 space-y-1">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary-600 shadow-sm focus:ring-primary-500 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-primary-700 dark:hover:text-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 w-full sm:w-auto justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                {{ __('Sign in to your account') }}
            </x-primary-button>
        </div>
    </form>
    <p class="text-sm text-gray-600 dark:text-gray-400">Don't have an account?
        <a href="{{ route('register') }}" class="text-primary-700 hover:underline" wire:navigate>Sign up</a>
    </p>
</div>
