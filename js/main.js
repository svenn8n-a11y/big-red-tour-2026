/* =====================================================
   MILWAUKEE BIG RED TOUR 2026 – MEMMINGEN
   Main JavaScript
   ===================================================== */

'use strict';

// ─── GSAP + ScrollTrigger ───────────────────────────────────────────────────
gsap.registerPlugin(ScrollTrigger);

// ─── DOM READY ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initCountdown();
  initHeroAnimation();
  initScrollReveal();
  initHeader();
  initScrollProgress();
  initCustomCursor();
  initMagneticButtons();
  initTabs();
  initFAQ();
  initMobileNav();
  initHeroParallax();
  initCard3D();
  initForm();
  initMusic();
  initErlebnisScroll();
  initGallery();
  initDealStamp();
  initHeiglCar();
});

// ─── 1. COUNTDOWN ───────────────────────────────────────────────────────────
function initCountdown() {
  // 10 April 2026, 08:00 CEST (UTC+2)
  const target = new Date('2026-04-10T08:00:00+02:00').getTime();

  const els = {
    d: document.getElementById('cDays'),
    h: document.getElementById('cHours'),
    m: document.getElementById('cMins'),
    s: document.getElementById('cSecs'),
  };

  if (!els.d) return;

  function pad(n) { return String(n).padStart(2, '0'); }

  function flashTick(el) {
    el.classList.add('tick');
    setTimeout(() => el.classList.remove('tick'), 300);
  }

  let prevSecs = -1;

  function tick() {
    const now  = Date.now();
    const diff = target - now;

    if (diff <= 0) {
      els.d.textContent = '00';
      els.h.textContent = '00';
      els.m.textContent = '00';
      els.s.textContent = '00';
      return;
    }

    const days  = Math.floor(diff / 86400000);
    const hours = Math.floor((diff % 86400000) / 3600000);
    const mins  = Math.floor((diff % 3600000)  / 60000);
    const secs  = Math.floor((diff % 60000)    / 1000);

    els.d.textContent = pad(days);
    els.h.textContent = pad(hours);
    els.m.textContent = pad(mins);

    if (secs !== prevSecs) {
      els.s.textContent = pad(secs);
      flashTick(els.s);
      if (secs === 59) flashTick(els.m);
      if (secs === 59 && mins === 59) flashTick(els.h);
      if (secs === 59 && mins === 59 && hours === 23) flashTick(els.d);
      prevSecs = secs;
    }
  }

  tick();
  setInterval(tick, 500);
}

// ─── 2. HERO ENTRANCE ANIMATION ─────────────────────────────────────────────
function initHeroAnimation() {
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  tl.from('#heroEyebrow',      { opacity: 0, y: 30, duration: 0.8, delay: 0.3 })
    .from('#heroLine1',         { opacity: 0, y: 60, duration: 0.7 }, '-=0.4')
    .from('#heroLine2',         { opacity: 0, y: 60, duration: 0.7 }, '-=0.5')
    .from('.hero__sub-text',    { opacity: 0, y: 30, duration: 0.6 }, '-=0.4')
    .from('#heroPoeppelLogo',   { opacity: 0, x: 120, duration: 0.9, ease: 'power4.out' }, '-=0.2')
    .from('#heroDesc',          { opacity: 0, y: 20, duration: 0.6 }, '-=0.4')
    .from('#heroActions',       { opacity: 0, y: 20, duration: 0.6 }, '-=0.4')
    .from('#heroCountdown',     { opacity: 0, x: 30, duration: 0.6 }, '-=0.5')
    .call(alignLogoToH1);
}

// ─── 2b. LOGO-ALIGNMENT: Pöppel-OK = BIG RED-OK · Milwaukee-UK = TOUR-UK ────
function alignLogoToH1() {
  const line1    = document.getElementById('heroLine1');
  const line2    = document.getElementById('heroLine2');
  const logoWrap = document.getElementById('heroPoeppelLogo');
  if (!line1 || !line2 || !logoWrap) return;
  const parent = logoWrap.offsetParent;
  if (!parent) return;
  const parentRect = parent.getBoundingClientRect();
  const line1Rect  = line1.getBoundingClientRect();
  const line2Rect  = line2.getBoundingClientRect();
  logoWrap.style.top    = (line1Rect.top    - parentRect.top) + 'px';
  logoWrap.style.height = (line2Rect.bottom - line1Rect.top)  + 'px';
}
window.addEventListener('resize', alignLogoToH1, { passive: true });

// ─── 3. SCROLL REVEAL ───────────────────────────────────────────────────────
function initScrollReveal() {
  const els = document.querySelectorAll('.reveal-up');

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    els.forEach(el => io.observe(el));
  } else {
    // Fallback: show all
    els.forEach(el => el.classList.add('is-visible'));
  }
}

// ─── 4. HEADER (scroll effect) ──────────────────────────────────────────────
function initHeader() {
  const header = document.getElementById('header');
  if (!header) return;

  let lastY = 0;

  function onScroll() {
    const y = window.scrollY;
    if (y > 60) {
      header.classList.add('is-scrolled');
    } else {
      header.classList.remove('is-scrolled');
    }
    lastY = y;
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// ─── 5. SCROLL PROGRESS BAR ─────────────────────────────────────────────────
function initScrollProgress() {
  const bar = document.getElementById('scrollProgress');
  if (!bar) return;

  window.addEventListener('scroll', () => {
    const scrolled = window.scrollY;
    const total    = document.documentElement.scrollHeight - window.innerHeight;
    const pct      = total > 0 ? (scrolled / total) * 100 : 0;
    bar.style.width = pct + '%';
  }, { passive: true });
}

// ─── 6. CUSTOM CURSOR ───────────────────────────────────────────────────────
function initCustomCursor() {
  const cursor = document.getElementById('cursor');
  const ring   = document.getElementById('cursorRing');
  if (!cursor || !ring) return;
  if (window.matchMedia('(max-width: 768px)').matches) return;

  let mx = 0, my = 0;
  let rx = 0, ry = 0;
  let raf;

  document.addEventListener('mousemove', (e) => {
    mx = e.clientX;
    my = e.clientY;
    cursor.style.left = mx + 'px';
    cursor.style.top  = my + 'px';

    if (!raf) {
      raf = requestAnimationFrame(animateRing);
    }
  });

  function animateRing() {
    rx += (mx - rx) * 0.12;
    ry += (my - ry) * 0.12;
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    raf = requestAnimationFrame(animateRing);
  }

  document.addEventListener('mousedown', () => cursor.classList.add('is-clicking'));
  document.addEventListener('mouseup',   () => cursor.classList.remove('is-clicking'));

  // Hover state on interactive elements
  const hoverEls = document.querySelectorAll('a, button, .tab-btn, .faq__q, input, select, .erlebnis__card, .deals__card');
  hoverEls.forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursor.classList.add('is-hovered');
      ring.classList.add('is-hovered');
    });
    el.addEventListener('mouseleave', () => {
      cursor.classList.remove('is-hovered');
      ring.classList.remove('is-hovered');
    });
  });
}

// ─── 7. MAGNETIC BUTTONS ────────────────────────────────────────────────────
function initMagneticButtons() {
  if (window.matchMedia('(max-width: 768px)').matches) return;

  const btns = document.querySelectorAll('.mag-btn');

  btns.forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const rect   = btn.getBoundingClientRect();
      const cx     = rect.left + rect.width  / 2;
      const cy     = rect.top  + rect.height / 2;
      const dx     = (e.clientX - cx) * 0.35;
      const dy     = (e.clientY - cy) * 0.35;
      btn.style.transform = `translate(${dx}px, ${dy}px)`;
    });

    btn.addEventListener('mouseleave', () => {
      btn.style.transform = 'translate(0,0)';
    });
  });
}

// ─── 8. TABS (Zielgruppen) ──────────────────────────────────────────────────
function initTabs() {
  const tabBtns   = document.querySelectorAll('.tab-btn');
  const tabPanels = document.querySelectorAll('.tab-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;

      // Deactivate all
      tabBtns.forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      tabPanels.forEach(p => p.classList.remove('active'));

      // Activate clicked
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      const panel = document.getElementById('tab-' + target);
      if (panel) {
        panel.classList.add('active');
        // Re-animate
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(10px)';
        requestAnimationFrame(() => {
          panel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          panel.style.opacity = '1';
          panel.style.transform = 'translateY(0)';
        });
      }
    });
  });

  // Mobile: close nav on tab link click
  document.querySelectorAll('.mobile-nav__link').forEach(link => {
    link.addEventListener('click', closeMobileNav);
  });
}

// ─── 9. FAQ ACCORDION ───────────────────────────────────────────────────────
function initFAQ() {
  const items = document.querySelectorAll('.faq__item');

  items.forEach(item => {
    const btn = item.querySelector('.faq__q');
    const ans = item.querySelector('.faq__a');
    if (!btn || !ans) return;

    btn.addEventListener('click', () => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';

      // Close all
      items.forEach(i => {
        const b = i.querySelector('.faq__q');
        const a = i.querySelector('.faq__a');
        if (b && a) {
          b.setAttribute('aria-expanded', 'false');
          a.style.maxHeight = '0';
        }
      });

      // Open clicked (unless it was already open)
      if (!isOpen) {
        btn.setAttribute('aria-expanded', 'true');
        ans.style.maxHeight = ans.scrollHeight + 'px';
      }
    });
  });
}

// ─── 10. MOBILE NAV ─────────────────────────────────────────────────────────
let mobileNavOpen = false;

function openMobileNav() {
  document.getElementById('mobileNav')?.classList.add('is-open');
  document.getElementById('mobileOverlay')?.classList.add('is-open');
  document.getElementById('burger')?.classList.add('is-open');
  document.body.style.overflow = 'hidden';
  mobileNavOpen = true;
}

function closeMobileNav() {
  document.getElementById('mobileNav')?.classList.remove('is-open');
  document.getElementById('mobileOverlay')?.classList.remove('is-open');
  document.getElementById('burger')?.classList.remove('is-open');
  document.body.style.overflow = '';
  mobileNavOpen = false;
}

function initMobileNav() {
  document.getElementById('burger')?.addEventListener('click', () => {
    mobileNavOpen ? closeMobileNav() : openMobileNav();
  });

  document.getElementById('mobileNavClose')?.addEventListener('click', closeMobileNav);
  document.getElementById('mobileOverlay')?.addEventListener('click', closeMobileNav);

  document.querySelectorAll('.mobile-nav__link').forEach(link => {
    link.addEventListener('click', () => {
      closeMobileNav();
    });
  });
}

// ─── 11. HERO PARALLAX ──────────────────────────────────────────────────────
function initHeroParallax() {
  const img = document.getElementById('heroImg');
  if (!img) return;

  // Scroll parallax
  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    img.style.transform = `translateY(${y * 0.2}px)`;
  }, { passive: true });

  // Mouse parallax (subtle)
  if (window.matchMedia('(min-width: 769px)').matches) {
    document.addEventListener('mousemove', (e) => {
      const dx = (e.clientX / window.innerWidth  - 0.5) * 10;
      const dy = (e.clientY / window.innerHeight - 0.5) * 6;
      img.style.transform = `translate(${dx}px, ${dy}px)`;
    });
  }
}

// ─── 12. 3D CARD HOVER ──────────────────────────────────────────────────────
function initCard3D() {
  if (window.matchMedia('(max-width: 768px)').matches) return;

  const cards = document.querySelectorAll('.card-3d');

  cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width  - 0.5;
      const y = (e.clientY - rect.top)  / rect.height - 0.5;
      card.style.transform = `perspective(800px) rotateX(${-y * 6}deg) rotateY(${x * 6}deg)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(800px) rotateX(0) rotateY(0)';
    });
  });
}

// ─── 13. FORM SUBMISSION ────────────────────────────────────────────────────
function initForm() {
  const form    = document.getElementById('regForm');
  const success = document.getElementById('formSuccess');
  const btn     = document.getElementById('submitBtn');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Basic validation
    const email = form.querySelector('#email');
    if (!email.value.includes('@')) {
      shakeEl(email);
      return;
    }

    // Loading state
    btn.disabled = true;
    const span = btn.querySelector('span');
    const origText = span.textContent;
    span.textContent = 'Wird gesendet…';

    try {
      const data = new FormData(form);
      const action = form.action;

      // If no real Formspree endpoint yet, just simulate
      if (action.includes('YOUR_FORM_ID')) {
        await fakeSend();
      } else {
        const res = await fetch(action, {
          method: 'POST',
          body: data,
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Server error');
      }

      // Success
      form.style.display = 'none';
      if (success) {
        success.classList.add('is-visible');
        success.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }

    } catch (err) {
      span.textContent = 'Fehler – bitte erneut versuchen';
      btn.disabled = false;
      setTimeout(() => {
        span.textContent = origText;
      }, 3000);
    }
  });
}

function fakeSend() {
  return new Promise(resolve => setTimeout(resolve, 1000));
}

function shakeEl(el) {
  el.style.animation = 'none';
  el.offsetHeight; // reflow
  el.style.animation = 'shake 0.4s ease';
  el.focus();
  setTimeout(() => { el.style.animation = ''; }, 400);
}

// Inject shake keyframe dynamically
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
  }
`;
document.head.appendChild(shakeStyle);

// ─── 14. MUSIC CONTROL ──────────────────────────────────────────────────────
// Muted Autoplay: Browser erlaubt stummes Autoplay immer.
// Direktes play() – Browser queued intern bis Audio bereit (kein canplay-Race).
//
function initMusic() {
  const heroBtn   = document.getElementById('musicBtn');
  const headerBtn = document.getElementById('headerMuteBtn');
  const audio     = document.getElementById('heroAudio');
  if (!audio) return;

  audio.volume = 0.35;
  audio.muted  = true;

  // Reaktiv: UI immer auf echten Audio-Events basieren, nie auf Timing
  function updateUI() {
    const playing = !audio.paused && !audio.muted;
    heroBtn?.classList.toggle('is-playing', playing);
    headerBtn?.classList.toggle('is-playing', playing);
    headerBtn?.classList.toggle('is-muted', audio.muted);
    headerBtn?.setAttribute('aria-label', audio.muted ? 'Ton einschalten' : 'Ton stumm schalten');
  }
  audio.addEventListener('play',         updateUI);
  audio.addEventListener('pause',        updateUI);
  audio.addEventListener('volumechange', updateUI);

  // Muted Autoplay – Browser erlaubt stummes Abspielen immer
  audio.play().catch(() => {
    // Autoplay geblockt → beim ersten Gesture starten (muted)
    const unlockOnce = () => { audio.play().catch(() => {}); };
    document.addEventListener('click',      unlockOnce, { once: true, capture: true });
    document.addEventListener('touchstart', unlockOnce, { once: true, capture: true });
  });

  function toggleMute() {
    if (audio.paused) {
      audio.muted = false;
      audio.play().catch(() => {});
    } else {
      audio.muted = !audio.muted;
    }
    // updateUI wird durch 'play'/'volumechange' Events ausgelöst
  }

  heroBtn?.addEventListener('click',   toggleMute);
  headerBtn?.addEventListener('click', toggleMute);
}

// ─── 15. ERLEBNIS HORIZONTAL SCROLL ─────────────────────────────────────────
function initErlebnisScroll() {
  if (window.matchMedia('(max-width: 768px)').matches) return;

  const section = document.querySelector('.erlebnis');
  const track   = document.querySelector('.erlebnis__track');
  if (!section || !track) return;

  gsap.to(track, {
    x: () => -(track.scrollWidth - section.offsetWidth),
    ease: 'none',
    scrollTrigger: {
      trigger:   section,
      pin:       true,
      scrub:     1,
      start:     'top top',
      end:       () => `+=${track.scrollWidth - section.offsetWidth}`,
      invalidateOnRefresh: true,
    },
  });
}

// ─── 16. SMOOTH SCROLL for anchor links ─────────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', (e) => {
    const target = document.querySelector(a.getAttribute('href'));
    if (!target) return;
    e.preventDefault();

    const headerH = document.getElementById('header')?.offsetHeight ?? 70;
    const top = target.getBoundingClientRect().top + window.scrollY - headerH - 8;

    window.scrollTo({ top, behavior: 'smooth' });
  });
});

// ─── 17. GALLERY SLIDER ──────────────────────────────────────────────────────
function initGallery() {
  const track  = document.getElementById('galleryTrack');
  const dotsWrap = document.getElementById('galleryDots');
  const prevBtn  = document.getElementById('galleryPrev');
  const nextBtn  = document.getElementById('galleryNext');
  if (!track) return;

  const slides = track.querySelectorAll('.gallery__slide');
  const total  = slides.length;
  let current  = 0;
  let timer;

  // Set each slide to exact viewport width (flex: none in CSS, Breite per JS)
  function setWidths() {
    const w = track.parentElement.offsetWidth;
    slides.forEach(s => {
      s.style.width     = w + 'px';
      s.style.flexBasis = w + 'px';
    });
    // Position nach Resize korrigieren
    const slideW = track.parentElement.offsetWidth;
    track.style.transform = `translateX(-${current * slideW}px)`;
  }
  setWidths();
  window.addEventListener('resize', setWidths, { passive: true });

  // Create dot buttons
  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.className = 'gallery__dot' + (i === 0 ? ' active' : '');
    dot.setAttribute('aria-label', `Bild ${i + 1}`);
    dot.addEventListener('click', () => goTo(i));
    dotsWrap?.appendChild(dot);
  });

  function goTo(index) {
    current = ((index % total) + total) % total;
    const slideW = track.parentElement.offsetWidth;
    track.style.transform = `translateX(-${current * slideW}px)`;
    dotsWrap?.querySelectorAll('.gallery__dot').forEach((d, i) => {
      d.classList.toggle('active', i === current);
    });
    resetTimer();
  }

  function resetTimer() {
    clearInterval(timer);
    timer = setInterval(() => goTo(current + 1), 5000);
  }

  prevBtn?.addEventListener('click', () => goTo(current - 1));
  nextBtn?.addEventListener('click', () => goTo(current + 1));

  // Touch/swipe support
  let touchStartX = 0;
  track.parentElement.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
  track.parentElement.addEventListener('touchend', e => {
    const dx = e.changedTouches[0].clientX - touchStartX;
    if (Math.abs(dx) > 40) goTo(dx < 0 ? current + 1 : current - 1);
  });

  resetTimer();
}

// ─── TOP SECRET STEMPEL ─────────────────────────────────────────────────────
function initDealStamp() {
  const stamp = document.getElementById('dealsStamp');
  if (!stamp) return;
  ScrollTrigger.create({
    trigger: stamp,
    start: 'top 80%',
    once: true,
    onEnter: () => stamp.classList.add('is-stamped')
  });
}

// ─── HEIGL AUTO EINFAHRT ────────────────────────────────────────────────────
function initHeiglCar() {
  const carWrap = document.querySelector('.food__car-wrap');
  if (!carWrap) return;
  ScrollTrigger.create({
    trigger: '#food',
    start: 'top 70%',
    once: true,
    onEnter: () => carWrap.classList.add('is-visible')
  });
}
