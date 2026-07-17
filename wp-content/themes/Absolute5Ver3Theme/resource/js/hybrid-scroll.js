//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//
// Hybrid Scroll Settings (ordinals なし)
//
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 横移動の速さ
// 小さいと速く進む / 大きいとゆっくり進む
// 例: 0.6 = やや速め / 1 = 標準 / 1.2 = ゆっくり
const SCROLL_SPEED = 1;

// 横移動を開始するまでの待機量
// 0   = すぐ動く
// 200 = 少し待ってから動く
// 400 = かなり待ってから動く
const START_DELAY = 300;

// 最後の要素が中央に来たあとに持たせる余裕
// 大きいほど、最後が中央に来てから少し止まって見える
// 例: 200 = 少し余韻 / 400 = わりとしっかり余韻
const END_DELAY = 600;

// イージングの強さ
// 小さいとぬるっと追従 / 大きいとキビキビ追従
// 例: 0.05 = かなりなめらか / 0.08 = 自然 / 0.12 = やや速い
const EASING = 0.03;

// item が最小でどこまで薄くなるか
// 0   = 完全に消える
// 0.2 = うっすら見える
// 0.4 = けっこう見える
const ITEM_MIN_OPACITY = 1;

// opacity / scale が強く効く範囲
// 小さいと中央付近だけ強調
// 大きいと広い範囲をなだらかに強調
// 例: 0.4 = メリハリ強め / 0.6 = 自然 / 0.8 = なだらか
const ITEM_FADE_RANGE = 1;

// item の最小 scale
// 1    = 拡大縮小なし
// 0.95 = 少し小さく
// 0.9  = しっかり小さく
const ITEM_MIN_SCALE = 1;

// item の最大 scale
// 1    = 拡大なし
// 1.02 = ほんの少し強調
// 1.05 = わかりやすく強調
const ITEM_MAX_SCALE = 1;

// 現在の translateX 値
let currentX = 0;

// スクロール量から計算した目標位置
let targetX = 0;

// DOM キャッシュ
let hybridScrollElements = [];
let horizontalScrollBodies = [];
let scrollBodyItems = [];

const initCache = () => {
  hybridScrollElements = [...document.querySelectorAll('[data-js-hybrid-scroll-trigger]')];
  horizontalScrollBodies = [...document.querySelectorAll('[data-js-hybrid-scroll-target]')];
  scrollBodyItems = horizontalScrollBodies.map(body =>
    [...body.querySelectorAll('[data-js-hybrid-scroll-item]')]
  );
};

const lerp = (start, end, t) => {
  return start + (end - start) * t;
};

const updateHybridScroll = () => {
  hybridScrollElements.forEach((HybridScroll, index) => {
    const horizontalScrollBody = horizontalScrollBodies[index];
    if (!horizontalScrollBody) return;

    const items = scrollBodyItems[index];
    if (!items.length) return;

    const firstItem = items[0];
    const lastItem = items[items.length - 1];

    const HybridScrollTop = HybridScroll.getBoundingClientRect().top;
    const viewportHeight = window.innerHeight;
    const viewportWidth = window.innerWidth;

    const totalScrollable = HybridScroll.scrollHeight - viewportHeight;

    // 横移動に使う実質の距離
    // END_DELAY 分だけ最後に余韻を残す
    const moveDistance = Math.max(totalScrollable * SCROLL_SPEED - END_DELAY, 1);

    // 最初の要素が画面中央に来る位置
    const startX = -firstItem.offsetLeft;

    // 最後の要素が画面中央に来る位置
    const endX =
      viewportWidth / 2 -
      (lastItem.offsetLeft + lastItem.offsetWidth / 2);

    // まだ発火前なら最初の要素を中央で固定
    if (HybridScrollTop > 0) {
      targetX = startX;
      return;
    }

    const scrollTop = Math.abs(HybridScrollTop);
    const adjustedScroll = Math.max(scrollTop - START_DELAY, 0);
    const progress = Math.min(adjustedScroll / moveDistance, 1);

    targetX = startX + (endX - startX) * progress;
  });
};

const updateItemStyle = () => {
  const viewportCenter = window.innerWidth / 2;

  // 中央からどれくらい離れたら effect を弱めるか
  const maxDistance = window.innerWidth * ITEM_FADE_RANGE;

  horizontalScrollBodies.forEach((_, index) => {
    const items = scrollBodyItems[index];

    items.forEach((item) => {
      const rect = item.getBoundingClientRect();
      const itemCenter = rect.left + rect.width / 2;
      const distance = Math.abs(viewportCenter - itemCenter);

      // 中央に近いほど 1、遠いほど 0
      const normalized = Math.max(1 - distance / maxDistance, 0);

      // opacity
      const opacity =
        ITEM_MIN_OPACITY + (1 - ITEM_MIN_OPACITY) * normalized;

      // scale
      const scale =
        ITEM_MIN_SCALE + (ITEM_MAX_SCALE - ITEM_MIN_SCALE) * normalized;

      item.style.opacity = opacity;
      item.style.transform = `scale(${scale})`;
    });
  });
};

const animateHybridScroll = () => {
  currentX = lerp(currentX, targetX, EASING);
  if (Math.abs(currentX - targetX) < 0.01) currentX = targetX;

  horizontalScrollBodies.forEach((horizontalScrollBody) => {
    horizontalScrollBody.style.transform = `translateX(${currentX}px)`;
  });

  updateItemStyle();

  requestAnimationFrame(animateHybridScroll);
};

window.addEventListener('DOMContentLoaded', () => {
  initCache();
  updateHybridScroll();
});
window.addEventListener('load', updateHybridScroll);
window.addEventListener('scroll', updateHybridScroll);
window.addEventListener('resize', updateHybridScroll);

animateHybridScroll();
