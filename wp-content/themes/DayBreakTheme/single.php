<?php

$post_type = get_post_type();

// body class を p-single-{post_type} にする
$body_class = 'p-single-' . $post_type;

// マッピング
$map = [
  'news' => 'pages/singles/single-news/single-news.php',
  // 'hoge' => 'pages/singles/single-hoge/single-hoge.php',
];

$sections = [];

if (isset($map[$post_type])) {
  $sections[] = $map[$post_type];
} else {
}

require get_template_directory() . '/layout.php';
