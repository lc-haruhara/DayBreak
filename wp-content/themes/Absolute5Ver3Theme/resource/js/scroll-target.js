(() => {
  const SELECTOR = '[data-js-scroll-target]';
  const ACTIVE_CLASS = 'is-on';
  const DEFAULT_TRIGGER_ADD = 0.8; //AddTiming
  const DEFAULT_TRIGGER_REMOVE = 0.2; //RemoveTiming

  const checkTargets = () => {
    const targets = document.querySelectorAll(SELECTOR);
    const windowH = window.innerHeight;

    targets.forEach((target) => {
      const rect = target.getBoundingClientRect();
      const mode = target.getAttribute('data-js-scroll-target');
      const onAttr = target.getAttribute('data-js-scroll-target-on');
      const offAttr = target.getAttribute('data-js-scroll-target-off');
      const onLine = windowH * (onAttr !== null ? parseFloat(onAttr) : DEFAULT_TRIGGER_ADD);
      const offLine = windowH * (offAttr !== null ? parseFloat(offAttr) : DEFAULT_TRIGGER_REMOVE);

      if (mode !== 'once' && rect.bottom <= offLine) {
        target.classList.remove(ACTIVE_CLASS);
      } else if (rect.top <= onLine) {
        target.classList.add(ACTIVE_CLASS);
      }
    });
  };

  let ticking = false;

  const onScroll = () => {
    if (ticking) return;

    ticking = true;
    requestAnimationFrame(() => {
      checkTargets();
      ticking = false;
    });
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', checkTargets);
  window.addEventListener('load', checkTargets);

  checkTargets();
})();