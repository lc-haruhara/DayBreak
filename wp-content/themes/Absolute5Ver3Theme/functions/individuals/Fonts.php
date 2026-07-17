<?php

/**
 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 *
 * フォント読み込みの生成ロジック（基本触らない）
 *
 * 設定は functions/config/fonts.config.php（唯一の編集対象）。
 * header.php の Fonts セクションで absolute5_render_fonts() を呼ぶ。
 *
 * 出力内容:
 *   - preconnect（googleapis / gstatic）
 *   - <style> :root{ --ff-base; --ff-sub; ... }（font-family の単一ソース）
 *   - テキストフォントの統合 <link>（+ preload:true があれば CSS を preload）
 *   - Material Symbols（icon_names で subset・media=print で非同期・noscript）
 *
 * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 */

/**
 * 設定配列を取得（1回だけ読み込んでキャッシュ）
 */
function absolute5_fonts_config()
{
  static $config = null;

  if ($config === null) {
    $config = require get_template_directory() . '/functions/config/fonts.config.php';
  }

  return $config;
}

/**
 * フォント名を CSS の font-family トークンに変換
 * 総称ファミリ（sans-serif 等）は素のまま、それ以外は引用符で囲む
 */
function absolute5_font_quote($name)
{
  $generic = [
    'serif', 'sans-serif', 'monospace', 'cursive', 'fantasy',
    'system-ui', 'ui-sans-serif', 'ui-serif', 'ui-monospace', 'ui-rounded',
  ];

  if (in_array($name, $generic, true)) {
    return $name;
  }

  return '"' . $name . '"';
}

/**
 * family_stacks を :root の CSS変数として出力
 * '__ja__' は ja_fallback に展開する
 */
function absolute5_fonts_css_vars()
{
  $config = absolute5_fonts_config();
  $ja     = isset($config['ja_fallback']) ? $config['ja_fallback'] : [];
  $decls  = [];

  foreach ($config['family_stacks'] as $key => $stack) {
    $tokens = [];

    foreach ($stack as $item) {
      if ($item === '__ja__') {
        foreach ($ja as $ja_name) {
          $tokens[] = absolute5_font_quote($ja_name);
        }
      } else {
        $tokens[] = absolute5_font_quote($item);
      }
    }

    $decls[] = '--ff-' . $key . ':' . implode(',', $tokens) . ';';
  }

  echo '<style id="absolute5-font-vars">:root{' . implode('', $decls) . '}</style>' . "\n";
}

/**
 * テキストフォントの Google Fonts 統合 URL を生成
 * 例: https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Poppins:wght@400;700&display=swap
 */
function absolute5_text_fonts_url()
{
  $config   = absolute5_fonts_config();
  $families = [];

  foreach ($config['text_fonts'] as $font) {
    $name    = str_replace(' ', '+', $font['name']);
    $weights = isset($font['weights']) ? $font['weights'] : [400];
    sort($weights);

    $families[] = 'family=' . $name . ':wght@' . implode(';', $weights);
  }

  return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
}

/**
 * Material Symbols の Google Fonts URL を生成（icon_names で subset）
 */
function absolute5_material_symbols_url()
{
  $config = absolute5_fonts_config();
  $ms     = $config['material_symbols'];

  $family  = str_replace(' ', '+', $ms['family']);
  $axis    = $ms['axis'];
  $display = isset($ms['display']) ? $ms['display'] : 'block';

  $icons = array_values(array_unique($ms['icons']));
  sort($icons);

  return 'https://fonts.googleapis.com/css2'
    . '?family=' . $family . ':' . $axis
    . '&icon_names=' . implode(',', $icons)
    . '&display=' . $display;
}

/**
 * header.php の Fonts セクションから呼ぶ公開関数
 * フォント関連の head 出力を 1 箇所にまとめて生成する
 */
function absolute5_render_fonts()
{
  // preconnect
  echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";

  // font-family の CSS変数（単一ソース）
  absolute5_fonts_css_vars();

  // テキストフォント（同期・display=swap）
  $text_url    = absolute5_text_fonts_url();
  $has_preload = false;
  foreach (absolute5_fonts_config()['text_fonts'] as $font) {
    if (!empty($font['preload'])) {
      $has_preload = true;
      break;
    }
  }
  if ($has_preload) {
    echo '<link rel="preload" as="style" href="' . esc_url($text_url) . '" />' . "\n";
  }
  echo '<link rel="stylesheet" href="' . esc_url($text_url) . '" />' . "\n";

  // Material Symbols（subset・media=print で非同期読み込み）
  $ms_url = absolute5_material_symbols_url();
  echo '<link rel="preload" as="style" href="' . esc_url($ms_url) . '" />' . "\n";
  echo '<link rel="stylesheet" href="' . esc_url($ms_url) . '" media="print" data-async-css />' . "\n";
  echo '<noscript><link rel="stylesheet" href="' . esc_url($ms_url) . '" /></noscript>' . "\n";
  echo '<script>(function(){var l=document.querySelectorAll("link[data-async-css]");var s=function(e){e.media="all";};for(var i=0;i<l.length;i++){(function(e){if(e.sheet){s(e);}else{e.addEventListener("load",function(){s(e);},{once:true});}})(l[i]);}})();</script>' . "\n";
}
