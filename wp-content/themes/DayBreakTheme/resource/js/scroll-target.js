/**
 * viewport intersection で `is-on` class を toggle するユーティリティ。
 *
 * 使い方:
 *   <div data-js-scroll-target>...</div>          // 二方向 (デフォルト)
 *   <div data-js-scroll-target="once">...</div>   // 一方向 (付与後は監視解除)
 *
 * 発火タイミング (帯の広さは BAND_INSET_TOP / BAND_INSET_BOTTOM で調整):
 *   - 付与/維持: 要素が viewport の判定帯 (既定 20%〜80%) に重なっている間
 *   - 解除: 要素がその帯から外れたとき (once モード以外・上下どちらの方向でも)
 *
 * 付与と解除を単一の帯 (単一 IntersectionObserver) で判定するため、
 * スクロールを往復しても状態が自動で再評価され、再付与されないデッドゾーンが生じない。
 * getBoundingClientRect を持たないため強制リフローを発生させず、要素数が多くても
 * メインスレッド負荷が上がらない。
 *
 * 初期表示:
 *   ページロード時点で帯内にある要素は START_DELAY (800ms) 後に自動付与する。
 *   その遅延中にスクロールが起きた場合はスクロール側を優先し即時付与する
 *   (先に発火した方を採用し、もう一方はキャンセル)。
 *
 * @param {ParentNode} [root=document]
 * @returns {() => void} cleanup 関数
 */
export function initScrollTarget(root = document) {
  const targets = root.querySelectorAll('[data-js-scroll-target]');
  if (targets.length === 0) return () => { };

  // 判定に使う viewport 帯を、上端・下端からの inset (%) で指定する。
  // 値を大きくすると帯が狭く (画面中央寄りに)、小さくすると広くなる。
  //   例: TOP=20, BOTTOM=20 → 画面の 30%〜75% が判定帯
  //       TOP=0,  BOTTOM=0  → 画面全体が判定帯
  const BAND_INSET_TOP = 20;
  const BAND_INSET_BOTTOM = 25;

  // 初期表示要素の自動付与までの遅延 (ms)。
  const START_DELAY = 800;

  // 付与開始前は is-on を付与しない (ロード時のちらつき防止)。
  // START_DELAY 経過 または 初回スクロール のいずれか早い方で開始する。
  let started = false;
  const inBand = new Set();

  // viewport 上端・下端を BAND_INSET 分だけ縮めた帯に重なる間だけ is-on。
  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        const target = entry.target;
        const isOnce = target.getAttribute('data-js-scroll-target') === 'once';

        if (entry.isIntersecting) {
          inBand.add(target);
          if (!started) continue;
          target.classList.add('is-on');
          if (isOnce) observer.unobserve(target);
        } else {
          inBand.delete(target);
          if (!isOnce) target.classList.remove('is-on');
        }
      }
    },
    { rootMargin: `-${BAND_INSET_TOP}% 0px -${BAND_INSET_BOTTOM}% 0px` },
  );

  targets.forEach((target) => observer.observe(target));

  // 付与開始: すでに帯内にある要素へ is-on を反映する (冪等)。
  // 遅延タイマーと初回スクロールの両方から呼ばれ、先に呼ばれた方だけが実行される。
  const start = () => {
    if (started) return;
    started = true;
    if (startTimer !== null) clearTimeout(startTimer);
    window.removeEventListener('scroll', start);
    inBand.forEach((target) => {
      target.classList.add('is-on');
      if (target.getAttribute('data-js-scroll-target') === 'once') {
        observer.unobserve(target);
      }
    });
  };

  const startTimer = setTimeout(start, START_DELAY);
  window.addEventListener('scroll', start, { passive: true });

  return () => {
    observer.disconnect();
    clearTimeout(startTimer);
    window.removeEventListener('scroll', start);
  };
}
