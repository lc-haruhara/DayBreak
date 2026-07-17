<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

ListPP の Props 一覧
(components/elements/lists/ListPP/ListPP.php)

プライバシーポリシーの本文を出力します。
Google Analytics や Cloudflare Turnstile の利用有無に応じて
表示内容を切り替えられます。

お問い合わせ先の住所・電話番号は
components/utilities/schema.json の organization 情報から自動取得します。

analytics?: bool
Google Analytics に関する項目を表示します。
true にすると「アクセス解析ツールについて」の項目が追加されます。

例:
- 'analytics' => true

--------------------------------

turnstile?: bool
Cloudflare Turnstile に関する項目を表示します。
true にすると「スパム対策について」の項目が追加されます。

例:
- 'turnstile' => true

--------------------------------

含まれる固定項目:
- 個人情報の利用目的
- 個人情報の第三者提供
- 個人情報の管理
- Cookie（クッキー）について
- SSL（HTTPS）による通信の暗号化
- 免責事項
- プライバシーポリシーの変更
- お問い合わせ先（schema.json から自動取得）

出力クラス: .c-list-pp

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：固定項目のみ
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ListPP', []);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  Google Analytics 利用あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ListPP', [
  'analytics' => true,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  Google Analytics + Cloudflare Turnstile 利用あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ListPP', [
  'analytics' => true,
  'turnstile' => true,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  Cloudflare Turnstile のみ利用あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ListPP', [
  'turnstile' => true,
]);
?>
