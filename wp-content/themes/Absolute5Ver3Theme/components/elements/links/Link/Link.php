<?php

require_once get_template_directory() . '/components/elements/links/Link/assets/Link.lib.php';

/**
 * 想定引数
 * - text?: string
 * - html?: string
 * - href?: string
 * - page?: int|string
 * - contentLang?: string
 * - showIcon?: bool
 * - icon?: string
 * - svg?: string
 * - data?: array
 * - class?: string|array
 * - download?: bool|string
 * - aria?: array
 * - attrs?: array
 */

$text         = $args['text'] ?? '';
$html         = $args['html'] ?? '';
$href         = $args['href'] ?? null;
$page         = $args['page'] ?? null;
$content_lang = $args['contentLang'] ?? null;
$show_icon    = $args['showIcon'] ?? null;
$icon         = $args['icon'] ?? null;
$svg          = $args['svg'] ?? null;
$data         = $args['data'] ?? [];
$class        = link_flatten_class($args['class'] ?? '');
$download     = $args['download'] ?? false;
$aria         = $args['aria'] ?? [];
$attrs        = $args['attrs'] ?? [];

$is_link = !empty($href) || !empty($page);

$raw_href = $is_link
  ? link_resolve_href([
    'href' => $href,
    'page' => $page,
  ])
  : '';

$has_download = $is_link && isset($args['download']) && $args['download'] !== false;

$link = $is_link
  ? link_resolve_link([
    'rawHref' => $raw_href,
    'hasDownload' => $has_download,
  ])
  : [
    'href' => '',
    'target' => null,
    'rel' => null,
    'isMailLink' => false,
    'isTelLink' => false,
    'opensInNewTab' => false,
    'isCurrent' => false,
    'hasDownload' => false,
  ];

$control_icon =
  $show_icon === false
  ? [
    'showIcon' => false,
    'icon' => null,
    'svg' => null,
  ]
  : link_resolve_control_icon([
    'icon' => $icon,
    'svg' => $svg,
    'data' => is_array($data) ? $data : [],
    'isLink' => $is_link,
    'isMailLink' => $link['isMailLink'],
    'isTelLink' => $link['isTelLink'],
    'opensInNewTab' => $link['opensInNewTab'],
    'hasDownload' => $has_download,
  ]);

$content_source   = $html !== '' ? $html : $text;
$content_text     = link_extract_text_from_html($content_source);
$resolved_lang    = link_resolve_text_lang($content_text, $content_lang);

$assistive_texts = $is_link
  ? link_get_assistive_texts([
    'isMailLink' => $link['isMailLink'],
    'isTelLink' => $link['isTelLink'],
    'opensInNewTab' => $link['opensInNewTab'],
    'hasDownload' => $has_download,
  ])
  : [];

// aria-label の競合を避ける
if (
  $is_link &&
  ($link['isMailLink'] || $link['isTelLink'] || $link['opensInNewTab'] || $has_download) &&
  isset($aria['label'])
) {
  unset($aria['label']);
}

$extra_attrs = [];

// button のときは type を明示
if (!$is_link) {
  $extra_attrs['type'] = 'button';
}

// download は a のときだけ
if ($is_link && $download !== false) {
  if (is_string($download) && $download !== '') {
    $extra_attrs['download'] = $download;
  } else {
    $extra_attrs['download'] = true;
  }
}

// aria
foreach ($aria as $k => $v) {
  if ($v === null || $v === false || $v === '') continue;
  $extra_attrs['aria-' . $k] = $v;
}

// attrs
foreach ($attrs as $k => $v) {
  $extra_attrs[$k] = $v;
}

// link解決結果で上書き
if ($is_link) {
  $extra_attrs['href'] = $link['href'];

  if (!empty($link['target'])) {
    $extra_attrs['target'] = $link['target'];
  }

  if (!empty($link['rel'])) {
    $extra_attrs['rel'] = $link['rel'];
  }

  if (!empty($link['isCurrent'])) {
    $extra_attrs['aria-current'] = 'page';
  }
}

$base_class = $is_link ? 'c-link' : 'c-button';
$merged_class = trim($base_class . ' ' . ($class ?? ''));

if ($merged_class !== '') {
  $extra_attrs['class'] = $merged_class;
}

$data_attr_html  = link_build_data_attributes(is_array($data) ? $data : []);
$extra_attr_html = link_build_extra_attributes($extra_attrs);
$tag = $is_link ? 'a' : 'button';
?>

<<?= $tag; ?> <?= $extra_attr_html; ?> <?= $data_attr_html; ?>>
  <span<?= $resolved_lang ? ' lang="' . esc_attr($resolved_lang) . '"' : ''; ?>>
    <?php
    if ($html !== '') {
      echo wp_kses_post($html);
    } else {
      echo esc_html($text);
    }
    ?>
    </span>

    <?php if (!empty($control_icon['showIcon']) && (!empty($control_icon['icon']) || !empty($control_icon['svg']))) : ?>
      <?php
      if (function_exists('C_Elements')) {
        C_Elements('IconCommon', [
          'icon' => $control_icon['icon'],
          'svg'  => $control_icon['svg'],
        ]);
      }
      ?>
    <?php endif; ?>

    <?php foreach ($assistive_texts as $assistive_text) : ?>
      <span class="f-utility-sr-only"><?= esc_html($assistive_text); ?></span>
    <?php endforeach; ?>
</<?= $tag; ?>>