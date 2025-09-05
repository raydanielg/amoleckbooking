import './bootstrap';

// Mobile nav toggle for header partial (works across all pages)
document.addEventListener('DOMContentLoaded', () => {
  const toggles = document.querySelectorAll('[data-collapse-toggle="mobile-menu-2"]');
  toggles.forEach((btn) => {
    btn.addEventListener('click', () => {
      const menu = document.getElementById('mobile-menu-2');
      if (!menu) return;

      const expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', (!expanded).toString());

      // Toggle hidden/display classes
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.add('flex');
      } else {
        menu.classList.add('hidden');
        menu.classList.remove('flex');
      }

      // Swap hamburger/close icons inside the button
      const icons = btn.querySelectorAll('svg');
      if (icons.length >= 2) {
        icons[0].classList.toggle('hidden'); // hamburger
        icons[1].classList.toggle('hidden'); // close
      }
    });
  });
});
