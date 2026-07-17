<!--::::::::::::::::::::::::::::::::::::::::::::
  Button
::::::::::::::::::::::::::::::::::::::::::::-->
<button
  type="button"
  class="c-navigation-drawer-button"
  aria-expanded="false"
  aria-controls="global-navigation-drawer"
  aria-label="メニューを開く">

  <span class="c-navigation-drawer-button-body">

    <!-- Text ::::::::::::::::::::::::::::-->
    <span class="c-navigation-drawer-button-text">
      <span class="open">
        Menu
      </span>
      <span class="close">
        Close
      </span>
    </span>

    <!-- Icon ::::::::::::::::::::::::::::-->
    <span class="c-navigation-drawer-button-icon">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </span>
  </span>

</button>

<!--::::::::::::::::::::::::::::::::::::::::::::
  Navigation Drawer
::::::::::::::::::::::::::::::::::::::::::::-->
<nav
  id="global-navigation-drawer"
  class="c-navigation-drawer-nav"
  aria-label="ドロワーメニュー"
  aria-hidden="true"
  inert>

  <div class="c-navigation-drawer-nav-body">
    <ul class="c-navigation-drawer-nav-list">
      <?php C_Elements('ListMenu', [
        'pp' => true,
        'contact' => true,
      ]); ?>
    </ul>
  </div>

  <div class="c-navigation-drawer-close-ovl" aria-hidden="true"></div>
</nav>

<script>
  (() => {
    const button = document.querySelector('.c-navigation-drawer-button');
    const drawer = document.getElementById('global-navigation-drawer');
    const drawerBody = drawer?.querySelector('.c-navigation-drawer-nav-body');

    if (!button || !drawer || !drawerBody) return;

    const focusableSelector = [
      'a[href]',
      'area[href]',
      'input:not([disabled]):not([type="hidden"])',
      'select:not([disabled])',
      'textarea:not([disabled])',
      'button:not([disabled])',
      '[tabindex]:not([tabindex="-1"])'
    ].join(',');

    let lastFocusedElement = null;

    const isOpen = () => !drawer.hasAttribute('inert');

    const isVisible = (el) => {
      return !!el && el.offsetParent !== null && !el.hasAttribute('hidden');
    };

    const getDrawerFocusableElements = () => {
      return [...drawer.querySelectorAll(focusableSelector)].filter(isVisible);
    };

    const openDrawer = () => {
      lastFocusedElement = document.activeElement;

      drawer.removeAttribute('inert');
      drawer.setAttribute('aria-hidden', 'false');
      drawer.classList.add('is-open');

      button.setAttribute('aria-expanded', 'true');
      button.setAttribute('aria-label', 'メニューを閉じる');

      document.body.classList.add('is-drawer-open');

      const drawerFocusableElements = getDrawerFocusableElements();
      if (drawerFocusableElements.length > 0) {
        drawerFocusableElements[0].focus();
      } else {
        drawer.setAttribute('tabindex', '-1');
        drawer.focus();
      }
    };

    const closeDrawer = () => {
      drawer.setAttribute('inert', '');
      drawer.setAttribute('aria-hidden', 'true');
      drawer.classList.remove('is-open');

      button.setAttribute('aria-expanded', 'false');
      button.setAttribute('aria-label', 'メニューを開く');

      document.body.classList.remove('is-drawer-open');

      if (drawer.getAttribute('tabindex') === '-1') {
        drawer.removeAttribute('tabindex');
      }

      if (lastFocusedElement instanceof HTMLElement) {
        lastFocusedElement.focus();
      } else {
        button.focus();
      }
    };

    const toggleDrawer = () => {
      isOpen() ? closeDrawer() : openDrawer();
    };

    const trapFocus = (event) => {
      if (!isOpen() || event.key !== 'Tab') return;

      const drawerFocusableElements = getDrawerFocusableElements();
      if (!drawerFocusableElements.length) {
        event.preventDefault();
        button.focus();
        return;
      }

      const firstDrawerElement = drawerFocusableElements[0];
      const lastDrawerElement = drawerFocusableElements[drawerFocusableElements.length - 1];
      const activeElement = document.activeElement;

      // button → Tab → drawer先頭
      if (!event.shiftKey && activeElement === button) {
        event.preventDefault();
        firstDrawerElement.focus();
        return;
      }

      // drawer先頭 → Shift+Tab → button
      if (event.shiftKey && activeElement === firstDrawerElement) {
        event.preventDefault();
        button.focus();
        return;
      }

      // drawer最後 → Tab → button
      if (!event.shiftKey && activeElement === lastDrawerElement) {
        event.preventDefault();
        button.focus();
        return;
      }

      // button → Shift+Tab → drawer最後
      if (event.shiftKey && activeElement === button) {
        event.preventDefault();
        lastDrawerElement.focus();
      }
    };

    button.addEventListener('click', toggleDrawer);

    drawer.addEventListener('click', (event) => {
      if (!isOpen()) return;

      if (!drawerBody.contains(event.target)) {
        closeDrawer();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (!isOpen()) return;

      if (event.key === 'Escape') {
        event.preventDefault();
        closeDrawer();
        return;
      }

      trapFocus(event);
    });

    // メニュー内リンククリックで閉じる
    const drawerLinks = drawer.querySelectorAll('a[href]');

    drawerLinks.forEach(link => {
      link.addEventListener('click', () => {
        closeDrawer();
      });
    });
  })();
</script>