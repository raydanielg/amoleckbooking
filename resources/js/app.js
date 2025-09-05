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

  // Auth page background slider
  const slider = document.querySelector('[data-auth-slider]');
  if (slider) {
    const attr = slider.getAttribute('data-images') || '';
    const images = attr.split(',').map(s => s.trim()).filter(Boolean);
    let idx = 0;
    const next = () => {
      if (images.length <= 1) return; // nothing to rotate
      idx = (idx + 1) % images.length;
      const nextSrc = images[idx];
      // fade out
      slider.style.opacity = '0';
      const img = new Image();
      img.onload = () => {
        slider.src = nextSrc;
        // small delay to allow browser to apply src before fade-in
        requestAnimationFrame(() => {
          slider.style.opacity = '1';
        });
      };
      img.src = nextSrc;
    };
    // ensure initial has transition
    slider.style.transition = slider.style.transition || 'opacity 700ms ease';
    setInterval(next, 5000);
  }
});
