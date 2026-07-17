<!-- CookieBanner -->
<div
  class="c-dialog-cookie"
  id="cookie-dialog"
  role="dialog"
  aria-modal="true"
  aria-labelledby="cookie-title"
  aria-describedby="cookie-description">
  <div class="c-dialog-cookie-body">
    <h2 id="cookie-title" class="visually-hidden">
      Cookieの利用について
    </h2>
    <p
      class="c-dialog-cookie-description"
      id="cookie-description">
      より良いサービスのため、当サイトではアクセス解析にのみCookieを使用しています。取得情報は広告利用や第三者への提供は一切なく、サイト改善のみに活用します。詳細については
      <?php
      C_Elements('Link', [
        'text' => 'プライバシーポリシー',
        'href' => '/privacy-policy',
      ]);
      ?>
      をご確認ください。
    </p>

    <div class="c-dialog-cookie-buttons">
      <?php
      C_Elements('ButtonLink', [
        'id'  => 'cookie-deny-btn',
        'text'  => '拒否する',
        'icon'  => 'close',
        'icon_position'  => 'left',
        'aria'  => [
          'label'    => 'クッキーを拒否する'
        ],
      ]);
      ?>
      <?php
      C_Elements('ButtonLink', [
        'id'  => 'cookie-accept-btn',
        'text'  => '同意',
        'icon'  => 'check',
        'icon_position'  => 'left',
        'aria'  => [
          'label'    => 'クッキーの使用に同意する'
        ],
      ]);
      ?>
    </div>
  </div>

</div>

<script>
  const DEBUG_MODE = false; // デバッグモード
  const COOKIE_KEY = 'cookie_consent';
  const EXPIRATION_DAYS = DEBUG_MODE ? -1 : 180;
  const GA_MEASUREMENT_ID = 'G-XXXXXXXXXX'; // TODO: InsertGA_ID

  /* ==================================================
     GA4 Consent Mode v2
  ================================================== */
  function updateGtagConsent(consentGiven) {
    if (typeof gtag !== 'function') return;

    gtag('consent', 'update', {
      analytics_storage: consentGiven ? 'granted' : 'denied',
      ad_storage: 'denied',
      ad_user_data: 'denied',
      ad_personalization: 'denied'
    });
  }

  /* ==================================================
     GAを同意後にのみロード
  ================================================== */
  function loadGA() {
    if (window.__gaLoaded) return;
    window.__gaLoaded = true;

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtag/js?id=${GA_MEASUREMENT_ID}`;
    document.head.appendChild(script);

    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    window.gtag = gtag;

    gtag('js', new Date());
    gtag('config', GA_MEASUREMENT_ID);

    if (DEBUG_MODE) {
      console.log('[CookieBanner] GA loaded');
    }
  }

  function handleCookieChoice(consentGiven) {
    const expirationDate = new Date();

    if (DEBUG_MODE) {
      expirationDate.setMinutes(expirationDate.getMinutes() - 1);
    } else {
      expirationDate.setDate(expirationDate.getDate() + EXPIRATION_DAYS);
    }

    const consentData = {
      granted: consentGiven,
      expires: expirationDate.toISOString()
    };

    localStorage.setItem(COOKIE_KEY, JSON.stringify(consentData));

    hideCookieBanner();

    if (consentGiven) {
      loadGA();
    }

    updateGtagConsent(consentGiven);

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: consentGiven ? 'cookie_consent_granted' : 'cookie_consent_denied'
    });

    if (DEBUG_MODE) {
      console.log('[CookieBanner] 同意処理:', consentData);

      setTimeout(() => {
        localStorage.removeItem(COOKIE_KEY);

        const banner = document.getElementById('cookie-dialog');
        banner?.classList.add('is-show');

        console.log('[CookieBanner] DEBUG: consent reset');
      }, 3000);
    }
  }

  function getConsentData() {
    const raw = localStorage.getItem(COOKIE_KEY);
    if (!raw) return null;
    try {
      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  }

  function isConsentExpired() {
    const data = getConsentData();
    if (!data || !data.expires) return true;
    return new Date(data.expires) < new Date();
  }

  function updateConsentStatusOnLoad() {
    const banner = document.getElementById('cookie-dialog');
    if (!banner) return;

    // DEBUG_MODE 時は必ず未同意扱い
    if (DEBUG_MODE) {
      localStorage.removeItem(COOKIE_KEY);
      setTimeout(() => {
        banner.classList.add('is-show');
      }, 300);
      return;
    }

    const data = getConsentData();
    const expired = !data || !data.expires || new Date(data.expires) < new Date();

    if (expired) {
      if (data) {
        localStorage.removeItem(COOKIE_KEY);
      }
      banner.classList.add('is-show');
    } else {
      /* 同意済みならGAロード後、Consent Mode v2 に反映 */
      if (data.granted) {
        loadGA();
      }

      updateGtagConsent(data.granted);

      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        event: data.granted ? 'cookie_consent_granted' : 'cookie_consent_denied'
      });
    }
  }

  updateConsentStatusOnLoad();

  document
    .getElementById('cookie-accept-btn')
    ?.addEventListener('click', () => handleCookieChoice(true));

  document
    .getElementById('cookie-deny-btn')
    ?.addEventListener('click', () => handleCookieChoice(false));


  // ======================================
  // 同意後の挙動
  // ======================================
  function hideCookieBanner() {
    const banner = document.getElementById('cookie-dialog');
    if (!banner) return;

    // 視覚的には閉じる
    banner.classList.remove('is-show');

    // フォーカス移動
    const main = document.querySelector('main');
    if (main) {
      main.setAttribute('tabindex', '-1');
      main.focus({
        preventScroll: true
      });
    }

    // DEBUG時はDOM消さない
    if (DEBUG_MODE) return;

    // 本番だけ完全非表示
    setTimeout(() => {
      banner.hidden = true;
    }, 2000);
  }

  // ======================================
  // DEBUG_MODE 表示用UI（最小限）
  // ======================================
  if (DEBUG_MODE) {
    const debugBadge = document.createElement('div');
    debugBadge.textContent = 'DEBUG MODE';
    debugBadge.setAttribute('aria-hidden', 'true');

    Object.assign(debugBadge.style, {
      position: 'fixed',
      bottom: '8px',
      right: '8px',
      padding: '4px 8px',
      fontSize: '12px',
      fontWeight: 'bold',
      background: '#d32f2f',
      color: '#fff',
      borderRadius: '4px',
      zIndex: '999999',
      opacity: '0.9',
      pointerEvents: 'none'
    });

    document.body.appendChild(debugBadge);
  }
</script>