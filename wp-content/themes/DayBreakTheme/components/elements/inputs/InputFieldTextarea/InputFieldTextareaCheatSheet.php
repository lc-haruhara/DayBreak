<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

InputFieldTextarea の Props 一覧
(components/elements/inputs/InputFieldTextarea/InputFieldTextarea.php)

テキストエリア入力欄に使います。
type => 'textarea' を指定することで textarea になります。

name: string  ※必須
textarea の name / id に使われます。

例:
- 'name' => 'your-message'

--------------------------------

label?: string
ラベルテキストです。

例:
- 'label' => 'お問い合わせ内容'

--------------------------------

required?: bool
true で必須フィールドになります。

例:
- 'required' => true

--------------------------------

hint?: string
入力欄の下に表示するヒントテキストです。

例:
- 'hint' => 'ご質問・ご依頼の内容をご記入ください'

--------------------------------

type?: string
未指定時は 'text' になり input タグが出力されます。
textarea として使うには必ず 'textarea' を指定してください。

例:
- 'type' => 'textarea'

--------------------------------

id?: string
textarea の id を name と別にしたいときに指定します。

例:
- 'id' => 'custom-id'

--------------------------------

autocomplete?: string
textarea の autocomplete 属性です。

例:
- 'autocomplete' => 'off'

--------------------------------

CF7 モード
field キーに props を渡すと CF7 用タグとして出力されます。

CF7 モード時は textarea に以下が追加されます:
- class="wpcf7-form-control wpcf7-textarea [wpcf7-validates-as-required]"
- wpcf7-form-control-wrap でラップされます

--------------------------------

内部構造

- .c-input-item
  - label.c-input-label [._any]
  - .c-input-item-input
    - .c-input-item-input-body
      - textarea
    - span#name-help.c-input-placeholder（hint あり時）

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：テキストエリア
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldTextarea', [
  'name'  => 'your-message',
  'label' => 'お問い合わせ内容',
  'type'  => 'textarea',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須フィールド + ヒント
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldTextarea', [
  'name'     => 'your-message',
  'label'    => 'お問い合わせ内容',
  'type'     => 'textarea',
  'required' => true,
  'hint'     => 'ご質問・ご依頼の内容をご記入ください',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldTextarea', [
  'field' => [
    'name'     => 'your-message',
    'label'    => 'お問い合わせ内容',
    'type'     => 'textarea',
    'required' => true,
    'hint'     => 'ご質問・ご依頼の内容をご記入ください',
  ],
]);
?>
