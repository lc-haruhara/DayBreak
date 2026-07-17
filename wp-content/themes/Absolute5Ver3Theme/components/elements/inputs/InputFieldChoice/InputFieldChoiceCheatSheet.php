<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

InputFieldChoice の Props 一覧
(components/elements/inputs/InputFieldChoice/InputFieldChoice.php)

ラジオボタン / チェックボックスのグループに使います。
<fieldset> + <legend> で構成されます。

name: string  ※必須
input の name に使われます。
チェックボックスの場合は name[] として出力されます。
各 input の id は "{name}-1", "{name}-2" ... になります。

例:
- 'name' => 'your-inquiry'
- 'name' => 'your-agreement'

--------------------------------

label?: string
グループのラベルテキストです。
<legend> に出力されます。

例:
- 'label' => 'お問い合わせ種別'
- 'label' => 'ご希望のサービス'

--------------------------------

required?: bool
true で各 input に required が付きます。

例:
- 'required' => true

--------------------------------

type: string  ※必須
'radio' または 'checkbox' を指定します。
未指定時は 'text' になり input タグが出力されてしまいます。

例:
- 'type' => 'radio'
- 'type' => 'checkbox'

--------------------------------

options: string[]  ※必須
選択肢の配列です。
各値が value と表示テキストの両方に使われます。

例:
- 'options' => ['ご相談', 'お見積もり', 'その他']

--------------------------------

default?: string
ラジオボタンで最初から選択状態にしたい値です。
checkbox には効果がありません。

例:
- 'default' => 'ご相談'

--------------------------------

id?: string
各 input の id プレフィックスを name と別にしたいときに指定します。
未指定時は name が使われます。

例:
- 'id' => 'inquiry'

--------------------------------

CF7 モード
field キーに props を渡すと CF7 用タグとして出力されます。

CF7 モード時は span に以下が追加されます:
- class="wpcf7-form-control wpcf7-radio|wpcf7-checkbox [wpcf7-validates-as-required]"
- wpcf7-form-control-wrap でラップされます

--------------------------------

内部構造（ラジオ / チェックボックス共通）

- fieldset.c-input-radio
  - legend.c-input-label
  - span.wpcf7-form-control（CF7）/ span（通常）
    - label.c-input-radio-item × options の数
      - span.c-input-radio-item-body
        - input[type="radio"|"checkbox"]
        - span.c-input-radio-item-elm
          - span
        - span.c-input-radio-item-text

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：ラジオボタン
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'name'    => 'your-inquiry',
  'label'   => 'お問い合わせ種別',
  'type'    => 'radio',
  'options' => ['ご相談', 'お見積もり', 'その他'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須ラジオボタン + デフォルト選択
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'name'     => 'your-inquiry',
  'label'    => 'お問い合わせ種別',
  'type'     => 'radio',
  'required' => true,
  'options'  => ['ご相談', 'お見積もり', 'その他'],
  'default'  => 'ご相談',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  チェックボックス（複数選択）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'name'    => 'your-services',
  'label'   => 'ご希望のサービス',
  'type'    => 'checkbox',
  'options' => ['Webサイト制作', 'LP制作', 'UI/UXデザイン', 'その他'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  必須チェックボックス（1件以上選択を要求）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'name'     => 'your-services',
  'label'    => 'ご希望のサービス',
  'type'     => 'checkbox',
  'required' => true,
  'options'  => ['Webサイト制作', 'LP制作', 'UI/UXデザイン', 'その他'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード（ラジオ）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'field' => [
    'name'     => 'your-inquiry',
    'label'    => 'お問い合わせ種別',
    'type'     => 'radio',
    'required' => true,
    'options'  => ['ご相談', 'お見積もり', 'その他'],
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  CF7 モード（チェックボックス）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('InputFieldChoice', [
  'field' => [
    'name'    => 'your-services',
    'label'   => 'ご希望のサービス',
    'type'    => 'checkbox',
    'options' => ['Webサイト制作', 'LP制作', 'その他'],
  ],
]);
?>
