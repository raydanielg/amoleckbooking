<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Testimonials | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="antialiased font-sans text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-900">
    @include('partials.header')

    <!-- Submit Feedback (auth only) -->
    <section class="bg-white dark:bg-gray-900">
      <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-12 lg:px-6">
        @auth
          <div class="max-w-3xl mx-auto">
            @if (session('status'))
              <div class="mb-4 p-3 rounded-md bg-primary-50 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
              <div class="mb-4 p-3 rounded-md bg-red-50 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                <ul class="list-disc pl-5">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('testimonials.store') }}" class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm">
              @csrf
              <h2 class="text-xl font-semibold mb-4">Andika Feedback yako</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                  <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kichwa (optional)</label>
                  <input id="title" name="title" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Mfano: Huduma bora sana" />
                </div>
                <div class="sm:col-span-2">
                  <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ujumbe</label>
                  <textarea id="body" name="body" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Elezea uzoefu wako..."></textarea>
                </div>
                <div>
                  <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rating</label>
                  <select id="rating" name="rating" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                  </select>
                </div>
              </div>
              <div class="mt-4">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-md bg-primary-700 hover:bg-primary-800 text-white font-medium">Tuma Feedback</button>
              </div>
            </form>
          </div>
        @else
          <div class="max-w-3xl mx-auto text-center">
            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
              <p class="text-gray-700 dark:text-gray-300">Ili uandike feedback, tafadhali <a href="{{ route('login') }}" class="text-primary-700 hover:underline" wire:navigate>ingia</a> au <a href="{{ route('register') }}" class="text-primary-700 hover:underline" wire:navigate>jisajili</a>.</p>
            </div>
          </div>
        @endauth
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-white dark:bg-gray-900">
      <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm">
          <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Testimonials</h2>
          <p class="mb-8 font-light text-gray-500 lg:mb-16 sm:text-xl dark:text-gray-400">Soma maoni ya wagonjwa waliopata huduma bora za physiotherapy kutoka Amoleck Physio clinic.</p>
        </div>
        <div class="grid mb-8 lg:mb-12 lg:grid-cols-2">
          <figure class="flex flex-col justify-center items-center p-8 text-center bg-gray-50 border-b border-gray-200 md:p-12 lg:border-r dark:bg-gray-800 dark:border-gray-700">
            <blockquote class="mx-auto mb-8 max-w-2xl text-gray-500 dark:text-gray-400">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Huduma bora na uponyaji wa haraka</h3>
              <p class="my-4">“Nilipata majeraha ya michezo, lakini baada ya vipindi kadhaa vya physiotherapy, maumivu yalipungua sana na kurudi uwanjani mapema.”</p>
              <p class="my-4">Nimependa sana utaratibu na maelezo ninayopewa kila hatua ya matibabu.”</p>
            </blockquote>
            <figcaption class="flex justify-center items-center space-x-3">
              <img class="w-9 h-9 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/karen-nelson.png" alt="profile picture">
              <div class="space-y-0.5 font-medium dark:text-white text-left">
                <div>Bonnie Green</div>
                <div class="text-sm font-light text-gray-500 dark:text-gray-400">Athlete</div>
              </div>
            </figcaption>
          </figure>
          <figure class="flex flex-col justify-center items-center p-8 text-center bg-gray-50 border-b border-gray-200 md:p-12 dark:bg-gray-800 dark:border-gray-700">
            <blockquote class="mx-auto mb-8 max-w-2xl text-gray-500 dark:text-gray-400">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Utaalamu na urafiki wa timu</h3>
              <p class="my-4">“Wataalamu ni wazoefu na wenye huruma. Walinisaidia kuelewa kiini cha maumivu ya mgongo na jinsi ya kuyadhibiti.”</p>
              <p class="my-4">Ningependa kuwapendekeza kwa yeyote anayeumia mgongo au shingo.”</p>
            </blockquote>
            <figcaption class="flex justify-center items-center space-x-3">
              <img class="w-9 h-9 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png" alt="profile picture">
              <div class="space-y-0.5 font-medium dark:text-white text-left">
                <div>Roberta Casas</div>
                <div class="text-sm font-light text-gray-500 dark:text-gray-400">Designer</div>
              </div>
            </figcaption>
          </figure>
          <figure class="flex flex-col justify-center items-center p-8 text-center bg-gray-50 border-b border-gray-200 lg:border-b-0 md:p-12 lg:border-r dark:bg-gray-800 dark:border-gray-700">
            <blockquote class="mx-auto mb-8 max-w-2xl text-gray-500 dark:text-gray-400">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mtiririko mzuri wa matibabu</h3>
              <p class="my-4">“Ratiba ni wazi na urahisi wa kufanya booking mtandaoni umenisaidia sana. Niliweza kupanga miadi kulingana na muda wangu.”</p>
              <p class="my-4">Huduma ni za kitaalamu na mazingira mazuri.”</p>
            </blockquote>
            <figcaption class="flex justify-center items-center space-x-3">
              <img class="w-9 h-9 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="profile picture">
              <div class="space-y-0.5 font-medium dark:text-white text-left">
                <div>Jese Leos</div>
                <div class="text-sm font-light text-gray-500 dark:text-gray-400">Engineer</div>
              </div>
            </figcaption>
          </figure>
          <figure class="flex flex-col justify-center items-center p-8 text-center bg-gray-50 border-gray-200 md:p-12 dark:bg-gray-800 dark:border-gray-700">
            <blockquote class="mx-auto mb-8 max-w-2xl text-gray-500 dark:text-gray-400">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ufanisi wa kushirikiana</h3>
              <p class="my-4">“Huduma ni za kiwango cha juu na mawasiliano bora. Nilihisi kusikilizwa na kuelekezwa ipasavyo.”</p>
              <p class="my-4">Nitarudi tena endapo nitahitaji huduma za physiotherapy.”</p>
            </blockquote>
            <figcaption class="flex justify-center items-center space-x-3">
              <img class="w-9 h-9 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/joseph-mcfall.png" alt="profile picture">
              <div class="space-y-0.5 font-medium dark:text-white text-left">
                <div>Joseph McFall</div>
                <div class="text-sm font-light text-gray-500 dark:text-gray-400">CTO</div>
              </div>
            </figcaption>
          </figure>
        </div>
        <div class="text-center">
          <a href="#" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-accent-300 hover:bg-accent-100 dark:text-white dark:border-accent-700 dark:hover:bg-accent-800/40 focus:z-10 focus:ring-4 focus:ring-accent-200 dark:focus:ring-accent-900">Show more...</a>
        </div>
      </div>
    </section>
  </body>
</html>
