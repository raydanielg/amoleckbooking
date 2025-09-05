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

  // Sidebar drawer toggle
  const sidebar = document.getElementById('dashboard-sidebar');
  const sidebarOverlay = document.getElementById('dashboard-sidebar-overlay');
  const sidebarToggle = document.querySelector('[data-sidebar-toggle="dashboard-sidebar"]');
  const openSidebar = () => {
    if (!sidebar) return;
    sidebar.classList.remove('-translate-x-full');
    sidebarOverlay?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    if (sidebarToggle) sidebarToggle.setAttribute('aria-expanded', 'true');
  };
  const closeSidebar = () => {
    if (!sidebar) return;
    sidebar.classList.add('-translate-x-full');
    sidebarOverlay?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    if (sidebarToggle) sidebarToggle.setAttribute('aria-expanded', 'false');
  };
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', (e) => {
      e.preventDefault();
      openSidebar();
    });
  }
  sidebarOverlay?.addEventListener('click', closeSidebar);
  // Close on ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
  });
  // Close when clicking any nav link inside sidebar (mobile UX)
  if (sidebar) {
    sidebar.addEventListener('click', (e) => {
      const target = e.target;
      if (target instanceof HTMLElement && target.closest('a')) {
        closeSidebar();
      }
    });
  }

  // Simple month calendar for patient dashboard (TZ: Africa/Dar_es_Salaam)
  const calRoot = document.getElementById('patient-calendar');
  if (calRoot) {
    const TZ = 'Africa/Dar_es_Salaam';
    const LOCALE = 'sw-TZ';
    const apptsAttr = calRoot.getAttribute('data-appointments') || '[]';
    let appts = [];
    try { appts = JSON.parse(apptsAttr); } catch { appts = []; }

    const now = new Date();
    let viewYear = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, year: 'numeric' }).format(now) * 1;
    let viewMonth = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, month: 'numeric' }).format(now) * 1 - 1; // 0-based

    const fmtISO = (y, m, d) => {
      // m is 0-based
      const mm = String(m + 1).padStart(2, '0');
      const dd = String(d).padStart(2, '0');
      return `${y}-${mm}-${dd}`;
    };
    const weekdayIndex = (y, m, d) => {
      // Return 0..6 for Mon..Sun in TZ
      const date = new Date(Date.UTC(y, m, d, 12, 0, 0)); // noon UTC to avoid DST edge
      const wd = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, weekday: 'short' }).format(date);
      const map = { 'Jtat':0,'Jnne':1,'Jtan':2,'Alh':3,'Iju':4,'Jmos':5,'Jpil':6, 'Mon':0,'Tue':1,'Wed':2,'Thu':3,'Fri':4,'Sat':5,'Sun':6 };
      return map[wd] ?? ((date.getUTCDay()+6)%7);
    };
    const daysInMonth = (y, m) => new Date(y, m + 1, 0).getDate();

    const grouped = appts.reduce((acc, a) => {
      if (!a.date) return acc;
      acc[a.date] = acc[a.date] || [];
      acc[a.date].push(a);
      return acc;
    }, {});

    const render = () => {
      const y = viewYear, m = viewMonth;
      const startDay = weekdayIndex(y, m, 1); // Monday=0
      const dim = daysInMonth(y, m);

      calRoot.innerHTML = '';
      const header = document.createElement('div');
      header.className = 'flex items-center justify-between mb-3';
      const left = document.createElement('div');
      left.className = 'flex items-center gap-2';
      const prevBtn = document.createElement('button');
      prevBtn.type = 'button';
      prevBtn.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700';
      prevBtn.innerHTML = '<span aria-hidden="true">&#8592;</span><span class="hidden sm:inline text-xs">Mwezi uliopita</span>';
      prevBtn.onclick = () => { if (m === 0) { viewMonth = 11; viewYear = y - 1; } else { viewMonth = m - 1; } render(); };
      const nextBtn = document.createElement('button');
      nextBtn.type = 'button';
      nextBtn.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700';
      nextBtn.innerHTML = '<span class="hidden sm:inline text-xs">Mwezi ujao</span><span aria-hidden="true">&#8594;</span>';
      nextBtn.onclick = () => { if (m === 11) { viewMonth = 0; viewYear = y + 1; } else { viewMonth = m + 1; } render(); };
      const todayBtn = document.createElement('button');
      todayBtn.type = 'button';
      todayBtn.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-primary-50 text-primary-700 hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-300';
      todayBtn.textContent = 'Leo';
      todayBtn.onclick = () => {
        const t = new Date();
        viewYear = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, year: 'numeric' }).format(t) * 1;
        viewMonth = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, month: 'numeric' }).format(t) * 1 - 1;
        render();
      };
      left.appendChild(prevBtn); left.appendChild(todayBtn);

      const title = document.createElement('div');
      title.className = 'text-sm font-medium';
      const sample = new Date(Date.UTC(y, m, 1, 12, 0, 0));
      title.textContent = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, month: 'long', year: 'numeric' }).format(sample);

      const right = document.createElement('div'); right.appendChild(nextBtn);
      header.appendChild(left); header.appendChild(title); header.appendChild(right);
      calRoot.appendChild(header);

      // Weekday row (Swahili short)
      const weekdays = ['Jtat','Jnne','Jtan','Alh','Iju','Jmos','Jpil'];
      const rowNames = document.createElement('div');
      rowNames.className = 'grid grid-cols-7 gap-1 mb-1 text-[11px] text-gray-500';
      weekdays.forEach(w => { const el = document.createElement('div'); el.className = 'text-center'; el.textContent = w; rowNames.appendChild(el); });
      calRoot.appendChild(rowNames);

      const grid = document.createElement('div');
      grid.className = 'grid grid-cols-7 gap-1';
      for (let i=0;i<startDay;i++) { const b=document.createElement('div'); b.className='h-20 rounded bg-gray-50 dark:bg-gray-800'; grid.appendChild(b); }
      for (let day=1; day<=dim; day++) {
        const iso = fmtISO(y, m, day);
        const cell = document.createElement('div');
        cell.className = 'h-20 rounded border border-gray-100 dark:border-gray-800 p-1 relative bg-white dark:bg-gray-900';
        const num = document.createElement('div'); num.className = 'text-xs text-gray-500'; num.textContent = String(day); cell.appendChild(num);
        const list = document.createElement('div'); list.className = 'mt-1 space-y-1 overflow-hidden';
        const events = grouped[iso] || [];
        events.slice(0,2).forEach(e => {
          const pill = document.createElement('div'); pill.className = 'px-1.5 py-0.5 rounded text-[11px] bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 truncate'; pill.title = e.title || 'Miadi'; pill.textContent = e.title || 'Miadi'; list.appendChild(pill);
        });
        if (events.length > 2) { const more = document.createElement('div'); more.className = 'text-[11px] text-gray-500'; more.textContent = `+${events.length - 2} zaidi`; list.appendChild(more); }
        // highlight leo
        const tY = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, year: 'numeric' }).format(now) * 1;
        const tM = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, month: 'numeric' }).format(now) * 1 - 1;
        const tD = new Intl.DateTimeFormat(LOCALE, { timeZone: TZ, day: 'numeric' }).format(now) * 1;
        if (y === tY && m === tM && day === tD) { cell.classList.add('ring-1','ring-primary-500'); }
        cell.appendChild(list); grid.appendChild(cell);
      }
      calRoot.appendChild(grid);
    };

    render();
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

  // Profile dropdown toggle (dashboard header)
  const userToggle = document.querySelector('[data-dropdown-toggle="user-menu"]');
  const userMenu = document.getElementById('user-menu');
  if (userToggle && userMenu) {
    const closeMenu = (ev) => {
      if (!userMenu.contains(ev.target) && !userToggle.contains(ev.target)) {
        userMenu.classList.add('hidden');
        document.removeEventListener('click', closeMenu);
      }
    };
    userToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenu.classList.toggle('hidden');
      if (!userMenu.classList.contains('hidden')) {
        // attach outside click listener
        setTimeout(() => document.addEventListener('click', closeMenu), 0);
      }
    });
  }
});
