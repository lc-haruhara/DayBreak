// DEMO : https://haruhara.lc-dev.xyz/example/scroll-trigger-js/index.php
// CODE : https://haruhara.lc-dev.xyz/example/scroll-trigger-js/script-code.php

// ページが完全にロードされた後に、ScrollTrigger の位置を再計算
window.addEventListener('load', () => {
  ScrollTrigger.refresh();  // トリガー位置を更新
});

// ウィンドウがリサイズされた後に、ScrollTrigger の位置を再計算
window.addEventListener('resize', () => {
  ScrollTrigger.refresh();  // トリガー位置を更新
});

//