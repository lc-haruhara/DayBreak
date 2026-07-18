<?php

/* ==========================================================
   Args
========================================================== */

$url           = trim($args['url'] ?? '');
$text          = trim($args['text'] ?? '');
$data          = $args['data'] ?? [];
$aria          = $args['aria'] ?? [];
$id            = $args['id'] ?? '';
$class         = $args['class'] ?? '';
$size          = $args['size'] ?? '';
$color         = $args['color'] ?? '';
$icon          = $args['icon'] ?? '';
$svg           = $args['svg'] ?? '';
$icon_position = $args['icon_position'] ?? 'right';
$label_for     = $args['label'] ?? '';
$text_jp        = $args['text_jp'] ?? '';

if ($text === '') {
  $text = 'Read more';
}

if ($text_jp === '') {
  $text_jp = '詳しく見る';
}

/* ==========================================================
   text の lang 自動判定
========================================================== */

$text_lang = '';

if ($text !== '' && preg_match('/^[A-Za-z]+(?:[ \-\'&]+[A-Za-z]+)*$/', $text)) {
  $text_lang = 'en';
}

/* ==========================================================
   タグ自動判定
========================================================== */

if ($label_for !== '') {
  $tag = 'label';
} elseif ($url !== '') {
  $tag = 'a';
} else {
  $tag = 'button';
}

$attrs = [];

/* ==========================================================
   id
========================================================== */

if ($id !== '') {
  $attrs[] = 'id="' . esc_attr($id) . '"';
}

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-btn-link'];

if ($size !== '') {
  $classes[] = '_size-' . sanitize_html_class($size);
}

if ($color !== '') {
  $classes[] = '_color-' . sanitize_html_class($color);
}

if (!empty($icon) || !empty($svg)) {
  $classes[] = '_icon-' . sanitize_html_class($icon_position);
}

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

$attrs[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';

/* ==========================================================
   aタグ処理
========================================================== */

if ($tag === 'a') {

  $is_tel    = false;
  $is_mailto = false;

  if (preg_match('/^tel:/i', $url)) {
    $tel_value = preg_replace('/^tel:\s*/i', '', $url);
    $tel_value = trim(preg_replace('/[^\d\-\.\+\(\)\s]/u', '', $tel_value));
    if ($tel_value !== '') {
      $is_tel  = true;
      $attrs[] = 'href="tel:' . esc_attr($tel_value) . '"';
    }
  } elseif (preg_match('/\d/', $url) && preg_match('/^[\d\s\-\.\+\(\)]+$/u', $url) && strlen(preg_replace('/\D/', '', $url)) >= 10) {
    $is_tel    = true;
    $tel_value = trim(preg_replace('/[^\d\-\.\+\(\)\s]/u', '', $url));
    $attrs[]   = 'href="tel:' . esc_attr($tel_value) . '"';
  }

  if (!$is_tel && $is_mailto === false) {
    if (preg_match('/^mailto:/i', $url)) {
      $mail_value = trim(preg_replace('/^mailto:\s*/i', '', $url));
      $email_part = (strpos($mail_value, '?') !== false) ? substr($mail_value, 0, strpos($mail_value, '?')) : $mail_value;
      if (is_email(trim($email_part))) {
        $is_mailto = true;
        $attrs[]   = 'href="mailto:' . esc_attr($mail_value) . '"';
      }
    } elseif (is_email($url)) {
      $is_mailto = true;
      $attrs[]   = 'href="mailto:' . esc_attr($url) . '"';
    }
  }

  if (!$is_tel && !$is_mailto) {
    $parsed_url  = wp_parse_url($url);
    $home_host   = wp_parse_url(home_url(), PHP_URL_HOST);
    $is_external = isset($parsed_url['host']) && $parsed_url['host'] !== $home_host;

    if (!$is_external && !isset($parsed_url['host'])) {
      $normalized_path = '/' . ltrim($url, '/');
      $url = $normalized_path === '/'
        ? home_url('/')
        : untrailingslashit(home_url($normalized_path));
    }

    $attrs[] = 'href="' . esc_url($url) . '"';

    if ($is_external) {
      $attrs[] = 'target="_blank"';
      $attrs[] = 'rel="noopener noreferrer external"';
    }
  }
}

/* ==========================================================
   button処理
========================================================== */

if ($tag === 'button') {
  $attrs[] = 'type="button"';
}

/* ==========================================================
   label処理
========================================================== */

if ($tag === 'label') {
  $attrs[] = 'for="' . esc_attr($label_for) . '"';
}

/* ==========================================================
   data属性
========================================================== */

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
   aria属性
========================================================== */

if (is_array($aria)) {
  foreach ($aria as $key => $value) {

    if ($value === true) {
      $attrs[] = 'aria-' . esc_attr($key);
      continue;
    }

    if ($value === '' || $value === null) {
      continue;
    }

    $attrs[] = 'aria-' . esc_attr($key) . '="' . esc_attr($value) . '"';
  }
}

/* ==========================================================
   アイコン生成（1回だけ）
========================================================== */

$icon_html = '';

if (!empty($icon) || !empty($svg)) {

  ob_start(); ?>

  <span class="c-btn-link-icon">
    <?php
    C_Elements('IconCommon', [
      'icon' => $icon,
      'svg'  => $svg,
    ]);
    ?>
  </span>

<?php
  $icon_html = ob_get_clean();
}

?>

<<?= esc_html($tag); ?> <?= implode(' ', $attrs); ?>>
  <span class="c-btn-link-body">

    <?php if ($icon_html && $icon_position === 'left') echo $icon_html; ?>

    <span class="c-btn-link-text">
      <span
        class="c-btn-link-text-en"
        <?php if ($text_lang !== ''): ?>
        lang="<?= esc_attr($text_lang); ?>"
        <?php endif; ?>>
        <?= esc_html($text); ?>
      </span>
      <span
        class="c-btn-link-text-jp">
        <?= esc_html($text_jp); ?>
      </span>
    </span>

    <?php if ($icon_html && $icon_position === 'right') echo $icon_html; ?>

  </span>
</<?= esc_html($tag); ?>>