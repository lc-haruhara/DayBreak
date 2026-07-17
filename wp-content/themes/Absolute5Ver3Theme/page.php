<?php

$slug = get_queried_object()->post_name;

// body class を p-{slug} にする
$body_class = 'p-' . $slug;

// マッピング
$map = [
  // 'hoge'    => 'pages/subs/xxxxxxx/xxxxxxx.php',
  'contact' => 'pages/subs/contact/contact.php',
];

$sections = [];
if (isset($map[$slug])) {
  $sections[] = $map[$slug];
} else {
}

require get_template_directory() . '/layout.php';
