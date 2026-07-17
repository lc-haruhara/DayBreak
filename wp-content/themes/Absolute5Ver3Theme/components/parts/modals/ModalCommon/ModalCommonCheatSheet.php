<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

ModalCommon の Props 一覧
(components/parts/modals/ModalCommon/ModalCommon.php)

モーダルダイアログのラッパーを出力します。
モーダルの内部コンテンツは対応する
ModalCommon[{id}].php ファイルに記述します。

id: string  ※必須
モーダルの識別子です。
以下の用途に使われます。
- DOM の id="modal-{id}"
- 内部コンテンツの呼び出し（C_Elements('ModalCommon[{id}]', ...)）

例:
- 'id' => 'pp'           → ModalCommon[pp].php を読み込む
- 'id' => 'confirm'      → ModalCommon[confirm].php を読み込む
- 'id' => 'hogehoge'     → ModalCommon[hogehoge].php を読み込む

※ ModalCommon[{id}].php が存在しない場合は内部コンテンツが空になります。

--------------------------------

close_buttons?: bool
フッター部分の「閉じる」ボタンを表示するかどうかです。
未指定時は true（表示する）。

false にするケース:
- 確認フォームなど、ユーザーに「閉じる」ボタンを
  使わせたくない場合

例:
- 'close_buttons' => true
- 'close_buttons' => false

--------------------------------

モーダルを開く方法（JS）

data-modal-open="{id}" を持つ要素をクリックすると開きます。
ButtonLink の例:

C_Elements('ButtonLink', [
  'text' => 'プライバシーポリシーを確認',
  'data' => [
    'modal-open' => 'pp',
  ],
  'aria' => [
    'controls' => 'modal-pp',
    'expanded' => 'false',
  ],
]);

--------------------------------

モーダルを閉じる方法（JS）

- data-modal-close 属性を持つ要素クリック
- モーダル外側（オーバーレイ）クリック
- Escape キー

--------------------------------

内部構造

- .c-modal-wrap#modal-{id}[role="dialog"]
  - .c-modal-wrap-inner
    - .c-modal-body
      - button.c-modal-close-button[data-modal-close]
      - .c-modal-body-inner
        - ModalCommon[{id}] の内容
        - .c-modal-contents-buttons（close_buttons=true 時）
          - ButtonLink（閉じるボタン）
    - .c-modal-close-ovl[data-modal-close]

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：モーダルを設置する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('ModalCommon', [
  'id' => 'hogehoge',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  閉じるボタンを非表示にする（確認フォームなど）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('ModalCommon', [
  'id'            => 'confirm',
  'close_buttons' => false,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  プライバシーポリシー用モーダル
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('ModalCommon', [
  'id' => 'pp',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  モーダルを開くボタンとの組み合わせ例
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
// 開くボタン
C_Elements('ButtonLink', [
  'text' => 'モーダルを開く',
  'data' => [
    'modal-open' => 'hogehoge',
  ],
  'aria' => [
    'controls' => 'modal-hogehoge',
    'expanded' => 'false',
  ],
]);

// モーダル本体（MountModal.php などにまとめて設置）
C_Parts('ModalCommon', [
  'id' => 'hogehoge',
]);
?>
