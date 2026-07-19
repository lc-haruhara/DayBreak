// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	トップページ ヒーロー画像の表示切り替え
//	.p-top-concept-trigger が画面に入ったら .p-top-hero-image に is-hidden を付与。
//	is-hidden を外すのは hero 側へスクロールを戻した時（trigger が画面の下側へ
//	抜けた時）だけ。下へスクロールして trigger が画面上側へ抜けても is-hidden は維持する。
//	見た目（フェード・拡大など）は CSS 側の .p-top-hero-image.is-hidden で行う。
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

const HIDDEN_CLASS = 'is-hidden';

const image = document.querySelector('.p-top-hero-image');
const trigger = document.querySelector('.p-top-concept-trigger');

if (image && trigger && 'IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // trigger が画面に入ったら hero を隠す
        image.classList.add(HIDDEN_CLASS);
      } else if (entry.boundingClientRect.top > 0) {
        // trigger が画面より下にある = hero 側へ戻った時だけ元に戻す
        image.classList.remove(HIDDEN_CLASS);
      }
      // trigger が画面より上（下方向へ通り過ぎた）場合は is-hidden を維持
    });
  });

  observer.observe(trigger);
}
