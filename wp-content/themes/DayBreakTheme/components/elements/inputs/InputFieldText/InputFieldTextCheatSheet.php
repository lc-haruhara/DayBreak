<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

InputFieldText の Props 一覧
(components/elements/inputs/InputFieldText/InputFieldText.php)

name: string  ※必須
input の name / id に使われます。
未指定または空文字の場合は何も出力されません。

例:
- 'name' => 'your-name'
- 'name' => 'address'

--------------------------------

label?: string
ラベルテキストです。
<label for="{name}"> に出力されます。

例:
- 'label' => 'お名前'
- 'label' => '会社名'

--------------------------------

required?: bool
true にすると required 属性が付き、
ラベルに _any クラスが付かなくなります（必須表示）。
未指定時は false（任意）扱いで _any クラスが付きます。

例:
- 'required' => true
- 'required' => false

--------------------------------

hint?: string
入力欄の下に表示するプレースホルダー的なヒントテキストです。
<span id="{name}-help" class="c-input-placeholder"> として出力されます。
aria-describedby="{name}-help" と紐付いています。

例:
- 'hint' => '例：山田 太郎'
- 'hint' => '半角英数字で入力してください'

--------------------------------

type?: string
input の type 属性です。
未指定時は 'text' になります。

指定できる値:
- 'text'
- 'email'
- 'tel'
- 'url'
- 'number'
- 'password'

例:
- 'type' => 'text'
- 'type' => 'tel'

※ InputFieldText コンポーネント名は分類目的のもので、
  type を指定しないと自動で 'text' にはなりません。

--------------------------------

id?: string
input の id 属性を name と別にしたいときに指定します。
未指定時は name が id になります。

例:
- 'id' => 'custom-id'

--------------------------------

placeholder?: string
input の placeholder 属性です。
hint と併用可能です。

例:
- 'placeholder' => '例：山田 太郎'

--------------------------------

autocomplete?: string
input の autocomplete 属性です。

例:
- 'autocomplete' => 'name'
- 'autocomplete' => 'email'
- 'autocomplete' => 'tel'
- 'autocomplete' => 'off'

--------------------------------

maxlength?: int
入力文字数の上限です。

例:
- 'maxlength' => 100
- 'maxlength' => 50

--------------------------------

minlength?: int
入力文字数の下限です。

例:
- 'minlength' => 5

--------------------------------

CF7 モード
field キーに props を渡すと CF7 用タグとして出力されます。

例:
C_Elements('InputFieldText', [
  'field' => [
    'name'     => 'your-name',
    'label'    => 'お名前',
    'required' => true,
    'type'     => 'text',
    'hint'     => '例：山田 太郎',
  ],
]);

CF7 モード時は input に以下が追加されます:
- class="wpcf7-form-control wpcf7-text [wpcf7-validates-as-required]"
- wpcf7-form-control-wrap でラップされます

--------------------------------

内部構造

- .c-input-item
  - label.c-input-label [._any]
  - .c-input-item-input
    - .c-input-item-input-body
      - input
    - span#name-help.c-input-placeholder（hint あり時）

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：テキスト入力
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'  => 'your-name',
  'label' => 'お名前',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須フィールド
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'     => 'your-name',
  'label'    => 'お名前',
  'required' => true,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  ヒントテキストあり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'     => 'your-name',
  'label'    => 'お名前',
  'required' => true,
  'hint'     => '例：山田 太郎',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  placeholder あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'        => 'your-name',
  'label'       => 'お名前',
  'placeholder' => '例：山田 太郎',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  電話番号入力（type=tel）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'         => 'your-tel',
  'label'        => '電話番号',
  'type'         => 'tel',
  'required'     => true,
  'hint'         => '例：03-1234-5678',
  'autocomplete' => 'tel',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  URL入力（type=url）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'  => 'your-url',
  'label' => 'WebサイトURL',
  'type'  => 'url',
  'hint'  => '例：https://example.com/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  maxlength あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'      => 'subject',
  'label'     => '件名',
  'required'  => true,
  'maxlength' => 100,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  autocomplete あり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'name'         => 'your-name',
  'label'        => 'お名前',
  'required'     => true,
  'autocomplete' => 'name',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldText', [
  'field' => [
    'name'     => 'your-name',
    'label'    => 'お名前',
    'required' => true,
    'type'     => 'text',
    'hint'     => '例：山田 太郎',
  ],
]);
?>
