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
            <!-- Left: Image slider marketing panel (lg+) -->
            <div class="hidden lg:flex relative overflow-hidden">
                <!-- Single image slider -->
                <img data-auth-slider id="auth-slider" src="/leg-stretching-therapy-middleaged-patients_1271419-25743.jpg" alt="physio" class="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-700" data-images="/leg-stretching-therapy-middleaged-patients_1271419-25743.jpg,/medical-assistant-helping-patient-with-physiotherapy-exercises_23-2149071449.jpg,/medical-assistant-helping-patient-with-physiotherapy-exercises_23-2149071451.jpg,/physical-therapist-assisting-patient_820340-68466.jpg,/medical-assistant-helping-patient-with-physiotherapy-exercises_23-2149071506.jpg,/physiotherapist-assisting-patient-with-rehabilitation-exercises_73899-30211.jpg,/two-men-physiptherapist-patient-having-rehab-session-massaging-back-clinic_839833-31884.jpg,/effective-exercise-routines-with-doctor-physiotherapy-centre-patience-persistence-you_483187-8608.jpg">
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-900/70 via-primary-700/60 to-primary-600/40"></div>
                <!-- Copy -->
                <div class="relative z-10 flex items-center justify-center w-full p-10">
                    <div class="max-w-lg text-white">
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
                        <h2 class="text-3xl xl:text-4xl font-bold leading-tight">
                            Mfumo wa Booking wa Amoleck Physiotherapy
                        </h2>
                        <p class="mt-4 text-white/90 leading-relaxed">
                            Panga miadi yako ya physiotherapy kwa urahisi. Jisajili, chagua daktari, na weka ratiba inayokufaa.
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

            <!-- Right: Form area -->
            <div class="w-full flex items-center justify-center px-6 py-10 sm:px-10 bg-white dark:bg-gray-900">
                <div class="w-full max-w-md rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 sm:p-8 bg-white dark:bg-gray-900">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
