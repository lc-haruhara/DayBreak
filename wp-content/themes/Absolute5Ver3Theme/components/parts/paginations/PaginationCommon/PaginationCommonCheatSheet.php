<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

PaginationCommon の Props 一覧
(components/parts/paginations/PaginationCommon/PaginationCommon.php)

ページネーションを出力します。
引数は不要です。グローバルの $wp_query を参照します。

$wp_query->max_num_pages が 1 以下の場合は何も出力されません。

依存:
- wp_pagenavi()（WP-PageNavi プラグイン）
- select_pagination_new() / select_pagination_old()（テーマ独自関数）

一般的な使い方:
- アーカイブページ・カスタム WP_Query のループの外に置く
- カスタム WP_Query を使う場合は query_posts() ではなく
  WP_Query を使い、$wp_query をグローバルに割り当ててください

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
  ※ $wp_query がセットされている前提
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('PaginationCommon');
?>
