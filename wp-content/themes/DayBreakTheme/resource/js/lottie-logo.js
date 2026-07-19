// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	Lottie アニメーションの初期化
//	[data-js-lottie] を持つ要素の data-lottie-src（JSON パス）を読み込む。
//	自動再生はせず、scroll-target.js が付与する is-on を監視して再生を開始する。
//	（トリガーは最寄りの [data-js-scroll-target] 祖先。無ければ要素自身）
//	lottie-web は CDN（EnqueueResources.php）でグローバル window.lottie として読み込まれる。
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

const ACTIVE_CLASS = 'is-on';

const targets = document.querySelectorAll('[data-js-lottie]');

if (targets.length && window.lottie) {
  targets.forEach((el) => {
    const src = el.dataset.lottieSrc;
    if (!src) return;

    const anim = window.lottie.loadAnimation({
      container: el,
      renderer: 'svg',
      loop: false,
      autoplay: false,
      path: src,
    });

    // is-on を監視するトリガー要素（scroll-target が付いた祖先 or 自身）
    const trigger = el.closest('[data-js-scroll-target]') || el;

    const sync = () => {
      if (trigger.classList.contains(ACTIVE_CLASS)) {
        // 入るたびに最初から再生
        anim.goToAndPlay(0, true);
      } else {
        anim.pause();
      }
    };

    // クラスの付け外しを監視
    const observer = new MutationObserver(sync);
    observer.observe(trigger, {
      attributes: true,
      attributeFilter: ['class'],
    });

    // 初期表示時点で既に is-on の場合に対応
    sync();
  });
}
