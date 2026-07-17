<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

ButtonLink の Props 一覧
(components/elements/buttons/ButtonLink/ButtonLink.php)

url?: string
リンク先です。
指定がある場合は a タグで出力されます。

例:
- 'url' => '/contact/'
- 'url' => 'https://example.com/'
- 'url' => 'info@example.com'
- 'url' => 'mailto:info@example.com'
- 'url' => '03-1234-5678'
- 'url' => 'tel:03-1234-5678'

自動判定:
- メールアドレス → mailto:
- 電話番号 → tel:
- 外部URL → target="_blank" / rel 付与
- 相対パス → home_url() 付きのURLに補完

※ label が指定されている場合は url より label 出力が優先されます。

--------------------------------

text?: string
ボタンやリンクの表示テキストです。

例:
- 'text' => '詳しく見る'
- 'text' => 'お問い合わせ'
- 'text' => '資料請求'

※ 未指定時は 'more' が入ります。

--------------------------------

label?: string
指定すると label タグで出力されます。
値は for 属性に使われます。

例:
- 'label' => 'drawer-toggle'
- 'label' => 'search-input'
- 'label' => 'checkbox-agree'

出力例:
- <label for="drawer-toggle">...</label>

優先順位:
- label がある → label タグ
- label がなく url がある → a タグ
- それ以外 → button タグ

--------------------------------

id?: string
id 属性を付けます。

例:
- 'id' => 'contact-button'
- 'id' => 'global-nav-toggle'

--------------------------------

class?: string | string[]
追加クラスです。

■ string の場合
- 'class' => 'is-large'

■ string[] の場合
- 'class' => ['is-large', 'is-primary']

--------------------------------

size?: string
サイズ用の修飾クラスを付けます。

例:
- 'size' => 's'
- 'size' => 'm'
- 'size' => 'l'

出力例:
- _size-s
- _size-m
- _size-l

--------------------------------

color?: string
色用の修飾クラスを付けます。

例:
- 'color' => 'primary'
- 'color' => 'white'
- 'color' => 'black'

出力例:
- _color-primary
- _color-white
- _color-black

--------------------------------

icon?: string
アイコン名です。
指定すると内部で IconCommon を呼び出して
ボタン内にアイコンが出力されます。

例:
- 'icon' => 'arrow'
- 'icon' => 'close'
- 'icon' => 'download'

※ アイコンは .c-btn-link-icon 内に出力されます。

--------------------------------

svg?: string
SVGファイル名（拡張子なし可）を指定します。
`images/icons/` 配下のSVGファイルをインライン展開します。
icon と svg は同時に指定可能ですが、svg が優先されます。

例:
- 'svg' => 'logo'
- 'svg' => 'logo.svg'

出力例:
- .c-btn-link-icon 内に SVG がインライン展開される

--------------------------------

icon_position?: string
アイコン位置です。

指定値:
- 'left'
- 'right'

例:
- 'icon_position' => 'left'
- 'icon_position' => 'right'

※ 未指定時は 'right' です。
※ icon がある場合は自動で以下のクラスが付きます。
- _icon-left
- _icon-right

--------------------------------

data?: array
data-* 属性をまとめて指定できます。

例:
'data' => [
  'modal-open' => 'contact',
  'state' => 'closed',
  'drawer-close' => true,
]

出力例:
- data-modal-open="contact"
- data-state="closed"
- data-drawer-close

付与ルール:
- true → 属性名のみ出力
- '' / null → 出力しない
- それ以外 → data-* 属性として出力

--------------------------------

aria?: array
aria-* 属性をまとめて指定できます。

例:
'aria' => [
  'label' => 'お問い合わせを開く',
  'controls' => 'contact-modal',
  'expanded' => 'false',
]

出力例:
- aria-label="お問い合わせを開く"
- aria-controls="contact-modal"
- aria-expanded="false"

付与ルール:
- true → 属性名のみ出力
- '' / null → 出力しない
- それ以外 → aria-* 属性として出力

--------------------------------

タグの自動判定

以下の優先順位でタグが決まります。

- label がある → label
- label がなく、url がある → a
- それ以外 → button

--------------------------------

aタグ時の自動処理

url がある場合は a タグになります。

自動判定内容:
- 電話番号なら tel: に変換
- メールアドレスなら mailto: に変換
- 外部リンクなら target="_blank" を付与
- 外部リンクなら rel="noopener noreferrer external" を付与
- 相対パスなら home_url() 付きURLに補完

--------------------------------

buttonタグ時の自動処理

button タグ時は自動で以下が付きます。

- type="button"

--------------------------------

labelタグ時の自動処理

label 指定時は自動で以下が付きます。

- for="{labelの値}"

--------------------------------

クラスの付与ルール

基本クラス:
- c-btn-link

追加クラス:
- _size-{size}
- _color-{color}
- _icon-{icon_position}
- class で渡した独自クラス

例:
- c-btn-link _size-l _color-primary
- c-btn-link _icon-left
- c-btn-link _size-m _color-black is-wide

--------------------------------

内部構造

内部は以下のような構造で出力されます。

- .c-btn-link
  - .c-btn-link-body
    - .c-btn-link-icon
    - .c-btn-link-text

--------------------------------

運用ルール（推奨）

- ページ遷移させたい → url を使う
- JS動作用のトリガー → url なしで button にする
- input と紐付けたい → label を使う
- data 属性を複数付けたい → data を使う
- aria 属性を複数付けたい → aria を使う
- アイコン位置を変えたいときだけ icon_position を指定する

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：button出力
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '詳しく見る',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text' => 'お問い合わせ',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  text未指定時は more
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', []);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  内部リンク
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'お問い合わせ',
  'url'  => '/contact/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  外部リンク
target="_blank" rel="noopener noreferrer external" が付く
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '外部サイトを見る',
  'url'  => 'https://example.com/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  メールリンク
自動で mailto: になる
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'メールで問い合わせる',
  'url'  => 'info@example.com',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text' => 'メールで問い合わせる',
  'url'  => 'mailto:info@example.com',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  電話リンク
自動で tel: になる
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '電話する',
  'url'  => '03-1234-5678',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text' => '電話する',
  'url'  => 'tel:03-1234-5678',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  label出力
label が最優先
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text'  => 'メニューを開く',
  'label' => 'drawer-toggle',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text'  => '検索フォームを開く',
  'label' => 'search-toggle',
  'url'   => '/will-be-ignored/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  id を付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'お問い合わせ',
  'id'   => 'contact-button',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  class を追加する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text'  => '詳しく見る',
  'class' => 'is-large',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text'  => '詳しく見る',
  'class' => ['is-large', 'is-primary'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  size 指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '詳しく見る',
  'size' => 's',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text' => '詳しく見る',
  'size' => 'l',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  color 指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text'  => '詳しく見る',
  'color' => 'primary',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text'  => '詳しく見る',
  'color' => 'white',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  icon を付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '詳しく見る',
  'icon' => 'arrow',
]);
?>

<?php
C_Elements('ButtonLink', [
  'text' => '閉じる',
  'icon' => 'close',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  icon を左にする
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '戻る',
  'icon' => 'arrow',
  'icon_position' => 'left',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  icon を右にする
※未指定でも right
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '詳しく見る',
  'icon' => 'arrow',
  'icon_position' => 'right',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  size + color + icon
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '資料請求',
  'size' => 'l',
  'color' => 'primary',
  'icon' => 'download',
  'icon_position' => 'right',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  data属性をまとめて付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'モーダルを開く',
  'data' => [
    'modal-open' => 'contact',
    'state' => 'closed',
    'drawer-close' => true,
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  aria属性をまとめて付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'メニューを開く',
  'aria' => [
    'label' => 'グローバルメニューを開く',
    'controls' => 'global-nav',
    'expanded' => 'false',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  data + aria を併用する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => 'モーダルを開く',
  'icon' => 'arrow',
  'data' => [
    'modal-open' => 'contact',
  ],
  'aria' => [
    'controls' => 'modal-contact',
    'expanded' => 'false',
    'label' => 'お問い合わせモーダルを開く',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  label + aria
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text'  => '検索を開く',
  'label' => 'search-toggle',
  'icon'  => 'search',
  'aria'  => [
    'label' => '検索フォームを開く',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  button として使い、JSトリガーにする
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('ButtonLink', [
  'text' => '閉じる',
  'icon' => 'close',
  'data' => [
    'modal-close' => true,
  ],
  'aria' => [
    'label' => 'モーダルを閉じる',
  ],
]);
?>