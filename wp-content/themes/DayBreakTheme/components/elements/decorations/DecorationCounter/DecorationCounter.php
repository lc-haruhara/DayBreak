<?php

/* ==========================================================
   Args
========================================================== */

$value    = $args['value'] ?? '';
$unit     = $args['unit'] ?? '';
$digits   = max(0, (int) ($args['digits'] ?? 0)); // 整数部の最小桁数。2 なら 00,01,02... と 0 埋めする
$duration = (int) ($args['duration'] ?? 1600);
$once     = $args['once'] ?? true;
$class    = $args['class'] ?? '';

/* ==========================================================
   数値の正規化
   カンマ付き文字列 (1,200) や小数も受け取れるようにする
========================================================== */

$raw = is_string($value) ? str_replace(',', '', $value) : $value;

// 負数はカウンターの用途外 (0 埋め・桁組みが成立しない)
if (!is_numeric($raw) || $raw < 0) {
  return;
}

$decimals = 0;
$dot_pos  = strpos((string) $raw, '.');

if ($dot_pos !== false) {
  $decimals = strlen(substr((string) $raw, $dot_pos + 1));
}

/* ==========================================================
   表示文字列の生成
   0 埋め → 3 桁区切り の順で組む (00,099 のようなオドメーター表記になる)
========================================================== */

$fixed     = number_format((float) $raw, $decimals, '.', '');
$parts     = explode('.', $fixed);
$int_part  = str_pad($parts[0], $digits, '0', STR_PAD_LEFT);
$frac_part = $parts[1] ?? '';

$display = strrev(implode(',', str_split(strrev($int_part), 3)));

if ($frac_part !== '') {
  $display .= '.' . $frac_part;
}

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-deco-counter'];

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

?>

<span
  class="<?= esc_attr(implode(' ', $classes)); ?>"
  data-js-scroll-target="<?= $once ? 'once' : ''; ?>"
  data-js-count-up
  data-digits="<?= esc_attr($digits); ?>"
  data-duration="<?= esc_attr($duration); ?>">
  <span class="c-deco-counter-number"><?= esc_html($display); ?></span>
  <?php if ($unit !== '') : ?>
    <span class="c-deco-counter-unit"><?= esc_html($unit); ?></span>
  <?php endif; ?>
</span>
