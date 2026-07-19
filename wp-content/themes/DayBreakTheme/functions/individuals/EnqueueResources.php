<?php
add_action('wp_enqueue_scripts', function () {
  $theme_uri  = get_template_directory_uri();
  $theme_path = get_template_directory();

  $filever = function ($relative_path) use ($theme_path) {
    $full_path = $theme_path . $relative_path;
    return file_exists($full_path) ? filemtime($full_path) : null;
  };

  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  //
  // CSS
  //
  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  wp_enqueue_style(
    'absolute5-style',
    get_stylesheet_uri(),
    [],
    $filever('/style.css')
  );

  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  //
  // CDN JS
  //
  //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  // Swiper.js（MIT ライセンス / トップページ ヒーロースライダーで使用）
  wp_enqueue_script(
    'swiper-bundle-js',
    'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
    [],
    null,
    true
  );
  wp_enqueue_style(
    'swiper-bundle-css',
    'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
    [],
    null
  );

  // Lottie（lottie-web / MIT ライセンス / Concept セクションのロゴアニメーションで使用）
  wp_enqueue_script(
    'lottie-web',
    'https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js',
    [],
    null,
    true
  );

  // GSAP
  // wp_enqueue_script(
  //   'gsap',
  //   'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
  //   [],
  //   null,
  //   true
  // );

  // wp_enqueue_script(
  //   'gsap-scrolltrigger',
  //   'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
  //   ['gsap'],
  //   null,
  //   true
  // );

  // Google Maps API
  // wp_enqueue_script(
  //   'google-maps-api',
  //   'https://maps.googleapis.com/maps/api/js?key=AIzaSyB8l-rF55iiHn1Im3ZBI5h-ZuDSlxsl1xw',
  //   [],
  //   null,
  //   true
  // );

  // Smooth Scroll Page
  // wp_enqueue_script(
  //   'absolute5-smoothscroll-page-lib',
  //   $theme_uri . '/resource/js/library/SmoothScroll.js',
  //   [],
  //   $filever('/resource/js/library/SmoothScroll.js'),
  //   true
  // );
});
