## コンポーネントパーツの便利な使い方
https://s.craft.me/3uAO1U5Yu2Magt

## ContactForm7ショートコード
<?php echo apply_shortcodes('[contact-form-7 id="98110d1" title="Contact form 1"]'); ?>

## turnstile を cf7 に出すためのショートコード。管理画面から貼り付け。
[cf7-simple-turnstile]

<!--::::::::::::::::::::::::::::::::::::::::::::

  Modal
  trigger になる button の aria-controls を modal の ID と揃える

::::::::::::::::::::::::::::::::::::::::::::-->
<button type="button" aria-expanded="false" aria-controls="modal-hogehoge">
  hogehoge モーダル
</button>

<?php
C_Parts('Modal', [
  'id'      => 'hogehoge',
  'close_buttons' => false,
  'content' => 'Modal/ModalContents-hogehoge'
]);
?>

<!--::::::::::::::::::::::::::::::::::::::::::::

  LinkButton

::::::::::::::::::::::::::::::::::::::::::::-->
url : 外部リンクOK 自動でblankになる
text : 設定しないと more と出力 (ButtonLink.phpで変更可)
class : 追加classが必要な際
size : 指定した文字列でヘルパークラスが出る ._size-xxxxx
color : 指定した文字列でヘルパークラスが出る ._color-xxxxx
aria : label > リンクの時の場合のみ設定 / それ以外 > モーダルOpenボタンの時に設定
data : 必要な場合のみ
icon : iconを表示する際、material symbol の IconNameを入力
icon_position : iconの表示位置 left / right 初期値は right

※ aria-label は設定すると以下のように出力されます。
button > [設定した文言]
_self遷移の場合 > [設定した文言]へ遷移
_blank遷移の場合 > [設定した文言]へ遷移（別ウィンドウで開きます）

<?php
C_Elements('ButtonLink', [
  'url'   => 'example',
  'text'  => 'Open Modal',
  'class' => 'c-btn-link-ex',
  'size'  => 'xxxxx',
  'color'  => 'xxxxx',
  'icon'  => 'chevron_forward',
  'icon_position'  => 'left',
  'aria'  => [
    'controls' => 'modal-hogehoge',
    'expanded' => 'false',
    'label'    => 'Open hogehoge modal'
  ],
  'data' => [
    'modal-open' => 'hogehoge'
  ],
]);
?>

<!-- 最小構成 ::::::::::::::::::::::::::::-->
<?php
C_Elements('ButtonLink', [
  'url'   => 'example',
  'aria'  => [
    'label'    => '会社概要'
  ],
]);
?>

<!-- ButtonLink メールアドレス・電話番号の場合 ::::::::::::::::::::::::::::-->
<?php
C_Elements('ButtonLink', [
  'url'   => 'info@example.com',
  'text'  => 'メールする',
  'aria'  => ['label' => 'info@example.com にメールする'],
]);
?>

<!-- ButtonLink 電話番号の場合 ::::::::::::::::::::::::::::-->
<?php
C_Elements('ButtonLink', [
  'url'   => '090-9999-9999',
  'text'  => '電話する',
  'aria'  => ['label' => '090-9999-9999 に電話する'],
]);
?>

<!--::::::::::::::::::::::::::::::::::::::::::::

  Icon

::::::::::::::::::::::::::::::::::::::::::::-->
https://fonts.google.com/icons
のアイコンのIcon nameを渡すと表示してくれる
画像パスを渡すと画像で出してくれる

icon : Google Materia Symbol の IconName を渡すとアイコン表示。画像パスを渡すと画像表示。
button : true にすると button として表示。
size : 指定した文字列でヘルパークラスが出る ._size-xxxxx
color : 指定した文字列でヘルパークラスが出る ._color-xxxxx
aria : label > リンクの時の場合のみ設定

※ aria-label は設定すると以下のように出力されます。
button > [設定した文言]
a _self遷移の場合 > [設定した文言]へ遷移
a _blank遷移の場合 > [設定した文言]へ遷移（別ウィンドウで開きます）

<?php
C_Elements('IconCommon', [
  'icon'  => 'chevron_forward',
  'url' => 'https://google.com',
  'size'  => 'xxxxx',
  'color'  => 'xxxxx',
  'aria' => [
    'label' => '会社概要'
  ]
]);
?>



<!--::::::::::::::::::::::::::::::::::::::::::::

  List

::::::::::::::::::::::::::::::::::::::::::::-->

<!--::::::::::::::::::::::::::::::::::::::::::::
  プライバシーポリシー
::::::::::::::::::::::::::::::::::::::::::::-->

<!-- アナリティクス文言の表示 ------------------------------->
<?php
C_Elements('ListPP', [
  'analytics'  => true,
  'turnstile'  => false,
]);
?>

<!-- turnstile文言の表示 ------------------------------->
<?php
C_Elements('ListPP', [
  'analytics'  => true,
  'turnstile'  => false,
]);
?>