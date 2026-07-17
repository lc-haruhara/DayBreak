<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

TextLoop の Props 一覧
(components/elements/decorations/TextLoop/TextLoop.php)

text: string | string[]
ループさせる表示テキストです。
配列を渡すと、各要素が span で包まれ、半角スペース区切りで並びます。

■ string の場合
- 'text' => 'DayBreak'
- 'text' => 'お知らせ'

■ string[] の場合
- 'text' => ['Every Daybreak', 'Begins in Okinawa']

出力例:
<span class="c-text-loop-text" lang="en">
  <span data-text="Every Daybreak">Every Daybreak</span> <span data-text="Begins in Okinawa">Begins in Okinawa</span>
</span>

※ 未指定・空文字の場合は何も出力されません。
※ 配列内の空文字要素は無視されます。すべて空なら出力されません。
※ 半角英字のみの場合は自動で lang="en" が付きます（全要素をつなげて判定）。
※ 内側の span にはクラスは付かず、data-text 属性に自身のテキストが入ります。
  content: attr(data-text) での擬似要素の重ねなどに使えます。
※ 個別に装飾したい場合は .c-text-loop-text > span や :nth-child() で指定してください。

--------------------------------

direction?: string
流れる向きです。

指定値:
- 'minus' → 左方向（マイナスX方向）へ流れる
- 'plus'  → 右方向（プラスX方向）へ流れる

例:
- 'direction' => 'minus'
- 'direction' => 'plus'

※ 未指定時は 'minus'（左方向）です。
※ 不正な値が入った場合も 'minus' になります。
※ 出力クラス: _dir-minus / _dir-plus

--------------------------------

speed?: int | float
1ループにかかる秒数です。
値が大きいほどゆっくり流れます。

例:
- 'speed' => 10   → 速い
- 'speed' => 20   → 標準
- 'speed' => 60   → ゆっくり

※ 未指定時は 20 です。
※ 最小値は 1 に丸められます。
※ CSS変数 --text-loop-duration として出力されます。

--------------------------------

repeat?: int
1グループ内で .c-text-loop-text を何回繰り返すかです。
短いテキストで隙間が空く場合に増やします。

※ text が配列の場合も、配列全体で1つの .c-text-loop-text です。
  例: 'text' => ['A', 'B'], 'repeat' => 3 → 「A B」が3つ並ぶ

例:
- 'repeat' => 2
- 'repeat' => 4
- 'repeat' => 8

※ 未指定時は 4 です。
※ 最小値は 1 に丸められます。
※ 画面幅を埋めるだけの数を指定してください。

--------------------------------

gap?: string
.c-text-loop-text 同士の間隔です。CSSの長さで指定します。
（配列内の span 同士の間隔ではありません。そちらは半角スペース固定です）

例:
- 'gap' => '1em'
- 'gap' => '40px'
- 'gap' => '2rem'

※ 未指定時は 0.5em です。
※ CSS変数 --text-loop-gap として出力されます。

--------------------------------

id?: string
id 属性を付けます。

例:
- 'id' => 'hero-text-loop'

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

出力例:
- _color-primary
- _color-white

--------------------------------

data?: array
data-* 属性をまとめて指定できます。

例:
'data' => [
  'js-scroll-target' => true,
  'state' => 'active',
]

出力例:
- data-js-scroll-target
- data-state="active"

付与ルール:
- true → 属性名のみ出力
- '' / null → 出力しない
- それ以外 → data-* 属性として出力

--------------------------------

クラスの付与ルール

基本クラス:
- c-text-loop

追加クラス:
- _dir-{direction}
- _size-{size}
- _color-{color}
- class で渡した独自クラス

例:
- c-text-loop _dir-minus
- c-text-loop _dir-plus _size-l _color-white

--------------------------------

内部構造

内部は以下のような構造で出力されます。

- .c-text-loop            … overflow: hidden の枠
  - .c-text-loop-track      … アニメーションする帯（同一グループを2つ持つ）
    - .c-text-loop-group    … repeat 回数分のテキストのまとまり
      - .c-text-loop-text   … repeat 1回につき1つ
        - span              … text が配列なら要素数分 / data-text 付き
      - .c-text-loop-text
    - .c-text-loop-group    … 上と同一内容の複製

--------------------------------

仕組み

- track に同じグループを2つ並べ、-50% まで移動させることで継ぎ目なくループします。
- そのためグループの複製数は常に2固定です。
  隙間を埋めたい場合は repeat を増やしてください。

--------------------------------

アクセシビリティ

- 装飾用コンポーネントのため .c-text-loop に aria-hidden="true" が付きます。
  同じテキストが2回読み上げられることはありません。
- 読み上げさせたいテキストには使用しないでください。
- prefers-reduced-motion: reduce ではアニメーションが停止します。

--------------------------------

運用ルール（推奨）

- 装飾の流れる文字として使う
- 速度を変えたいときだけ speed を指定する
- 短いテキストで隙間が空くときは repeat を増やす
- 文字同士の間隔を調整したいときだけ gap を指定する

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：左方向（マイナスX方向）へ流れる
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text' => 'DayBreak',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  配列を渡して span で区切る
  <span>Every Daybreak</span> <span>Begins in Okinawa</span>
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text' => ['Every Daybreak', 'Begins in Okinawa'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  右方向（プラスX方向）へ流れる
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'      => 'DayBreak',
  'direction' => 'plus',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  左方向を明示する
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'      => 'RECRUIT',
  'direction' => 'minus',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  速度を変える
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'  => 'DayBreak',
  'speed' => 10,
]);
?>

<?php
C_Elements('TextLoop', [
  'text'  => 'DayBreak',
  'speed' => 60,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  繰り返し数を増やして隙間を埋める
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'   => 'NEWS',
  'repeat' => 8,
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  文字同士の間隔を変える
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text' => 'DayBreak',
  'gap'  => '2rem',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  size / color 指定
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'  => 'DayBreak',
  'size'  => 'l',
  'color' => 'white',
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  id / class を付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'  => 'DayBreak',
  'id'    => 'hero-text-loop',
  'class' => ['is-large', 'is-primary'],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  data属性をまとめて付ける
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text' => 'DayBreak',
  'data' => [
    'js-scroll-target' => true,
    'state'            => 'active',
  ],
]);
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  上下で逆方向に流す
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Elements('TextLoop', [
  'text'      => 'DayBreak',
  'direction' => 'minus',
  'speed'     => 30,
]);
?>

<?php
C_Elements('TextLoop', [
  'text'      => 'DayBreak',
  'direction' => 'plus',
  'speed'     => 30,
]);
?>
