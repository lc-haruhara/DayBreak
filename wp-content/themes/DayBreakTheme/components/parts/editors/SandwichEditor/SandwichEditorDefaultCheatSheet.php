<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

SandwichEditorDefault の Props 一覧
(components/parts/editors/SandwichEditor/SandwichEditorDefault.php)

ACF の FlexibleContents フィールドを使ったコンテンツビューアです。
引数は不要です。現在の投稿の FlexibleContents を自動取得します。

対応レイアウト:
- Col1Layout → 1カラム（Col1.php）
- Col2Layout → 2カラム（Col1.php + Col2.php）
- Col3Layout → 3カラム（Col1.php + Col2.php + Col3.php）
- HeadingBigLayout → 大見出し（HeadingBig.php）
- HeadingSmallLayout → 小見出し（HeadingSmall.php）
- ListLayout → リスト（List.php）
- ListNameLayout → 名称付きリスト（ListName.php）
- TableLayout → テーブル（Table.php）
- YoutubeLayout → YouTube 埋め込み（Youtube.php）
- FileLayout → ファイルダウンロード（File.php）
- LinkButtonLayout → リンクボタン（LinkButton.php）

付与される data 属性:
- data-js-table-of-contents-target → 目次生成対象
- data-js-auto-hyper-link → テキスト内 URL を自動リンク化

前提条件:
- ACF（Advanced Custom Fields）プラグインが必要
- FlexibleContents フィールドが投稿タイプに設定されていること
- 各レイアウトは ACF 側のフィールド設定に依存します

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
  ※ FlexibleContents が設定された投稿ページで使用
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('SandwichEditorDefault');
?>
