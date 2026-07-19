<?php

/* ==========================================================
   Args

   name   : 表示するロゴ名。images/logo-{name}.svg を読み込む
            例) 'main'（既定） / 'store1' 〜 'store10'
   class  : ラッパーに追加するクラス（文字列 or 配列）
   inline : true  … SVG を展開（既定）。CSS で fill を制御できる
            false … img タグで読み込む。HTML が軽くなりキャッシュも効く
   alt    : img モード時の代替テキスト（既定は空＝装飾扱い）
   lazy   : img モード時に loading="lazy" を付けるか（既定 true）
========================================================== */

$name   = $args['name'] ?? 'main';
$class  = $args['class'] ?? '';
$inline = $args['inline'] ?? true;
$alt    = $args['alt'] ?? '';
$lazy   = $args['lazy'] ?? true;

/* ==========================================================
   ファイル名の検証

   name をそのままパスに使うため、英数字とハイフン・アンダースコアのみ許可する
========================================================== */

if (!is_string($name) || !preg_match('/\A[a-zA-Z0-9_-]+\z/', $name)) {
  $name = 'main';
}

$file = 'logo-' . $name . '.svg';

if (!is_file(get_template_directory() . '/images/' . $file)) {
  $name = 'main';
  $file = 'logo-main.svg';
}

$svg_path = get_template_directory() . '/images/' . $file;

/* ==========================================================
   class生成
========================================================== */

$classes = ['c-logo-common', '_name-' . sanitize_html_class($name)];

if (is_array($class)) {
  foreach ($class as $c) {
    $classes[] = sanitize_html_class($c);
  }
} elseif ($class !== '') {
  $classes[] = sanitize_html_class($class);
}

/* ==========================================================
   出力
========================================================== */

if (!$inline) :

  /* img モード
     レイアウトシフトを防ぐため、SVG の先頭から width / height を読む */

  $header = file_get_contents($svg_path, false, null, 0, 512);
  $width  = '';
  $height = '';

  if ($header !== false) {
    if (preg_match('/\bwidth="([0-9.]+)/', $header, $m)) {
      $width = $m[1];
    }
    if (preg_match('/\bheight="([0-9.]+)/', $header, $m)) {
      $height = $m[1];
    }
  }
?>

  <span class="<?= esc_attr(implode(' ', $classes)); ?>">
    <img
      src="<?= esc_url(get_template_directory_uri() . '/images/' . $file); ?>"
      alt="<?= esc_attr($alt); ?>"
      <?php if ($width !== '' && $height !== '') : ?>width="<?= esc_attr($width); ?>" height="<?= esc_attr($height); ?>" <?php endif; ?>
      <?php if ($lazy) : ?>loading="lazy" decoding="async" <?php endif; ?>>
  </span>

<?php

else :

  /* インラインモード
     テーマ同梱の SVG のみを読み込むため、そのまま展開する */

  $svg = file_get_contents($svg_path);

  if ($svg === false) {
    return;
  }
?>

  <span class="<?= esc_attr(implode(' ', $classes)); ?>">
    <?= $svg; ?>
  </span>

<?php endif; ?>
