/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 ページの読込み時

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
// ページの読み込み前に .l-main-root-wrap に .is-loading を追加
document.addEventListener('DOMContentLoaded', () => {
  const mainRootWrap = document.querySelector('.l-main-root-wrap');
  if (mainRootWrap) {
    mainRootWrap.classList.add('is-loading');
  }
});

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

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 共通イベント

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
document.addEventListener('DOMContentLoaded', function () {

  /*---------------------------------------------------------------------
    ✅ a要素を押下した際の遷移の処理 及び ローディング画面呼び出しのトリガー
  ---------------------------------------------------------------------*/
  // スルーの対象
  // - ハッシュリンク(#)
  // - 別ウィンドウでページを開く
  // - a mailto / tel / target
  // - data-js-non-trans

  // 対象のリンクを選択
  var links = document.querySelectorAll(
    'a:not([href^="#"]):not([href^="tel:"]):not([href^="mailto:"]):not([target]):not([download]):not([data-js-non-trans])'
  );

  // 各リンクにイベントリスナーを追加
  links.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // デフォルトのナビゲート動作をキャンセル

      var url = this.getAttribute('href'); // 遷移先のURLを取得
      const loadingWrap = document.querySelector('.c-loading');

      if (url) {
        if (loadingWrap) {
          loadingWrap.classList.remove('is-loaded'); // .c-loading から .is-loaded を削除
        }

        // 遷移先に遷移
        setTimeout(function() {
          window.location.href = url; // URLに遷移
        }, 150); // 150ミリ秒後に実行
      }
    });
  });

});

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 ページの表示時。ブラウザバックでも有効。

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
window.onpageshow = function (event) {

  /*---------------------------------------------------------------------
    ✅ 強制リロード ※ブラウザバックでローディング画面から抜けられないケースの対処
  ---------------------------------------------------------------------*/
  if (event.persisted) {
  	window.location.reload();
  }

};
