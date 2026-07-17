<?php

/**
 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 *
 * フォント設定（単一ソース）
 *
 * ここ（配列）を編集すれば、header の <link> / preload /
 * CSS変数（--ff-*） / Material Symbols の icon_names URL が
 * すべて自動生成される。出力ロジックは
 * functions/individuals/Fonts.php（基本触らない）。
 *
 * 参照: AbsoluteFiveAstroForMicroCMS の fonts.config.ts 相当。
 *
 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 */

return [

  //テキストフォント（Google Fonts CDN で読み込む）
  //    preload: 巨大な日本語フォント等は false（優先読み込みしない）
  'text_fonts' => [
    ['name' => 'Noto Sans JP', 'weights' => [400, 700], 'preload' => false],
    ['name' => 'Poppins',      'weights' => [400, 700], 'preload' => true],
    ['name' => 'Oswald',       'weights' => [400, 700], 'preload' => true],
  ],

  //日本語フォールバックチェーン（family_stacks の '__ja__' で展開）
  'ja_fallback' => [
    'Noto Sans JP',
    'dashicons',
    'ヒラギノ角ゴ Pro W3',
    'Hiragino Kaku Gothic Pro',
    'メイリオ',
    'Meiryo',
    'ＭＳ Ｐゴシック',
    'sans-serif',
  ],

  //font-family スタック → :root の CSS変数として出力
  //    キー 'base' は変数名 --ff-base に対応（SCSS の $font-family-map から参照）
  //    '__ja__' は上の ja_fallback に展開される
  'family_stacks' => [
    'base' => ['Poppins', '__ja__'],
    'sub'  => ['Oswald', '__ja__'],
  ],

  //Material Symbols（アイコンフォント）
  //    icons に列挙したアイコンだけを icon_names= で subset 読み込みする。
  //    ※ 列挙し忘れたアイコンはエラーも出さず表示されないので注意。
  //    axis はフロントの IconMtStyle mixin（opsz 24 / GRAD 0 等）に合わせフル指定を維持。
  //    display は 'block'（アイコン用。'swap' だと読込前にリガチャ名が一瞬見える）。
  'material_symbols' => [
    'family'  => 'Material Symbols Outlined',
    'axis'    => 'opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
    'display' => 'block',
    'icons'   => [
      'ad',
      'arrow_outward',
      'call',
      'check',
      'chevron_forward',
      'close',
      'download',
      'error',
      'keyboard_arrow_down',
      'mail',
      'open_in_new',
      'send',
      'web_asset',
      'bucket_check', // デフォルトトップの pages/top/00sample.php で使用（トップ差し替え時は削除可）
      // 開発用チートシートでのみ使用（本番描画に不要・必要なら有効化）:
      // 'menu', 'info', 'arrow_forward',
    ],
  ],

];
