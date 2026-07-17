<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

InputFieldEmail の Props 一覧
(components/elements/inputs/InputFieldEmail/InputFieldEmail.php)

InputFieldText と同じ構造です。
メールアドレス入力欄に使います。
type => 'email' を指定することで email input になります。

name: string  ※必須
email input の name / id に使われます。

例:
- 'name' => 'your-email'

--------------------------------

label?: string
ラベルテキストです。

例:
- 'label' => 'メールアドレス'

--------------------------------

required?: bool
true で必須フィールドになります。

例:
- 'required' => true

--------------------------------

hint?: string
入力欄の下に表示するヒントテキストです。

例:
- 'hint' => '例：info@example.com'

--------------------------------

type?: string
未指定時は 'text' になります。
メールアドレス入力欄として使うには必ず 'email' を指定してください。

例:
- 'type' => 'email'

--------------------------------

placeholder?: string
input の placeholder 属性です。

例:
- 'placeholder' => 'info@example.com'

--------------------------------

autocomplete?: string
input の autocomplete 属性です。

例:
- 'autocomplete' => 'email'

--------------------------------

maxlength?: int
入力文字数の上限です。

例:
- 'maxlength' => 254

--------------------------------

CF7 モード
field キーに props を渡すと CF7 用タグとして出力されます。

CF7 モード時は input に以下が追加されます:
- class="wpcf7-form-control wpcf7-email [wpcf7-validates-as-required]"
- wpcf7-form-control-wrap でラップされます

--------------------------------

内部構造

- .c-input-item
  - label.c-input-label [._any]
  - .c-input-item-input
    - .c-input-item-input-body
      - input[type="email"]
    - span#name-help.c-input-placeholder（hint あり時）

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：メールアドレス入力
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldEmail', [
  'name'  => 'your-email',
  'label' => 'メールアドレス',
  'type'  => 'email',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須フィールド + ヒント
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldEmail', [
  'name'     => 'your-email',
  'label'    => 'メールアドレス',
  'type'     => 'email',
  'required' => true,
  'hint'     => '例：info@example.com',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  autocomplete + maxlength
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldEmail', [
  'name'         => 'your-email',
  'label'        => 'メールアドレス',
  'type'         => 'email',
  'required'     => true,
  'hint'         => '例：info@example.com',
  'autocomplete' => 'email',
  'maxlength'    => 254,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldEmail', [
  'field' => [
    'name'     => 'your-email',
    'label'    => 'メールアドレス',
    'type'     => 'email',
    'required' => true,
    'hint'     => '例：info@example.com',
  ],
]);
?>
