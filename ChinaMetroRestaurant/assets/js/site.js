document.addEventListener('DOMContentLoaded', () => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (reducedMotion) {
    document.documentElement.classList.add('reduced-motion');
    return;
  }

  const selectors = [
    '.page-hero',
    '.hero-copy',
    '.hero-card',
    '.section-heading',
    '.panel',
    '.menu-card',
    '.offer-card',
    '.contact-card',
    '.admin-card',
    '.stat'
  ];

  const seen = new Set();
  const revealNodes = [];

  selectors.forEach((selector) => {
    document.querySelectorAll(selector).forEach((element, index) => {
      if (seen.has(element)) {
        return;
      }

      seen.add(element);
      element.classList.add('reveal');
      element.style.setProperty('--reveal-delay', `${index * 70}ms`);
      revealNodes.push(element);
    });
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      }

      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, {
    threshold: 0.18,
    rootMargin: '0px 0px -8% 0px'
  });

  revealNodes.forEach((node) => observer.observe(node));

  const hero = document.querySelector('.hero');
  if (hero) {
    window.addEventListener('pointermove', (event) => {
      const x = ((event.clientX / window.innerWidth) - 0.5) * 10;
      const y = ((event.clientY / window.innerHeight) - 0.5) * 10;
      hero.style.setProperty('--pointer-x', `${x.toFixed(2)}px`);
      hero.style.setProperty('--pointer-y', `${y.toFixed(2)}px`);
    }, { passive: true });
  }
});
