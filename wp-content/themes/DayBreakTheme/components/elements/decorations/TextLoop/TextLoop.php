<?php

/* ==========================================================
   Args
========================================================== */

$text      = $args['text'] ?? '';
$direction = $args['direction'] ?? 'minus';
$speed     = $args['speed'] ?? 20;
$repeat    = $args['repeat'] ?? 4;
$gap       = $args['gap'] ?? '';
$id        = $args['id'] ?? '';
$class     = $args['class'] ?? '';
$size      = $args['size'] ?? '';
$color     = $args['color'] ?? '';
$data      = $args['data'] ?? [];

/* ==========================================================
   text の正規化
   string / string[] のどちらでも受け取れる
========================================================== */

$texts = [];

foreach (is_array($text) ? $text : [$text] as $t) {
  $t = trim((string) $t);

  if ($t !== '') {
    $texts[] = $t;
  }
}

if (!$texts) return;

if (!in_array($direction, ['plus', 'minus'], true)) {
  $direction = 'minus';
}

$speed = max(1, (float) $speed);

$repeat = max(1, (int) $repeat);

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-text-loop'];

$classes[] = '_dir-' . $direction;

if ($size !== '') {
  $classes[] = '_size-' . sanitize_html_class($size);
}

if ($color !== '') {
  $classes[] = '_color-' . sanitize_html_class($color);
}

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

/* ==========================================================
   属性生成
========================================================== */

$attrs = [];

if ($id !== '') {
  $attrs[] = 'id="' . esc_attr($id) . '"';
}

$attrs[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';

$styles = ['--text-loop-duration:' . $speed . 's'];

if ($gap !== '') {
  $styles[] = '--text-loop-gap:' . $gap;
}

$attrs[] = 'style="' . esc_attr(implode(';', $styles)) . '"';

if (is_array($data)) {
  foreach ($data as $key => $value) {

    if ($value === true) {
      $attrs[] = 'data-' . esc_attr($key);
      continue;
    }

    if ($value === '' || $value === null) {
      continue;
    }

    $attrs[] = 'data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
  }
}

/* ==========================================================
   グループ生成
   track には同一グループを2つ並べ、-50% 移動で継ぎ目なくループさせる
========================================================== */

// 表示テキスト全体（配列なら半角スペース区切りで連結）
$text_value = implode(' ', $texts);

// 半角英字のみなら lang="en" を付ける
$text_lang = preg_match('/^[A-Za-z]+(?:[ \-\'&]+[A-Za-z]+)*$/', $text_value) ? 'en' : '';

// 各テキストを span で包み、半角スペースで区切る
$inner_html = implode(' ', array_map(
  fn($t) => '<span data-text="' . esc_attr($t) . '">' . esc_html($t) . '</span>',
  $texts
));

ob_start(); ?>

<span class="c-text-loop-group">
  <?php for ($i = 0; $i < $repeat; $i++): ?>
    <span
      class="c-text-loop-text"
      <?php if ($text_lang !== ''): ?>
      lang="<?= esc_attr($text_lang); ?>"
      <?php endif; ?>>
      <?= $inner_html; ?>
    </span>
  <?php endfor; ?>
</span>

<?php
$group_html = ob_get_clean();
?>

<div <?= implode(' ', $attrs); ?> aria-hidden="true">
  <div class="c-text-loop-track">
    <?= $group_html; ?>
    <?= $group_html; ?>
  </div>
</div>