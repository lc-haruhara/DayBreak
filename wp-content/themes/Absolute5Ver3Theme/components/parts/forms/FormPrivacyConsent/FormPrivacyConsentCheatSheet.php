<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

FormPrivacyConsent の Props 一覧
(components/parts/forms/FormPrivacyConsent/FormPrivacyConsent.php)

プライバシーポリシー確認チェックボックスを出力します。
「プライバシーポリシー」テキストをクリックすると
ModalCommon[pp] が開く構造になっています。

name?: string
チェックボックスの name / id に使われます。
未指定時は 'your-pp-confirm'。

例:
- 'name' => 'your-pp-confirm'
- 'name' => 'agree-pp'

--------------------------------

label?: string
チェックボックスのラベルテキストです。
未指定時は 'プライバシーポリシーに同意する'。

例:
- 'label' => 'プライバシーポリシーに同意する'
- 'label' => '上記の内容に同意します'

--------------------------------

依存関係

「プライバシーポリシー」ボタンは data-modal-open="pp" を持つため、
ModalCommon[pp] が MountModal.php 等で設置されている必要があります。

--------------------------------

内部構造

- .c-input-pp-confirm
  - p.c-input-pp-confirm-paragraph
    - button[data-modal-open="pp"]（プライバシーポリシーを開く）
  - .c-input-radio
    - label[for="{name}"].c-input-radio-item
      - span.c-input-radio-item-body
        - input[type="checkbox"]
        - span.c-input-radio-item-elm
        - span.c-input-radio-item-text

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：デフォルト設定で使う
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('FormPrivacyConsent', []);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  name と label を指定する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('FormPrivacyConsent', [
  'name'  => 'agree-pp',
  'label' => '上記の内容に同意します',
]);
?>
