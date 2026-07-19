// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	トップページ ヒーロー画像の表示切り替え
//	.p-top-concept-trigger が画面に入る、または画面より上へ抜けている間は
//	.p-top-hero-image に is-hidden を付与する。trigger が画面より下にある
//	（＝hero 側にいる）時だけ is-hidden を外す。
//	これにより、ページ途中で読み込んで trigger を既に通り過ぎている場合も
//	初回から is-hidden が付く。
//	見た目（フェード・拡大など）は CSS 側の .p-top-hero-image.is-hidden で行う。
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

const HIDDEN_CLASS = 'is-hidden';

const image = document.querySelector('.p-top-hero-image');
const trigger = document.querySelector('.p-top-concept-trigger');

if (image && trigger && 'IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      // trigger が画面内、または画面より上へ抜けている（下方向へ通り過ぎた／
      // 途中読み込みで既に過ぎている）→ hero を隠す
      if (entry.isIntersecting || entry.boundingClientRect.top < 0) {
        image.classList.add(HIDDEN_CLASS);
      } else {
        // trigger が画面より下 = hero 側にいる → 元に戻す
        image.classList.remove(HIDDEN_CLASS);
      }
    });
  });

  observer.observe(trigger);
}
