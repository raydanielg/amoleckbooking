<?php

use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Services\SmsService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth-split')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        // Also send a short code via SMS alongside the email reset link
        try {
            $user = User::where('email', $this->email)->first();
            if ($user && !empty($user->phone)) {
                $code = (string) random_int(100000, 999999);
                $msg = "Reset code yako ni: $code. Pia tumekutumia email yenye link ya kubadili nenosiri.";
                /** @var SmsService $sms */
                $sms = app(SmsService::class);
                $sms->send($user->phone, $msg, ['language' => 'English']);
            }
        } catch (\Throwable $e) {
            // Silently ignore SMS failures to not block UX
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-6">
    <div class="space-y-1">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Forgot your password?</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Enter your email and we will send you a reset link</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-primary-700 dark:hover:text-primary-300" wire:navigate>Back to sign in</a>
            <x-primary-button class="bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    <p class="text-sm text-gray-600 dark:text-gray-400">Don't have an account?
        <a href="{{ route('register') }}" class="text-primary-700 hover:underline" wire:navigate>Sign up</a>
    </p>
</div>
