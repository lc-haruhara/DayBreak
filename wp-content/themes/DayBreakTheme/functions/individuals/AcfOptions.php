<?php

if (!function_exists('acf_add_options_page')) {
  return;
}

// ============================================================
// 編集者へのアクセス制御
// true : 編集者に表示する / false : 管理者のみ
// フィールドの name をキーに指定する
// 全て false の場合、「サイト設定」メニューは編集者に非表示
// ============================================================
const ACF_OPTIONS_EDITOR_ACCESS = [
  'google_maps_api_key' => false, // Google Maps API キー
];

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ACF オプションページ（サイト設定）
// 目的: APIキーなどサイト全体の設定を管理画面から入力できるようにする
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('acf/init', function () {

  $has_editor_item = in_array(true, ACF_OPTIONS_EDITOR_ACCESS, true);
  $capability      = $has_editor_item ? 'manage_options' : 'manage_theme_tools';

  acf_add_options_page([
    'page_title' => 'サイト設定',
    'menu_title' => 'サイト設定',
    'menu_slug'  => 'site-settings',
    'capability' => $capability,
    'redirect'   => false,
    'position'   => 2,
  ]);

  acf_add_local_field_group([
    'key'    => 'group_site_settings',
    'title'  => 'サイト設定',
    'fields' => [
      [
        'key'   => 'field_google_maps_api_key',
        'label' => 'Google Maps API キー',
        'name'  => 'google_maps_api_key',
        'type'  => 'text',
      ],
    ],
    'location' => [
      [
        [
          'param'    => 'options_page',
          'operator' => '==',
          'value'    => 'site-settings',
        ],
      ],
    ],
  ]);
});

// 編集者には false に設定されたフィールドを非表示
add_filter('acf/prepare_field', function ($field) {

  if (!is_admin()) {
    return $field;
  }

  $user = wp_get_current_user();

  if (!in_array('editor', (array) $user->roles, true)) {
    return $field;
  }

  if (
    isset($field['name'], ACF_OPTIONS_EDITOR_ACCESS[$field['name']]) &&
    !ACF_OPTIONS_EDITOR_ACCESS[$field['name']]
  ) {
    return false;
  }

  return $field;
});
