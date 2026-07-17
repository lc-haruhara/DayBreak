/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 ページの読込み時（トップページのみ）

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

// トップページかどうかを確認
if (window.location.pathname === '/' || window.location.pathname === '/index.html') {

  // ページの読み込みが完了したら .is-loading を削除し、c-loading に .is-loaded を追加
  window.addEventListener('load', () => {
    const mainRootWrap = document.querySelector('.l-main-root-wrap');
    const loadingWrap = document.querySelector('.c-loading');

    // .l-main-root-wrap の .is-loading を削除（100msディレイ）
    if (mainRootWrap) {
      setTimeout(() => {
        mainRootWrap.classList.remove('is-loading');
      }, 100);
    }

    // .c-loading に .is-loaded を追加（200msディレイ）
    if (loadingWrap) {
      setTimeout(() => {
        loadingWrap.classList.add('is-loaded');
      }, 200);
    }
  });

  // ブラウザバック時の処理を追加
  window.onpageshow = function (event) {
    /*---------------------------------------------------------------------
      ✅ 強制リロード ※ブラウザバックでローディング画面から抜けられないケースの対処
    ---------------------------------------------------------------------*/
    if (event.persisted) {
      window.location.reload();
    }
  };

}
