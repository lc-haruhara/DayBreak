<?php
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// HTTPS redirect
// 機能: 管理画面への初回ログインで htaccess に httpsリダイレクトを書き込み。
// 目的: ヒューマンエラー防止のために htaccess への書き込みを自動化
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('wp_login', function ($user_login, $user) {
  // 管理者のみ実行
  if (!user_can($user, 'manage_options')) {
    return;
  }
  // すでに処理済みなら実行しない（保険）
  if (get_option('force_https_written')) {
    return;
  }
  $htaccess = ABSPATH . '.htaccess';
  if (!file_exists($htaccess) || !is_writable($htaccess)) {
    return;
  }
  $redirect_rule = <<<EOD
# BEGIN FORCE HTTPS
<IfModule mod_rewrite.c>
RewriteCond %{HTTPS} !=on [OR]
RewriteCond %{HTTP:X-Forwarded-Proto} !https
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
</IfModule>
# END FORCE HTTPS
EOD;
  $contents = file_get_contents($htaccess);
  // すでに書かれていたら何もしない
  if (strpos($contents, '# BEGIN FORCE HTTPS') !== false) {
    update_option('force_https_written', 1);
    return;
  }
  // WordPressルールより前に挿入
  if (strpos($contents, '# BEGIN WordPress') !== false) {
    $contents = str_replace(
      '# BEGIN WordPress',
      $redirect_rule . "\n# BEGIN WordPress",
      $contents
    );
  } else {
    $contents = $redirect_rule . $contents;
  }
  if (file_put_contents($htaccess, $contents) !== false) {
    update_option('force_https_written', 1);
  }
}, 10, 2);

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// head内の不要なタグを削除
// 機能: WordPressの不要なメタタグやスタイルシートを削除。
// 目的: セキュリティ向上とパフォーマンス改善。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('wp_enqueue_scripts', function () {
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('global-styles');
});

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
add_filter('style_loader_src', 'vc_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'vc_remove_wp_ver_css_js', 9999);
function vc_remove_wp_ver_css_js($src)
{
  return remove_query_arg('ver', $src);
}

//EmojiLoader
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_print_styles', 'wp_emoji_styles');
remove_action('wp_head', 'wp_print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');

//REST API リンク
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// RSSでサムネイルを表示
// 機能: RSSフィードに投稿のサムネイル画像を追加。
// 目的: フィード内で投稿の画像が確認できるようにする。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');
function rss_post_thumbnail($content)
{
  global $post;
  if (has_post_thumbnail($post->ID)) {
    $content = get_the_post_thumbnail($post->ID) . $content;
  }
  return $content;
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// RSSでカスタム投稿タイプを指定
// 機能: RSSフィードにカスタム投稿タイプを含める。
// 目的: カスタム投稿タイプ（例: news）をRSSで配信する。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('pre_get_posts', function ($query) {
  if ($query->is_feed()) {
    $post_type = $query->get('post_type');
    if (empty($post_type)) {
      $query->set('post_type', ['news']);
    }
  }
  return $query;
});


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// デフォルトファビコンの非表示
// 機能: WordPressが自動生成するデフォルトのファビコンを無効化。
// 目的: 独自のファビコンを利用するため。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('do_faviconico', function () {
  exit;
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// bodyタグにアクティブページのスラッグを追加
// 現在のページのスラッグを<body>タグのクラス属性に追加。
// 目的: ページごとのスタイリングやJavaScriptで利用しやすくする。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('body_class', function ($classes) {
  if (is_page()) {
    $page = get_post(get_the_ID());
    $classes[] = $page->post_name;
  }
  return $classes;
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 自動生成タグの制御
// 機能: 自動的に挿入される段落タグや画像タグを制御。
// 目的: 投稿本文のHTMLをよりクリーンに保つ。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('init', function () {
  remove_filter('the_excerpt', 'wpautop');
  remove_filter('the_content', 'wpautop');
});

add_filter('the_content', function ($content) {
  if (strpos($content, '<img') !== false) {
    $content = preg_replace('/<p>\s*(<img [^>]+>)\s*<\/p>/iU', '<div class="img">$1</div>', $content);
  }
  return $content;
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//	PaginationSelectBox
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//New - urlのみ出力
function select_pagination_new($maxPages, $paged)
{
  if ($maxPages == 1) return;
  if ($paged != 1) {
    echo esc_url(get_pagenum_link($paged - 1));
  }
}
//Old - urlのみ出力
function select_pagination_old($maxPages, $paged)
{
  if ($maxPages == 1) return;
  if ($paged != $maxPages) {
    echo esc_url(get_pagenum_link($paged + 1));
  }
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// コンポーネントのインクルード文を定型化
// 機能: get_template_directory を 簡略化する
// 目的: 助長になるコードのシュリンク
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
if (!defined('COMPONENTS_ELEMENTS')) {
  define('COMPONENTS_ELEMENTS', trailingslashit(get_stylesheet_directory()) . 'components/elements/');
}
if (!defined('COMPONENTS_PARTS')) {
  define('COMPONENTS_PARTS', trailingslashit(get_stylesheet_directory()) . 'components/parts/');
}
if (!defined('COMPONENTS_UTILITY')) {
  define('COMPONENTS_UTILITY', trailingslashit(get_stylesheet_directory()) . 'components/utilities/');
}
if (!defined('INCLUDE_SVG')) {
  define('INCLUDE_SVG', trailingslashit(get_stylesheet_directory()) . 'images/');
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// コンポーネントを関数で使うためのファイル読み込み
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
require_once get_template_directory() . '/components/utilities/Includes.php';
