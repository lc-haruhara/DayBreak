<?php

/* ==========================================================
   Args
========================================================== */

$en    = trim($args['en'] ?? '');
$ja    = trim($args['ja'] ?? '');
$tag   = $args['tag'] ?? 'h2';
$class = $args['class'] ?? '';

/* ==========================================================
   タグのホワイトリスト検証
========================================================== */

$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'];

if (!in_array($tag, $allowed_tags, true)) {
  $tag = 'h2';
}

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-heading-section'];

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

$class_attr = esc_attr(implode(' ', $classes));

?>

<<?= esc_html($tag); ?> class="<?= $class_attr; ?>">
  <div class="c-heading-section-body">
    <?php if ($en !== ''): ?>
      <span class="c-heading-section-en" lang="en">
        <?= esc_html($en); ?>
      </span>
    <?php endif; ?>
    <?php if ($ja !== ''): ?>
      <span class="c-heading-section-ja">
        <span><?= esc_html($ja); ?></span>
      </span>
    <?php endif; ?>
  </div>
</<?= esc_html($tag); ?>>
