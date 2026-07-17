<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

ListSnsShare の Props 一覧
(components/elements/lists/ListSnsShare/ListSnsShare.php)

現在の投稿ページをSNSにシェアするリンク一覧を出力します。
引数は不要です。現在のページURLとタイトルを自動取得します。

対応SNS:
- Facebook
- X（Twitter）
- Threads
- Bluesky
- LINE
- はてなブックマーク
- Pocket
- Feedly
- note
- Pinterest
- RSS

使用するWordPress関数:
- get_permalink() → 現在ページのURL
- single_post_title() → 投稿タイトル
- wp_title() → サイトタイトル

※ 投稿ページ（single.php など）で使うことを前提としています。
※ 固定ページや一覧ページでは正しく動作しない場合があります。

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ListSnsShare', []);
?>
