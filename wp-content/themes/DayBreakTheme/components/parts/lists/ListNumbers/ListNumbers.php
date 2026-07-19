<?php

/* ==========================================================
   表示データ
========================================================== */

// icon は images/lottie/ 配下の JSON ファイル名を指定する
$items = [
  ['icon' => 'icon-recruit-store.json', 'title' => '国内店舗数', 'value' => 99, 'unit' => '店舗', 'digits' => 2],
  ['icon' => 'icon-recruit-store.json', 'title' => '従業員数', 'value' => 18, 'unit' => '人', 'digits' => 2],
  ['icon' => 'icon-recruit-store.json', 'title' => '平均年齢', 'value' => 28, 'unit' => '歳', 'digits' => 2],
  ['icon' => 'icon-recruit-store.json', 'title' => '海外店舗数', 'value' => 99, 'unit' => '店舗', 'digits' => 2],
];

/* ==========================================================
   Args
========================================================== */

$class = $args['class'] ?? '';

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-list-numbers'];

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

?>

<dl class="<?= esc_attr(implode(' ', $classes)); ?>">
  <?php foreach ($items as $item) : ?>
    <?php
    $icon  = $item['icon'] ?? '';
    $title = $item['title'] ?? '';
    ?>
    <div class="c-list-numbers-item" data-js-scroll-target>
      <!--::::::::::::::::::::::::::::::::::::::::::::
        Icon
      ::::::::::::::::::::::::::::::::::::::::::::-->
      <div class="c-list-numbers-item-icon">
        <?php if ($icon !== '') : ?>
          <div
            class="c-list-numbers-item-icon-body"
            data-js-lottie
            data-lottie-reverse
            data-lottie-src="<?= esc_url(get_template_directory_uri() . '/images/lottie/' . $icon); ?>"></div>
        <?php endif; ?>
      </div>
      <!--::::::::::::::::::::::::::::::::::::::::::::
        Title
      ::::::::::::::::::::::::::::::::::::::::::::-->
      <dt class="c-list-numbers-item-title">
        <?= esc_html($title); ?>
      </dt>
      <!--::::::::::::::::::::::::::::::::::::::::::::
        Value
      ::::::::::::::::::::::::::::::::::::::::::::-->
      <dd class="c-list-numbers-item-value">
        <?php
        C_Elements('DecorationCounter', [
          'value'  => $item['value'] ?? 0,
          'unit'   => $item['unit'] ?? '',
          'digits' => $item['digits'] ?? 0,
          'once'   => $item['once'] ?? false,
        ]);
        ?>
      </dd>
    </div>
  <?php endforeach; ?>
</dl>