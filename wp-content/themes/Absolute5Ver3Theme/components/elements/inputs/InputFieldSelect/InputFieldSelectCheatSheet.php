<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

InputFieldSelect の Props 一覧
(components/elements/inputs/InputFieldSelect/InputFieldSelect.php)

セレクトボックス入力欄に使います。
type => 'select' を指定することで select タグになります。

name: string  ※必須
select の name / id に使われます。

例:
- 'name' => 'your-category'

--------------------------------

label?: string
ラベルテキストです。

例:
- 'label' => 'お問い合わせ種別'

--------------------------------

required?: bool
true で必須フィールドになります。

例:
- 'required' => true

--------------------------------

type?: string
必ず 'select' を指定してください。
未指定時は 'text' になり input タグが出力されます。

例:
- 'type' => 'select'

--------------------------------

options?: string[]
選択肢の配列です。
各値が value と表示テキストの両方に使われます。

例:
- 'options' => ['ご相談', 'お見積もり', 'その他']

--------------------------------

first_option_label?: string
セレクトボックスの先頭に表示する非選択時のラベルです。
value="" disabled selected の option として出力されます。

例:
- 'first_option_label' => '選択してください'

--------------------------------

id?: string
select の id を name と別にしたいときに指定します。

例:
- 'id' => 'custom-id'

--------------------------------

CF7 モード
field キーに props を渡すと CF7 用タグとして出力されます。

CF7 モード時は select に以下が追加されます:
- class="wpcf7-form-control wpcf7-select [wpcf7-validates-as-required]"
- wpcf7-form-control-wrap でラップされます

--------------------------------

内部構造

- .c-input-item
  - label.c-input-label [._any]
  - .c-input-item-input
    - .c-input-item-input-body
      - select
        - option（first_option_label あり時：disabled selected）
        - option × options の数

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：セレクトボックス
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldSelect', [
  'name'    => 'your-category',
  'label'   => 'お問い合わせ種別',
  'type'    => 'select',
  'options' => ['ご相談', 'お見積もり', 'その他'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須 + 先頭ラベルあり
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldSelect', [
  'name'               => 'your-category',
  'label'              => 'お問い合わせ種別',
  'type'               => 'select',
  'required'           => true,
  'first_option_label' => '選択してください',
  'options'            => ['ご相談', 'お見積もり', 'その他'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldSelect', [
  'field' => [
    'name'               => 'your-category',
    'label'              => 'お問い合わせ種別',
    'type'               => 'select',
    'required'           => true,
    'first_option_label' => '選択してください',
    'options'            => ['ご相談', 'お見積もり', 'その他'],
  ],
]);
?>
