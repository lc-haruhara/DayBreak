/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 共通イベント

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
document.addEventListener('DOMContentLoaded', function () {

  /*---------------------------------------------------------------------
    ✅ クリックした要素自身に .is-on を toggle
  ---------------------------------------------------------------------*/
  document.querySelectorAll("[data-js-toggle-on]").forEach(element => {
    element.addEventListener("click", () => {
      element.classList.toggle("is-on");
    });
  });

  /*---------------------------------------------------------------------
    ✅ Modal
  ---------------------------------------------------------------------*/
  // src/resource/js/features/modal.js
  // モーダルを開くトリガーボタン
  const buttons = document.querySelectorAll("[data-modal-open]");
  // すべてのモーダル要素
  const modals = document.querySelectorAll(".c-modal-wrap");
  // モーダルを閉じるトリガー（ボタン・オーバーレイなど）
  const closes = document.querySelectorAll("[data-modal-close]");
  // メインコンテンツ（モーダル表示中に無効化する領域）
  const mainWrap = document.querySelector(".l-main-root-wrap");
  // モーダル全体を包む領域（開いている時だけ操作可能にする）
  const modalRootWrap = document.querySelector(".l-main-root-modal");

  // 現在アクティブな開閉ボタン（フォーカス戻し用）
  let activeButton = null;
  // 現在開いているモーダル
  let activeModal = null;
  // フォーカストラップ用イベントハンドラ
  let trapHandler = null;

  // 初期状態：モーダル領域は操作不可
  if (modalRootWrap) {
    modalRootWrap.setAttribute("inert", "");
    modalRootWrap.setAttribute("aria-hidden", "true");
  }

  // すべてのモーダルを閉じる処理
  function closeAllModals() {
    modals.forEach(modal => {
      // スクリーンリーダーから非表示
      modal.setAttribute("aria-hidden", "true");

      // 表示状態クラス削除
      modal.classList.remove("is-open");

      // フォーカストラップ解除
      if (trapHandler) {
        modal.removeEventListener("keydown", trapHandler);
      }
    });

    buttons.forEach(button => {
      // 開閉状態を閉じるに更新
      button.setAttribute("aria-expanded", "false");
    });

    if (mainWrap) {
      // メイン領域の操作制限を解除
      mainWrap.removeAttribute("inert");

      // inert未対応ブラウザ用のaria補助も解除
      mainWrap.removeAttribute("aria-hidden");
    }

    if (modalRootWrap) {
      // モーダル領域は閉じている時は操作不可
      modalRootWrap.setAttribute("inert", "");
      modalRootWrap.setAttribute("aria-hidden", "true");
    }

    if (activeButton) {
      // モーダルを開いたボタンにフォーカスを戻す
      activeButton.focus();
      activeButton = null;
    }

    // 状態リセット
    activeModal = null;
    trapHandler = null;
  }

  // 各ボタンにクリックイベントを付与
  buttons.forEach(button => {
    const modal = document.getElementById(
      button.getAttribute("aria-controls")
    );

    // 対応するモーダルが存在しない場合は何もしない
    if (!modal) return;

    button.addEventListener("click", () => {
      // 他に開いているモーダルを閉じる
      closeAllModals();

      // 現在のボタンとモーダルを記録
      activeButton = button;
      activeModal = modal;

      // ボタンの状態を開いているに更新
      button.setAttribute("aria-expanded", "true");

      // aria-hiddenは削除して表示状態にする
      modal.removeAttribute("aria-hidden");

      // 表示用クラス付与
      modal.classList.add("is-open");

      if (mainWrap) {
        // 背景コンテンツを操作不可にする
        mainWrap.setAttribute("inert", "");

        // inert未対応ブラウザ用のフォールバック
        mainWrap.setAttribute("aria-hidden", "true");
      }

      if (modalRootWrap) {
        // モーダル領域は開いている時だけ操作可能
        modalRootWrap.removeAttribute("inert");
        modalRootWrap.removeAttribute("aria-hidden");
      }

      // 初期フォーカスは「表示状態になってから」当てる（display:none 等を考慮）
      requestAnimationFrame(() => {
        // モーダル内のフォーカス可能要素を取得
        const focusables = modal.querySelectorAll(
          'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
        );

        const first = focusables[0];
        const last = focusables[focusables.length - 1];

        // 開いた直後の初期フォーカス（任意で指定可能）
        const initial =
          modal.querySelector("[data-modal-initial-focus]") ?? first ?? modal;

        // 初期フォーカス設定
        if (initial && typeof initial.focus === "function") {
          initial.focus();
        }

        // フォーカストラップ処理（フォーカス可能要素が1つ以上ある場合のみ）
        if (!first || !last) return;

        trapHandler = function (e) {
          if (e.key !== "Tab") return;

          // Shift + Tab（逆方向）
          if (e.shiftKey) {
            if (document.activeElement === first) {
              e.preventDefault();
              last.focus();
            }
          } else {
            // Tab（順方向）
            if (document.activeElement === last) {
              e.preventDefault();
              first.focus();
            }
          }
        };

        // モーダル内でTabキー操作を監視
        modal.addEventListener("keydown", trapHandler);
      });
    });
  });

  // 閉じるトリガー（ボタン・オーバーレイなど）
  closes.forEach(close => {
    close.addEventListener("click", closeAllModals);
  });

  // ESCキーでモーダルを閉じる
  document.addEventListener("keydown", e => {
    if (e.key === "Escape" && activeModal) {
      closeAllModals();
    }
  });

  // 外部から呼び出せるようにグローバル公開
  window.closeAllModals = closeAllModals;

});