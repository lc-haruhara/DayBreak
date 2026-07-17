<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

Link の Props 一覧
(components/elements/links/Link/Link.php)

text?: string
リンク本文のテキストです。
html が未指定のときに使われます。

例:
- 'text' => 'お問い合わせ'
- 'text' => '会社概要'
- 'text' => '資料ダウンロード'

--------------------------------

html?: string
リンク本文を HTML で渡したいときに使います。
指定時は text より優先されます。

例:
- 'html' => '詳しくは <strong>こちら</strong>'
- 'html' => '<em>Read more</em>'

※ 内部では wp_kses_post() で出力されます。

--------------------------------

href?: string
リンク先URLを直接指定します。
page が未指定のときに使われます。

主な用途:
- 外部リンク
- サイト内URLの直接指定
- アンカーリンク
- 電話リンク
- メールリンク
- ダウンロードリンク

例:
- 'href' => '/contact/'
- 'href' => 'https://example.com/'
- 'href' => '#section'
- 'href' => 'tel:03-1234-5678'
- 'href' => 'mailto:info@example.com'
- 'href' => get_template_directory_uri() . '/images/pdf/sample.pdf'

--------------------------------

page?: int|string
サイト内ページを指定します。
指定すると href より優先されます。

■ 数値の場合
固定ページIDなどを指定します。

例:
- 'page' => 10
- 'page' => 123

■ 文字列の場合
slug として解決します。

例:
- 'page' => 'contact'
- 'page' => 'about'

※ page が指定されている場合は href より優先されます。
※ 数値 ID は get_permalink() で、スラッグは get_page_by_path() で解決します。

--------------------------------

contentLang?: string
リンク本文の言語を明示したいときに指定します。
未指定時は本文テキストから自動判定します。

例:
- 'contentLang' => 'ja'
- 'contentLang' => 'en'

主な用途:
- 英語表記を確実に英語として扱いたい
- 自動判定に任せたくない場合

--------------------------------

showIcon?: bool
リンク末尾の補助アイコンを表示するかどうかです。
未指定時はリンク種別に応じて自動表示されます。

例:
- 'showIcon' => false
- 'showIcon' => true

自動表示の対象:
- mailto:
- tel:
- 外部リンク
- download 指定あり

--------------------------------

icon?: string
表示アイコンを明示指定します。

例:
- 'icon' => 'open_in_new'
- 'icon' => 'download'
- 'icon' => 'mail'
- 'icon' => 'call'

※ showIcon が false の場合は表示されません。
※ svg が指定されている場合は svg が最優先です。

--------------------------------

svg?: string
表示する svg を明示指定します。
指定されている場合は最優先で使用されます。

例:
- 'svg' => 'external-link'
- 'svg' => 'pdf'
- 'svg' => 'logo-mark'

--------------------------------

class?: string | string[]
追加クラスです。

- string の場合はそのまま追加
- string[] の場合は各要素を追加

例:
- 'class' => 'my-link'
- 'class' => ['my-link', 'is-large']

--------------------------------

data?: array
data-* 属性のまとめ指定です。

例:
'data' => [
  'action' => 'open-modal',
  'state' => 'closed',
  'drawer-close' => true,
]

上記は以下のように出力されます。

- data-action="open-modal"
- data-state="closed"
- data-drawer-close

付与ルール:
- true → 属性名のみ付与
- false / null / '' → 付与しない
- それ以外 → 文字列化して付与

--------------------------------

aria?: array
aria-* 属性のまとめ指定です。

例:
'aria' => [
  'label' => 'お問い合わせページへ移動',
  'describedby' => 'contact-help',
]

上記は以下のように出力されます。

- aria-label="お問い合わせページへ移動"
- aria-describedby="contact-help"

付与ルール:
- true → 属性名のみ付与
- false / null / '' → 付与しない
- それ以外 → 文字列化して付与

※ mail / tel / 外部リンク / download の場合は、
  補助文を sr-only で追加する設計なので
  aria-label の競合回避のため内部で調整されます。

--------------------------------

attrs?: array
その他の属性をまとめて渡すための配列です。

例:
'attrs' => [
  'id' => 'contact-link',
  'title' => 'お問い合わせページへ移動',
]

上記は以下のように出力されます。

- id="contact-link"
- title="お問い合わせページへ移動"

--------------------------------

download?: bool|string
a タグ時の download 属性です。

例:
- 'download' => true
- 'download' => 'document.pdf'

--------------------------------

タグの自動判定

- href または page が指定されている → <a> タグ（class="c-link"）
- どちらも未指定 → <button type="button"> タグ（class="c-button"）

--------------------------------

page / href の優先順位

- page が指定されている場合は page を優先
- page が未指定の場合は href を使用
- 両方未指定なら button タグで出力（href は "#" にフォールバックしない）

--------------------------------

リンク種別の自動判定

href / page から解決されたリンク先に応じて、
以下が自動判定されます。

- メールリンクか
- 電話リンクか
- 外部リンクか
- 現在ページか
- download か

--------------------------------

target / rel の自動付与

外部リンクと判定された場合は自動で以下が付きます。

- target="_blank"
- rel="noopener noreferrer"

※ mailto: / tel: / #anchor は外部リンク扱いしません。

--------------------------------

aria-current の自動付与

現在ページと一致する場合は自動で以下が付きます。

- aria-current="page"

--------------------------------

リンク本文の lang 自動判定

text / html から抽出した本文テキストをもとに、
必要に応じて span に lang 属性が付きます。

例:
- 日本語中心 → lang="ja"
- 英語中心 → lang="en"

※ contentLang 指定時はそちらが優先されます。

--------------------------------

スクリーンリーダー向け補助文の自動付与

リンク種別に応じて、sr-only テキストが自動で追加されます。

追加される補助:
- mailto: → メールリンクです
- tel: → 電話リンクです
- 外部リンク → 新しいタブで開きます
- download → ファイルをダウンロードします

--------------------------------

運用ルール（推奨）

- サイト内リンク → page を使う
- 外部リンク / mail / tel / download / anchor → href を使う
- アイコンを消したい時だけ showIcon => false
- アイコンを固定したい時は icon または svg を使う
- data-* を複数付けたい時は data を使う
- id / title などその他属性は attrs を使う

::::::::::::::::::::::::::::::::::::::::::::
*/
?>



<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  href / page なし → button タグ出力
  class="c-button" になる
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '閉じる',
  'data' => [
    'modal-close' => true,
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      基本：通常の内部リンク
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => '/contact/',
]);
?>

<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'page' => 'contact',
]);
?>

<?php
C_Elements('Link', [
  'text' => '会社概要',
  'page' => 123,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      page が href より優先される
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'page' => 'contact',
  'href' => '/will-be-ignored/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      外部リンク
      自動で target="_blank" rel="noopener noreferrer"
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '外部サイトを見る',
  'href' => 'https://example.com/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      外部リンク + アイコン明示
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '外部サイトを見る',
  'href' => 'https://example.com/',
  'icon' => 'open_in_new',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      補助アイコンを消したい場合
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '外部サイトを見る',
  'href' => 'https://example.com/',
  'showIcon' => false,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      SVGアイコンを明示したい場合
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '外部サイトを見るSVGアイコン',
  'href' => 'https://example.com/',
  'svg' => 'sns/color/x.svg',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      アンカーリンク
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'セクションへ移動',
  'href' => '#section',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      メールリンク
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'メールで問い合わせる',
  'href' => 'mailto:info@example.com',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      電話リンク
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '03-1234-5678',
  'href' => 'tel:03-1234-5678',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      ダウンロードリンク
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => '会社案内PDF',
  'href' => get_template_directory_uri() . '/images/pdf/company.pdf',
  'download' => true,
]);
?>

<?php
C_Elements('Link', [
  'text' => '会社案内PDF',
  'href' => get_template_directory_uri() . '/images/pdf/company.pdf',
  'download' => 'company-profile.pdf',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      class を追加する
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => '/contact/',
  'class' => 'my-link',
]);
?>

<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => '/contact/',
  'class' => ['my-link', 'is-large'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      data-* 属性をまとめて付ける
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'モーダルを開く',
  'data' => [
    'modal-open' => 'hogehoge'
  ],
  'aria'  => [
    'controls' => 'modal-hogehoge',
    'expanded' => 'false',
    'label'    => 'Open hogehoge modal'
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      aria 属性をまとめて付ける
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => '/contact/',
  'aria' => [
    'label' => 'お問い合わせページへ移動',
    'describedby' => 'contact-help',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      その他の属性を attrs で付ける
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => '/contact/',
  'attrs' => [
    'id' => 'contact-link',
    'title' => 'お問い合わせページへ移動',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      本文言語を明示する
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'Company Profile',
  'href' => '/company/',
  'contentLang' => 'en',
]);
?>

<?php
C_Elements('Link', [
  'text' => '会社概要',
  'href' => '/company/',
  'contentLang' => 'ja',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      HTMLを本文にしたい場合
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'html' => '詳しくは <strong>こちら</strong>',
  'href' => '/guide/',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
      mail / tel / external / download は
      スクリーンリーダー向け補助文が自動付与される
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('Link', [
  'text' => 'お問い合わせ',
  'href' => 'mailto:info@example.com',
]);
?>

<?php
C_Elements('Link', [
  'text' => 'お電話はこちら',
  'href' => 'tel:03-1234-5678',
]);
?>

<?php
C_Elements('Link', [
  'text' => '外部サイトを見る',
  'href' => 'https://example.com/',
]);
?>

<?php
C_Elements('Link', [
  'text' => '資料ダウンロード',
  'href' => '/files/sample.pdf',
  'download' => true,
]);
?>