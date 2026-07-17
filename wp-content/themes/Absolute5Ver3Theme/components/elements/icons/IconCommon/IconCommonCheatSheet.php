<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

IconCommon の Props 一覧
(components/elements/icons/IconCommon/IconCommon.php)

icon?: string
文字アイコン名、または画像ファイル名です。
svg が未指定のときに使用されます。

■ 文字アイコンとして使う例
- 'icon' => 'arrow_forward'
- 'icon' => 'close'
- 'icon' => 'download'

■ 画像として使う例
- 'icon' => 'sample.png'
- 'icon' => 'logo.webp'
- 'icon' => 'thumb.jpg'

※ 拡張子が png / jpg / jpeg / webp / gif / bmp の場合は
  画像として扱われます。
※ svg が指定されている場合は svg が最優先です。

--------------------------------

svg?: string
インラインSVGとして表示したいファイル名です。

例:
- 'svg' => 'arrow-right'
- 'svg' => 'close.svg'
- 'svg' => 'sns/x.svg'

内部では以下を参照します。
get_template_directory() . '/images/icons/' . $svg_file

※ 拡張子 .svg は省略可能です。
※ file_get_contents() で読み込み、
  img タグではなくインラインSVGとして出力されます。

--------------------------------

url?: string
指定すると a タグで出力されます。

例:
- 'url' => '/contact/'
- 'url' => 'https://example.com/'
- 'url' => '#section'

※ 未指定時は span または button 出力になります。

--------------------------------

button?: bool
true のとき button タグで出力されます。

例:
- 'button' => true

※ url がある場合は a タグが優先されます。
※ 未指定時は通常の span 出力です。

--------------------------------

span?: bool
true のとき <span class="c-icon-btn"> で囲んで出力されます。
url / button のいずれも未指定の場合に使います。

例:
- 'span' => true

--------------------------------

aria?: array
aria-* 属性をまとめて指定できます。

例:
'aria' => [
  'label' => 'メニューを閉じる',
  'controls' => 'drawer-menu',
  'expanded' => 'false',
]

上記は以下のように出力されます。

- aria-label="メニューを閉じる"
- aria-controls="drawer-menu"
- aria-expanded="false"

付与ルール:
- true → 属性名のみ付与
- '' / null → 付与しない
- それ以外 → aria-* 属性として付与

--------------------------------

size?: string
サイズ用の修飾クラスを付与します。

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
色用の修飾クラスを付与します。

例:
- 'color' => 'primary'
- 'color' => 'white'
- 'color' => 'danger'

出力例:
- _color-primary
- _color-white
- _color-danger

--------------------------------

出力タグの優先順位

- url がある → a
- button が true → button
- span が true → span
- それ以外 → ラップなし（アイコン span のみ直接出力）

--------------------------------

アイコンの描画ルール

- svg がある → インラインSVGを出力
- icon が画像拡張子つき → img を出力
- それ以外の icon → 文字列を出力

--------------------------------

クラスの付与ルール

基本クラス:
- c-icon

追加クラス:
- icon が文字列アイコンの場合 → _{icon名}
- size がある場合 → _size-{size}
- color がある場合 → _color-{color}

例:
- c-icon _close
- c-icon _download _size-l
- c-icon _arrow_forward _color-primary

--------------------------------

外部リンクの自動判定

url が絶対URLで、かつ home_url() を含まない場合は
外部リンクとみなされます。

外部リンク時は自動で以下が付きます。
- target="_blank"
- rel="noopener noreferrer"

また、aria['label'] がある場合は、
文末に補助文が自動補完されます。

- 通常外部リンク → 「へ遷移（別ウィンドウで開きます）」
- 内部リンク → 「へ遷移」

--------------------------------

aria-label の自動補完

a タグ出力時のみ、
aria['label'] が指定されていれば自動補完されます。

例:
'aria' => [
  'label' => 'お問い合わせ'
]

内部リンクの場合:
- aria-label="お問い合わせへ遷移"

外部リンクの場合:
- aria-label="お問い合わせへ遷移（別ウィンドウで開きます）"

※ すでに「へ遷移」を含む場合は追記しません。

--------------------------------

運用ルール（推奨）

- 装飾だけなら span 出力で使う
- クリック動作があるなら button => true
- 遷移するなら url を使う
- SVGを表示したいときは svg を使う
- 画像を表示したいときは icon に拡張子付きで渡す
- ボタンやリンクの意味づけには aria-label を付ける

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：文字アイコン
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'arrow_forward',
]);
?>

<?php
C_Elements('IconCommon', [
  'icon' => 'close',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  サイズ指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'arrow_forward',
  'size' => 's',
]);
?>

<?php
C_Elements('IconCommon', [
  'icon' => 'arrow_forward',
  'size' => 'l',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  色指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'arrow_forward',
  'color' => 'primary',
]);
?>

<?php
C_Elements('IconCommon', [
  'icon' => 'close',
  'color' => 'white',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  サイズ + 色指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'download',
  'size' => 'l',
  'color' => 'primary',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  SVGをインライン出力する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'svg' => 'arrow-right',
]);
?>

<?php
C_Elements('IconCommon', [
  'svg' => 'close.svg',
]);
?>

<?php
C_Elements('IconCommon', [
  'svg' => 'sns/x.svg',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  SVG + サイズ指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'svg' => 'arrow-right',
  'size' => 'm',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  画像アイコンを表示する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'sample.png',
]);
?>

<?php
C_Elements('IconCommon', [
  'icon' => 'logo.webp',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  リンクとして使う
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'arrow_forward',
  'url'  => '/contact/',
  'aria' => [
    'label' => 'お問い合わせ'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      外部リンクとして使う
  target="_blank" rel="noopener noreferrer" が自動付与
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'open_in_new',
  'url'  => 'https://example.com/',
  'aria' => [
    'label' => '外部サイト'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  SVG + リンク
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'svg'  => 'sns/x.svg',
  'url'  => 'https://example.com/',
  'aria' => [
    'label' => 'Xを見る'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  ボタンとして使う
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon'   => 'close',
  'button' => true,
  'aria'   => [
    'label' => '閉じる'
  ],
]);
?>

<?php
C_Elements('IconCommon', [
  'svg'    => 'menu.svg',
  'button' => true,
  'aria'   => [
    'label' => 'メニューを開く'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  aria属性を複数付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon'   => 'menu',
  'button' => true,
  'aria'   => [
    'label' => 'メニューを開閉',
    'controls' => 'global-nav',
    'expanded' => 'false',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  true の aria は属性名のみ出力
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon'   => 'info',
  'button' => true,
  'aria'   => [
    'hidden' => true,
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  url がある場合は button より a が優先される
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon'   => 'arrow_forward',
  'url'    => '/contact/',
  'button' => true,
  'aria'   => [
    'label' => 'お問い合わせ'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  span タグで囲む（装飾用）
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'icon' => 'info',
  'span' => true,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      何も指定しないと出力されない
  ※ icon も svg も空なら return
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('IconCommon', [
  'button' => true,
]);
?>