<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Profile | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-900">
    @include('partials.header')

    @php
      $id = request()->route('id');
      $doctors = [
        1 => ['name' => 'Dr. Asha M.', 'title' => 'Physiotherapist', 'photo' => 'https://images.unsplash.com/photo-1550831107-1553da8c8464?q=80&w=1000&auto=format&fit=crop'],
        2 => ['name' => 'Dr. John K.', 'title' => 'Sports Physio', 'photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop'],
        3 => ['name' => 'Dr. Neema P.', 'title' => 'Rehab Specialist', 'photo' => 'https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?q=80&w=1000&auto=format&fit=crop'],
      ];
      $doc = $doctors[$id] ?? ['name' => 'Daktari', 'title' => 'Physiotherapist', 'photo' => 'https://images.unsplash.com/photo-1550831107-1553da8c8464?q=80&w=1000&auto=format&fit=crop'];
    @endphp

    <!-- Hero -->
    <section class="bg-white dark:bg-gray-900">
      <div class="py-12 px-4 mx-auto max-w-7xl lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
          <div class="md:col-span-4">
            <div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow">
              <img src="{{ $doc['photo'] }}" alt="{{ $doc['name'] }}" class="w-full h-full object-cover">
            </div>
          </div>
          <div class="md:col-span-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $doc['name'] }}</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">{{ $doc['title'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
              <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-md bg-primary-700 hover:bg-primary-800 text-white font-medium" wire:navigate>Fanya booking na {{ $doc['name'] }}</a>
              <a href="{{ route('doctors.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-md border border-accent-300 hover:bg-accent-100 text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40 font-medium" wire:navigate>Rudi kwa madaktari</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Profile details (full details) -->
    <section class="px-4 pb-20">
      <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main column -->
        <div class="lg:col-span-2 space-y-6">
          <!-- 1) Basic Information -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
              <div>
                <span class="block text-sm text-gray-500">Full Name</span>
                <p class="font-medium">{{ $doc['name'] ?? 'Daktari' }}</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Gender</span>
                <p class="font-medium">Female</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Age / Experience</span>
                <p class="font-medium">Experience: 7+ years</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Languages</span>
                <p class="font-medium">Kiswahili, English</p>
              </div>
              <div class="sm:col-span-2">
                <span class="block text-sm text-gray-500">Clinic Contact</span>
                <p class="font-medium">info@amoleckphysio.tz • +255 712 000 000</p>
              </div>
            </div>
          </div>

          <!-- 2) Professional Details -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h2 class="text-xl font-semibold mb-4">Professional Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
              <div>
                <span class="block text-sm text-gray-500">Specialty</span>
                <p class="font-medium">Sports Injury, Back Pain, Neuro Rehab</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Current Position</span>
                <p class="font-medium">Senior Physiotherapist</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Education</span>
                <p class="font-medium">BSc Physiotherapy, CPD Certificates</p>
              </div>
              <div>
                <span class="block text-sm text-gray-500">Clinic Branch</span>
                <p class="font-medium">Amoleck Physiotherapy – Kigamboni</p>
              </div>
              <div class="sm:col-span-2">
                <span class="block text-sm text-gray-500">License Number</span>
                <p class="font-medium">TZ-PT-009876 (example)</p>
              </div>
            </div>
          </div>

          <!-- 3) Biography / About -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h2 class="text-xl font-semibold mb-3">Biography</h2>
            <p class="text-gray-700 dark:text-gray-300">Dkt. Ezra ni mtaalamu wa physiotherapy aliye na uzoefu wa miaka 7 katika kutibu majeraha ya michezo na maumivu ya mgongo. Anapenda kutumia mbinu za kisasa kuhakikisha mgonjwa anapona kwa haraka na kwa usalama.</p>
          </div>

          <!-- 4) Skills / Expertise Tags -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h2 class="text-xl font-semibold mb-3">Skills & Expertise</h2>
            <div class="flex flex-wrap gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">✅ Sports Rehabilitation</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">✅ Back Pain Management</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">✅ Post-Surgery Recovery</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">✅ Neurological Physiotherapy</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">✅ Massage Therapy</span>
            </div>
          </div>

          <!-- 6) Achievements / Recognitions -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h2 class="text-xl font-semibold mb-3">Achievements & Recognitions</h2>
            <ul class="list-disc pl-5 text-gray-700 dark:text-gray-300 space-y-1">
              <li>CPD: Sports Physio Workshop (2023)</li>
              <li>Certification: Manual Therapy Level II</li>
              <li>Award: Best Patient Care – 2022</li>
            </ul>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
          <!-- 5) Availability / Schedule -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h3 class="text-lg font-semibold mb-3">Availability & Schedule</h3>
            <ul class="text-gray-700 dark:text-gray-300 space-y-1">
              <li>Mon–Fri: 08:00 – 17:00</li>
              <li>Sat: 09:00 – 14:00</li>
              <li>Sun: Closed</li>
            </ul>
            <div class="mt-4">
              <h4 class="text-sm font-semibold mb-2">Upcoming slots</h4>
              <div class="grid grid-cols-2 gap-2">
                <button class="px-3 py-2 rounded-md border border-accent-300 hover:bg-accent-100 text-sm text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40">Mon 10:00</button>
                <button class="px-3 py-2 rounded-md border border-accent-300 hover:bg-accent-100 text-sm text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40">Tue 11:30</button>
                <button class="px-3 py-2 rounded-md border border-accent-300 hover:bg-accent-100 text-sm text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40">Wed 15:00</button>
                <button class="px-3 py-2 rounded-md border border-accent-300 hover:bg-accent-100 text-sm text-gray-900 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40">Thu 09:30</button>
              </div>
              <p class="mt-2 text-xs text-gray-500">Integration ya booking itaamua slots halisi hapa.</p>
            </div>
          </div>

          <!-- 7) Social Proof -->
          <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <h3 class="text-lg font-semibold mb-3">Patient Testimonials</h3>
            <div class="space-y-4">
              <div class="rounded-lg bg-gray-50 dark:bg-gray-800/50 p-4">
                <div class="flex items-center gap-2 text-accent-600">
                  <span>★★★★★</span>
                </div>
                <p class="mt-1 text-gray-700 dark:text-gray-300">“Dr. Ezra alinirudishia uwezo wa kutembea baada ya ajali.” — Amina, 2024</p>
              </div>
              <div class="rounded-lg bg-gray-50 dark:bg-gray-800/50 p-4">
                <div class="flex items-center gap-2 text-accent-600">
                  <span>★★★★☆</span>
                </div>
                <p class="mt-1 text-gray-700 dark:text-gray-300">“Huduma bora sana na maelezo ya kutosha juu ya matibabu.” — Kelvin, 2023</p>
              </div>
            </div>
            <div class="mt-4">
              <span class="text-sm text-gray-600 dark:text-gray-300">Average rating</span>
              <div class="flex items-center gap-2">
                <span class="text-accent-600 text-lg">★★★★★</span>
                <span class="text-sm text-gray-500">4.8/5</span>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </body>
</html>
