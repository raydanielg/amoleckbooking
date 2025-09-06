<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'System Admin');
        $phone = env('ADMIN_PHONE', '0700000000');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        // If no ADMIN_PASSWORD is provided in .env, use a deterministic default for local/dev
        $password = env('ADMIN_PASSWORD');
        if (empty($password)) {
            $password = 'Admin@12345';
        }

        // Prefer matching by email if provided, otherwise by phone
        $attributes = [];
        if (!empty($email)) {
            $attributes['email'] = $email;
        } elseif (!empty($phone)) {
            $attributes['phone'] = $phone;
        } else {
            $attributes['phone'] = '0700000000';
        }

        $values = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($password),
        ];

        // Set role if column exists
        if (\Schema::hasColumn('users', 'role')) {
            $values['role'] = User::ROLE_ADMIN;
        }

        $user = User::updateOrCreate($attributes, $values);

        $this->command?->info('Admin user seeded:');
        $this->command?->line('  Name:     ' . $user->name);
        $this->command?->line('  Phone:    ' . $user->phone);
        $this->command?->line('  Email:    ' . $user->email);
        $this->command?->line('  Role:     ' . ($user->role ?? '(none)'));
        $this->command?->line('  Password: ' . (env('ADMIN_PASSWORD') ?: 'Admin@12345'));
    }
}
