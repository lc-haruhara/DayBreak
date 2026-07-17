<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

LoadingCommon の Props 一覧
(components/parts/loadings/LoadingCommon/LoadingCommon.php)

ローディング画面のコンテナを出力します。
引数は不要です。

内部の演出コンテンツは LoadingCommon.php を直接編集して追加します。

出力構造:
- .c-loading
  - .inner
    （← ここにローディング演出を記述）

JS との連携:
- loading.js または loading-top-only.js と組み合わせて使います
- EnqueueResources.php の読み込み設定を確認してください

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('LoadingCommon');
?>
