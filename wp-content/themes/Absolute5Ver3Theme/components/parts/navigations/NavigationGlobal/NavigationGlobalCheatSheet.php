<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

NavigationGlobal の Props 一覧
(components/parts/navigations/NavigationGlobal/NavigationGlobal.php)

グローバルナビゲーションを出力します。
引数は不要です。

内部で ListMenu（contact=true, pp=true）を呼び出します。

出力構造:
- nav.c-navigation-global
  - .c-navigation-global-body
    - ul.c-navigation-global-list
      - [ListMenu の li 要素群]

通常は MountContentsCommon.php で呼び出されます。

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('NavigationGlobal');
?>
