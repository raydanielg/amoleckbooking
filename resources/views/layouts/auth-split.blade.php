<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-50">
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
            <!-- Left: Form area -->
            <div class="w-full flex items-center justify-center px-6 py-10 sm:px-10">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

            <!-- Right: Marketing panel -->
            <div class="hidden lg:flex items-center justify-center bg-primary-700">
                <div class="max-w-lg px-10 text-white">
                    <?php
                        $recentUsers = \App\Models\User::query()
                            ->latest('created_at')
                            ->limit(4)
                            ->get(['name']);
                        $userCount = \App\Models\User::count();
                        function initials($name) {
                            $parts = preg_split('/\s+/', trim($name));
                            $first = isset($parts[0]) ? mb_substr($parts[0], 0, 1) : '';
                            $last = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '';
                            return mb_strtoupper($first.$last);
                        }
                    ?>
                    <h2 class="text-4xl font-bold leading-tight">
                        Mfumo wa Booking wa Amoleck Physiotherapy
                    </h2>
                    <p class="mt-4 text-white/90 leading-relaxed">
                        Ni mfumo wa kidigitali unaosaidia hospitali/kliniki yako kusimamia ratiba na wagonjwa wanaohitaji huduma za physiotherapy.
                        Badala ya mgonjwa kupiga simu au kufika kliniki kuuliza nafasi, anaweza kufanya booking mtandaoni kupitia tovuti — kwa urahisi, haraka, na uwazi.
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <div class="flex -space-x-2">
                            @forelse ($recentUsers as $u)
                                <div class="w-9 h-9 rounded-full border border-white/30 bg-white/20 backdrop-blur-sm flex items-center justify-center text-sm font-semibold">
                                    {{ initials($u->name) }}
                                </div>
                            @empty
                                <div class="w-9 h-9 rounded-full border border-white/30 bg-white/20"></div>
                                <div class="w-9 h-9 rounded-full border border-white/30 bg-white/20"></div>
                                <div class="w-9 h-9 rounded-full border border-white/30 bg-white/20"></div>
                                <div class="w-9 h-9 rounded-full border border-white/30 bg-white/20"></div>
                            @endforelse
                        </div>
                        <span class="text-sm">Wateja waliokwishajisajili: <strong>{{ number_format($userCount) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
