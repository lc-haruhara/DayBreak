<?php

if (!function_exists('link_array_get')) {
  function link_array_get(array $array, string $key, $default = null)
  {
    return array_key_exists($key, $array) ? $array[$key] : $default;
  }
}

if (!function_exists('link_flatten_class')) {
  function link_flatten_class($class): ?string
  {
    if (empty($class)) return null;

    if (is_string($class)) {
      $class = trim($class);
      return $class !== '' ? $class : null;
    }

    if (is_array($class)) {
      $class = array_filter(array_map('trim', $class), fn($v) => $v !== '');
      return !empty($class) ? implode(' ', $class) : null;
    }

    return null;
  }
}

if (!function_exists('link_extract_text_from_html')) {
  function link_extract_text_from_html(string $html): string
  {
    $text = wp_strip_all_tags($html, true);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim((string) $text);
  }
}

if (!function_exists('link_resolve_text_lang')) {
  function link_resolve_text_lang(string $text, ?string $content_lang = null): ?string
  {
    if (!empty($content_lang)) return $content_lang;
    if ($text === '') return null;

    // 日本語が1文字でもあれば ja 扱い
    if (preg_match('/[\p{Hiragana}\p{Katakana}\p{Han}ー]/u', $text)) {
      return 'ja';
    }

    // 英数字中心なら en
    if (preg_match('/^[\p{Latin}\p{Common}\p{Zs}\p{N}\p{P}]+$/u', $text)) {
      return 'en';
    }

    return null;
  }
}

if (!function_exists('link_is_external_url')) {
  function link_is_external_url(string $href): bool
  {
    if ($href === '') return false;
    if (str_starts_with($href, 'mailto:')) return false;
    if (str_starts_with($href, 'tel:')) return false;
    if (str_starts_with($href, '#')) return false;

    $parsed_href = wp_parse_url($href);
    if (empty($parsed_href['host'])) return false;

    $home = wp_parse_url(home_url('/'));
    $home_host = $home['host'] ?? '';

    return isset($parsed_href['host']) && $parsed_href['host'] !== $home_host;
  }
}

if (!function_exists('link_is_current_url')) {
  function link_is_current_url(string $href): bool
  {
    if ($href === '' || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:') || str_starts_with($href, '#')) {
      return false;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    $current_url = home_url($request_uri);

    $current_path = untrailingslashit((string) wp_parse_url($current_url, PHP_URL_PATH));
    $href_path    = untrailingslashit((string) wp_parse_url($href, PHP_URL_PATH));

    return $current_path !== '' && $current_path === $href_path;
  }
}

if (!function_exists('link_resolve_href')) {
  function link_resolve_href(array $args): string
  {
    $href = link_array_get($args, 'href');
    $page = link_array_get($args, 'page');

    // page が数値ID
    if (is_numeric($page)) {
      $url = get_permalink((int) $page);
      return $url ?: '#';
    }

    // page が slug 想定
    if (is_string($page) && $page !== '') {
      $page_obj = get_page_by_path($page);
      if ($page_obj) {
        $url = get_permalink($page_obj->ID);
        return $url ?: '#';
      }
    }

    if (is_string($href) && $href !== '') {
      return $href;
    }

    return '#';
  }
}

if (!function_exists('link_resolve_link')) {
  function link_resolve_link(array $args): array
  {
    $raw_href = link_array_get($args, 'rawHref', '#');
    $has_download = !empty($args['hasDownload']);

    $is_mail = str_starts_with($raw_href, 'mailto:');
    $is_tel  = str_starts_with($raw_href, 'tel:');
    $is_external = link_is_external_url($raw_href);

    $target = null;
    $rel = null;

    if ($is_external) {
      $target = '_blank';
      $rel = 'noopener noreferrer';
    }

    return [
      'href' => $raw_href,
      'target' => $target,
      'rel' => $rel,
      'isMailLink' => $is_mail,
      'isTelLink' => $is_tel,
      'opensInNewTab' => $is_external,
      'isCurrent' => link_is_current_url($raw_href),
      'hasDownload' => $has_download,
    ];
  }
}

if (!function_exists('link_resolve_control_icon')) {
  function link_resolve_control_icon(array $args): array
  {
    $explicit_icon = $args['icon'] ?? null;
    $explicit_svg  = $args['svg'] ?? null;
    $data          = is_array($args['data'] ?? null) ? $args['data'] : [];
    $is_link       = !empty($args['isLink']);

    $has_modal_open = array_key_exists('modal-open', $data);

    if (!empty($explicit_svg)) {
      return [
        'showIcon' => true,
        'icon' => null,
        'svg' => $explicit_svg,
      ];
    }

    if (!empty($explicit_icon)) {
      return [
        'showIcon' => true,
        'icon' => $explicit_icon,
        'svg' => null,
      ];
    }

    if (!empty($args['isMailLink'])) {
      return [
        'showIcon' => true,
        'icon' => 'mail',
        'svg' => null,
      ];
    }

    if (!empty($args['isTelLink'])) {
      return [
        'showIcon' => true,
        'icon' => 'call',
        'svg' => null,
      ];
    }

    if (!empty($args['hasDownload'])) {
      return [
        'showIcon' => true,
        'icon' => 'download',
        'svg' => null,
      ];
    }

    if (!empty($args['opensInNewTab'])) {
      return [
        'showIcon' => true,
        'icon' => 'open_in_new',
        'svg' => null,
      ];
    }

    if ($has_modal_open) {
      return [
        'showIcon' => true,
        'icon' => 'ad',
        'svg' => null,
      ];
    }

    if ($is_link) {
      return [
        'showIcon' => true,
        'icon' => 'chevron_forward',
        'svg' => null,
      ];
    }

    return [
      'showIcon' => false,
      'icon' => null,
      'svg' => null,
    ];
  }
}

if (!function_exists('link_get_assistive_texts')) {
  function link_get_assistive_texts(array $args): array
  {
    $texts = [];

    if (!empty($args['isMailLink'])) {
      $texts[] = 'メールリンクです';
    }

    if (!empty($args['isTelLink'])) {
      $texts[] = '電話リンクです';
    }

    if (!empty($args['opensInNewTab'])) {
      $texts[] = '新しいタブで開きます';
    }

    if (!empty($args['hasDownload'])) {
      $texts[] = 'ファイルをダウンロードします';
    }

    return $texts;
  }
}

if (!function_exists('link_build_data_attributes')) {
  function link_build_data_attributes(array $data): string
  {
    $attrs = [];

    foreach ($data as $key => $value) {
      $key = preg_replace('/[^a-zA-Z0-9\-\_]/', '', (string) $key);
      if ($key === '') continue;

      if ($value === true) {
        $attrs[] = 'data-' . esc_attr($key);
      } elseif ($value === '' || $value === null || $value === false) {
        continue;
      } else {
        $attrs[] = 'data-' . esc_attr($key) . '="' . esc_attr((string) $value) . '"';
      }
    }

    return implode(' ', $attrs);
  }
}

if (!function_exists('link_build_extra_attributes')) {
  function link_build_extra_attributes(array $attrs): string
  {
    $html = [];

    foreach ($attrs as $key => $value) {
      if ($value === null || $value === false || $value === '') continue;

      if ($value === true) {
        $html[] = esc_attr($key);
      } else {
        $html[] = esc_attr($key) . '="' . esc_attr((string) $value) . '"';
      }
    }

    return implode(' ', $html);
  }
}
