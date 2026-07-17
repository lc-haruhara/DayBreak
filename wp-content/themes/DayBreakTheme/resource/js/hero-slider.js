// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	トップページ ヒーロー画像の切り替え
//	.p-top-hero-image::after の円形プログレスバーを唯一の時計として使い、
//	リングが 1 周するたび（6 秒ごと）に画像を 1 枚進める。
//	setInterval で独自に数えるとリングと必ずズレるため、
//	毎回リングの currentTime を読み直して番号を決める。
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

const RING_NAME = 'hero-progress';
const SHUTTER_NAME = 'hero-shutter';
const INTERVAL = 6000;
// hero-shutter は 40%〜60% が閉じ切り。その中間で画像を差し替える。
// 秒数は SCSS 側にしか書かれておらず、ここでは実際の再生時間から割合で求める
const SHUTTER_CLOSED_RATIO = 0.5;

const root = document.querySelector('.p-top-hero-image');
const items = root ? [...root.querySelectorAll('.p-top-hero-image-item')] : [];
const shutter = root ? root.querySelector('.p-top-hero-image-shutter') : null;

if (root && items.length) {
  const render = (index) => {
    const prev = (index - 1 + items.length) % items.length;

    items.forEach((item, i) => {
      item.classList.toggle('is-active', i === index);
      item.classList.toggle('is-prev', i === prev);
    });
  };

  let current = -1;

  const step = (index) => {
    if (index === current) return;

    // 初回はシャッターを切らずにそのまま出す
    if (current === -1) {
      current = index;
      render(index);
      return;
    }

    current = index;

    if (!shutter) {
      render(index);
      return;
    }

    shutter.classList.remove('is-shooting');
    shutter.offsetWidth; // アニメーションを頭から再生し直す
    shutter.classList.add('is-shooting');

    const blade = shutter
      .getAnimations({ subtree: true })
      .find((animation) => animation.animationName === SHUTTER_NAME);
    const duration = blade ? blade.effect.getTiming().duration : null;

    // 再生時間を読めなければ隠せる保証がないので、待たずに差し替える
    if (typeof duration !== 'number' || !Number.isFinite(duration)) {
      render(index);
      return;
    }

    setTimeout(() => render(index), duration * SHUTTER_CLOSED_RATIO);
  };

  // リングのアニメーションを取得できない環境向け。リングとの同期は保証されない
  const startFallback = () => {
    step(0);
    setInterval(() => step((current + 1) % items.length), INTERVAL);
  };

  // リングの時刻から番号を計算し、次の 6 秒境界ちょうどに次を予約する。
  // 毎回読み直すので誤差が溜まらない
  const startSynced = (ring) => {
    const schedule = () => {
      const time = ring.currentTime;

      // 再生前は null、将来 CSSNumericValue になる可能性もある。
      // どちらも数値として扱えないのでフォールバックへ逃がす
      if (typeof time !== 'number') {
        startFallback();
        return;
      }

      step(Math.floor(time / INTERVAL) % items.length);
      setTimeout(schedule, INTERVAL - (time % INTERVAL));
    };
    schedule();
  };

  // CSS アニメーションはスタイル計算後に生成されるため、
  // モジュール実行直後だと getAnimations() がまだ空の場合がある
  requestAnimationFrame(() => {
    const ring = root
      .getAnimations({ subtree: true })
      .find((animation) => animation.animationName === RING_NAME);

    if (ring) {
      startSynced(ring);
    } else {
      startFallback();
    }
  });
}
