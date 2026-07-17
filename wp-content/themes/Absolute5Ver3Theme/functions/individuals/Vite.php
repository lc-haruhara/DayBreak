<?php

function absolute5_is_vite_dev()
{
  return wp_get_environment_type() === 'local';
}

function absolute5_vite_dev_url($path = '')
{
  return 'http://localhost:5173' . $path;
}

// head
add_action('wp_head', function () {
  if (!absolute5_is_vite_dev()) return;

  echo '<script type="module" src="' . esc_url(absolute5_vite_dev_url('/@vite/client')) . '"></script>';
}, 1);

// footer
add_action('wp_footer', function () {
  if (!absolute5_is_vite_dev()) return;

  echo '<script type="module" src="' . esc_url(absolute5_vite_dev_url('/resource/js/app.js')) . '"></script>';
}, 100);

add_action('wp_enqueue_scripts', function () {
  $theme_uri  = get_template_directory_uri();
  $theme_path = get_template_directory();

  wp_enqueue_style(
    'absolute5-style',
    get_stylesheet_uri(),
    [],
    file_exists($theme_path . '/style.css') ? filemtime($theme_path . '/style.css') : null
  );

  if (absolute5_is_vite_dev()) {
    return;
  }

  $manifest_path = $theme_path . '/dist/.vite/manifest.json';

  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (isset($manifest['resource/js/app.js']['css'][0])) {
      wp_enqueue_style(
        'absolute5-main',
        $theme_uri . '/dist/' . $manifest['resource/js/app.js']['css'][0],
        ['absolute5-style'],
        null
      );
    }

    if (isset($manifest['resource/js/app.js']['file'])) {
      wp_enqueue_script(
        'absolute5-app',
        $theme_uri . '/dist/' . $manifest['resource/js/app.js']['file'],
        [],
        null,
        true
      );
      return;
    }
  }

  wp_enqueue_script(
    'absolute5-app',
    $theme_uri . '/resource/js/app.js',
    [],
    file_exists($theme_path . '/resource/js/app.js') ? filemtime($theme_path . '/resource/js/app.js') : null,
    true
  );
});
