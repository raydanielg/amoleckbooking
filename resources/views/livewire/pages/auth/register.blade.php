<?php

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth-split')] class extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', Rule::unique(User::class, 'phone')],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Keep raw password before hashing for welcome SMS (requested)
        $rawPassword = $validated['password'];
        // Default role to patient (only if column exists to prevent insert error prior to migration)
        if (Schema::hasColumn('users', 'role')) {
            $validated['role'] = \App\Models\User::ROLE_PATIENT;
        }
        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // Send welcome SMS (name, email, phone, password) as requested
        try {
            /** @var SmsService $sms */
            $sms = app(SmsService::class);
            $name = $user->name;
            $phone = $user->phone;
            $email = $user->email ?: '-';
            $msg = "Karibu, $name! Akaunti yako imeundwa. Jina: $name, Simu: $phone, Barua pepe: $email, Nenosiri: $rawPassword";
            $sms->send($phone, $msg, [
                'language' => 'English', // set to 'Unicode' if you need Unicode SMS
            ]);
        } catch (\Throwable $e) {
            // Optionally log: \Log::warning('SMS send failed: '.$e->getMessage());
        }

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="space-y-2">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create your account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Join us in a few easy steps</p>
    </div>
    <form wire:submit="register" class="space-y-4">
        <!-- Name -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4 space-y-1">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="tel" placeholder="07XXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address (optional) -->
        <div class="mt-4 space-y-1">
            <x-input-label for="email" :value="__('Email (optional)')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 space-y-1">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 space-y-1">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 w-full sm:w-auto justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
