<header class="sticky top-0 z-40">
  <nav class="bg-white/90 backdrop-blur border-b border-gray-200 px-4 lg:px-6 py-3 dark:bg-gray-900/80 dark:border-gray-800">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
      <a href="/" class="flex items-center" wire:navigate>
        <img src="/logo.png" class="mr-3 h-6 sm:h-9" alt="{{ config('app.name', 'Logo') }}" />
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name', 'Laravel') }}</span>
      </a>
      <div class="flex items-center lg:order-2">
        <div class="hidden lg:flex items-center">
          <a href="{{ route('login') }}" class="text-gray-800 dark:text-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2.5 mr-2 dark:hover:bg-gray-800 focus:outline-none dark:focus:ring-gray-700" wire:navigate>Ingia</a>
          <a href="{{ route('register') }}" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800" wire:navigate>Fanya booking</a>
        </div>
        <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2.5 ml-1 text-gray-600 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:ring-gray-700" aria-controls="mobile-menu-2" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
          <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
      </div>
      <div class="hidden w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
        <ul class="flex flex-col mt-3 lg:mt-0 font-medium lg:flex-row lg:space-x-8 lg:items-center w-full lg:w-auto rounded-xl lg:rounded-none bg-white dark:bg-gray-900 lg:bg-transparent lg:dark:bg-transparent shadow lg:shadow-none border border-gray-200 lg:border-0 p-4 lg:p-0">
          <li>
            <a href="/" class="block py-2.5 pr-4 pl-3 text-white rounded lg:text-gray-700 bg-primary-700 lg:bg-transparent lg:hover:text-primary-700 lg:p-0 dark:text-white" aria-current="page">Home</a>
          </li>
          <li>
            <a href="{{ route('doctors.index') }}" class="block py-2.5 pr-4 pl-3 text-gray-700 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-300 lg:dark:hover:text-white dark:hover:bg-gray-800 dark:hover:text-white lg:dark:hover:bg-transparent" wire:navigate>Doctors</a>
          </li>
          <li>
            <a href="{{ route('testimonials.index') }}" class="block py-2.5 pr-4 pl-3 text-gray-700 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-300 lg:dark:hover:text-white dark:hover:bg-gray-800 dark:hover:text-white lg:dark:hover:bg-transparent" wire:navigate>Testimonials</a>
          </li>
          <li>
            <a href="{{ route('contact.index') }}" class="block py-2.5 pr-4 pl-3 text-gray-700 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-300 lg:dark:hover:text-white dark:hover:bg-gray-800 dark:hover:text-white lg:dark:hover:bg-transparent" wire:navigate>Contact Us</a>
          </li>
          <!-- Mobile-only CTAs -->
          <li class="mt-2 lg:hidden">
            <a href="{{ route('login') }}" class="block w-full text-center text-gray-800 dark:text-white hover:bg-gray-100 focus:ring-2 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:hover:bg-gray-800" wire:navigate>Ingia</a>
          </li>
          <li class="mt-2 lg:hidden">
            <a href="{{ route('register') }}" class="block w-full text-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-2 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700" wire:navigate>Fanya booking</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
