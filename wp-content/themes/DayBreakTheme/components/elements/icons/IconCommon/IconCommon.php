<?php
$icon    = $args['icon'] ?? '';
$svg     = $args['svg'] ?? '';
$url     = $args['url'] ?? '';
$aria    = $args['aria'] ?? [];
$is_btn  = !empty($args['button']);
$is_span = !empty($args['span']);

if (!$icon && !$svg) return;

/* -------------------------
   svg / 画像判定
------------------------- */

$is_svg = $svg !== '';
$is_image = false;

if ($is_svg) {
  $svg_file = str_ends_with($svg, '.svg') ? $svg : $svg . '.svg';
  $svg_path = get_template_directory() . '/images/icons/' . $svg_file;
} else {
  $extension = strtolower(pathinfo($icon, PATHINFO_EXTENSION));
  $image_extensions = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp'];
  $is_image = in_array($extension, $image_extensions, true);
}

/* -------------------------
   URL判定
------------------------- */

$is_link = !empty($url);
$is_external = false;

if ($is_link) {
  $is_external = filter_var($url, FILTER_VALIDATE_URL)
    && !str_contains($url, home_url());
}

/* -------------------------
   class生成
------------------------- */

$classes = ['c-icon'];

if (!$is_svg && !$is_image && $icon !== '') {
  $classes[] = '_' . sanitize_html_class($icon);
}

if (!empty($args['size'])) {
  $classes[] = '_size-' . sanitize_html_class($args['size']);
}

if (!empty($args['color'])) {
  $classes[] = '_color-' . sanitize_html_class($args['color']);
}

$class_attr = implode(' ', $classes);

/* -------------------------
   アイコンHTML生成
------------------------- */

ob_start();
?>

<span class="<?php echo esc_attr($class_attr); ?>" aria-hidden="true">
  <?php if ($is_svg): ?>
    <?php
    if (file_exists($svg_path)) {
      $svg_markup = file_get_contents($svg_path);

      if ($svg_markup !== false) {
        echo $svg_markup;
      }
    }
    ?>
  <?php elseif ($is_image): ?>
    <img
      src="<?php echo esc_url(get_template_directory_uri() . '/images/icons/' . $icon); ?>"
      alt=""
      loading="lazy">
  <?php else: ?>
    <?php echo esc_html($icon); ?>
  <?php endif; ?>
</span>

<?php
$icon_html = ob_get_clean();

/* -------------------------
   aria-label自動補完（aタグのみ）
------------------------- */

if ($is_link) {
  $label = $aria['label'] ?? '';

  if ($label !== '') {
    if (!str_contains($label, 'へ遷移')) {
      if ($is_external) {
        $label .= 'へ遷移（別ウィンドウで開きます）';
      } else {
        $label .= 'へ遷移';
      }

      $aria['label'] = $label;
    }
  }
}

/* -------------------------
   aria属性生成
------------------------- */

$attrs = [];

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

$aria_attr = implode(' ', $attrs);

/* -------------------------
   出力分岐
------------------------- */

if ($is_link):
?>
  <a
    href="<?php echo esc_url($url); ?>"
    class="c-icon-btn"
    <?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
    <?php echo $aria_attr; ?>>
    <?php echo $icon_html; ?>
  </a>

<?php elseif ($is_btn): ?>

  <button
    type="button"
    class="c-icon-btn"
    <?php echo $aria_attr; ?>>
    <?php echo $icon_html; ?>
  </button>

<?php elseif ($is_span): ?>

  <span
    class="c-icon-btn"
    <?php echo $aria_attr; ?>>
    <?php echo $icon_html; ?>
  </span>

<?php else: ?>

  <?php echo $icon_html; ?>

<?php endif; ?>