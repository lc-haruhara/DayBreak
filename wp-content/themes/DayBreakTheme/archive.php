<?php

$post_type = get_query_var('post_type');

// タクソノミーアーカイブの場合は、その投稿タイプを取得
if (is_tax()) {
  $taxonomy = get_queried_object();
  $post_types = get_taxonomy($taxonomy->taxonomy)->object_type;
  $post_type = $post_types[0] ?? '';
}

// body class を p-archive-{post_type} にする
$body_class = 'p-archive-' . $post_type;

$sections = [];

// News
if (is_post_type_archive('news')) {
  $sections[] = 'pages/archives/archive-news/archive-news.php';

  //   // Blog
  // } elseif (is_post_type_archive('blog')) {
  //   $sections[] = 'pages/archives/archives-blog.php';

  //   // recruit または service タクソノミー
  // } elseif (is_post_type_archive('recruit') || is_tax('service')) {
  //   $sections[] = 'pages/archives/archives-blog.php';
}

require get_template_directory() . '/layout.php';
