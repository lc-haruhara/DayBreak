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

    // data-lottie-reverse あり: is-on 解除で逆再生する（なしは従来どおり pause）
    const isReverse = 'lottieReverse' in el.dataset;

    const sync = () => {
      if (trigger.classList.contains(ACTIVE_CLASS)) {
        if (isReverse) {
          // 逆再生の途中で戻ってきた場合も、現在フレームから順再生して繋げる
          anim.setDirection(1);
          anim.play();
        } else {
          // 入るたびに最初から再生
          anim.goToAndPlay(0, true);
        }
      } else if (isReverse) {
        // 現在フレームから 0 へ巻き戻す（0 到達で自動停止）
        // 先頭で待機中（未再生・巻き戻し完了後）は何もしない
        anim.setDirection(-1);
        if (anim.currentFrame > 0) anim.play();
        else anim.pause();
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
