<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

NavigationDrawer の Props 一覧
(components/parts/navigations/NavigationDrawer/NavigationDrawer.php)

ドロワーメニュー（ハンバーガーメニュー）を出力します。
引数は不要です。

内部で ListMenu（contact=true, pp=true）を呼び出します。

含まれる要素:
- ハンバーガーボタン（button.c-navigation-drawer-button）
- ドロワーナビゲーション（nav#global-navigation-drawer）

JS 機能（インライン script で同梱）:
- ボタンクリックでドロワーを開閉
- フォーカストラップ（Tab / Shift+Tab でドロワー内を循環）
- Escape キーで閉じる
- ドロワー外クリックで閉じる
- ドロワー内リンククリックで閉じる

ARIA 対応:
- aria-expanded を開閉時に自動更新
- inert 属性で非表示時のフォーカスを制御
- body に is-drawer-open クラスを付与

通常は MountContentsCommon.php で呼び出されます。

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('NavigationDrawer');
?>
