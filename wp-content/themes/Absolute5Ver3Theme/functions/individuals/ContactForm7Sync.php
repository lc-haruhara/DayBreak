<?php

if (!defined('ABSPATH')) exit;

if (defined('KM_CF7_SYNC_LOADED')) {
  return;
}
define('KM_CF7_SYNC_LOADED', true);

/**
 * schema 一覧
 */
function km_cf7_schemas(): array
{
  $dir = get_template_directory() . '/components/elements/inputs/cf7/.Cf7Schema';
  $schemas = [];

  foreach (glob($dir . '/*.php') as $file) {
    $basename = basename($file);

    // Sync本体は schema として扱わない
    if (in_array($basename, ['InputCf7Sync.php'], true)) {
      continue;
    }

    $schema = require $file;

    if (!is_array($schema) || empty($schema['key'])) {
      continue;
    }

    $schemas[$schema['key']] = $schema;
  }

  return $schemas;
}

function km_cf7_schema(string $key): ?array
{
  $schemas = km_cf7_schemas();
  return $schemas[$key] ?? null;
}

/**
 * 管理画面
 */
add_action('admin_menu', function () {
  add_management_page(
    'CF7 Sync',
    'CF7 Sync',
    'manage_theme_tools',
    'km-cf7-sync',
    'km_cf7_render_sync_page'
  );
});

function km_cf7_render_sync_page(): void
{
  if (!current_user_can('manage_options')) {
    wp_die('権限がありません。');
  }

  $schemas = km_cf7_schemas();

  echo '<div class="wrap">';
  echo '<h1>CF7 Sync</h1>';
  echo '<p>schema を正本として Contact Form 7 に同期します。</p>';

  if (empty($schemas)) {
    echo '<div class="notice notice-warning"><p>schema が見つかりません。</p></div>';
    echo '</div>';
    return;
  }

  if (
    isset($_POST['km_cf7_sync_key'], $_POST['_wpnonce']) &&
    wp_verify_nonce($_POST['_wpnonce'], 'km_cf7_sync')
  ) {
    $key = sanitize_key(wp_unslash($_POST['km_cf7_sync_key']));
    $result = km_cf7_sync_form($key);

    if (is_wp_error($result)) {
      echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
    } else {
      echo '<div class="notice notice-success"><p>同期しました: ' . esc_html($key) . '</p></div>';
    }
  }

  foreach ($schemas as $key => $schema) {
    $cf7_id = (int)($schema['cf7_id'] ?? 0);

    echo '<hr>';
    echo '<h2>' . esc_html($schema['title'] ?? $key) . '</h2>';
    echo '<p><strong>Schema key:</strong> ' . esc_html($key) . '</p>';
    echo '<p><strong>CF7 ID:</strong> ' . esc_html((string)$cf7_id) . '</p>';

    echo '<form method="post" style="margin:12px 0 20px;">';
    wp_nonce_field('km_cf7_sync');
    echo '<input type="hidden" name="km_cf7_sync_key" value="' . esc_attr($key) . '">';
    submit_button('このフォームを同期', 'primary', 'submit', false);
    echo '</form>';

    echo '<details style="margin-top:16px;">';
    echo '<summary>生成プレビューを見る</summary>';

    echo '<h3>Form</h3>';
    echo '<textarea class="large-text code" rows="24" readonly>' . esc_textarea(km_cf7_build_form($schema)) . '</textarea>';

    echo '<h3>Mail</h3>';
    echo '<textarea class="large-text code" rows="18" readonly>' . esc_textarea(km_cf7_build_admin_mail_body($schema)) . '</textarea>';

    echo '<h3>Mail (2)</h3>';
    echo '<textarea class="large-text code" rows="22" readonly>' . esc_textarea(km_cf7_build_reply_mail_body($schema)) . '</textarea>';

    echo '<h3>Additional Settings</h3>';
    echo '<textarea class="large-text code" rows="6" readonly>' . esc_textarea(km_cf7_build_additional_settings($schema)) . '</textarea>';

    echo '</details>';
  }

  echo '</div>';
}

/**
 * 同期
 */
function km_cf7_sync_form(string $key)
{
  // phpcs / intelephense 用のスタブ
  if (!class_exists('WPCF7_ContactForm')) {
    class WPCF7_ContactForm
    {
      public static function get_instance($id) {}
      public static function get_current() {}
      public function prop($name) {}
      public function set_title($title) {}
      public function set_properties($properties) {}
      public function save() {}
      public function id() {}
    }
  }

  if (!class_exists('WPCF7_Submission')) {
    class WPCF7_Submission
    {
      public static function get_instance() {}
      public function get_posted_data() {}
    }
  }

  if (!class_exists('WPCF7_ContactForm')) {
    return new WP_Error('cf7_missing', 'Contact Form 7 が有効ではありません。');
  }

  $schema = km_cf7_schema($key);
  if (!$schema) {
    return new WP_Error('schema_missing', 'schema が見つかりません。');
  }

  $cf7_id = (int)($schema['cf7_id'] ?? 0);
  if (!$cf7_id) {
    return new WP_Error('cf7_id_missing', 'cf7_id が未設定です。');
  }

  $contact_form = WPCF7_ContactForm::get_instance($cf7_id);
  if (!$contact_form) {
    return new WP_Error('cf7_form_missing', '指定IDの CF7 フォームが見つかりません。');
  }

  $current_mail   = (array) $contact_form->prop('mail');
  $current_mail_2 = (array) $contact_form->prop('mail_2');

  $adminRecipient = trim((string)($schema['mail']['admin_recipient'] ?? get_option('admin_email')));
  $commonSender   = '[_site_title] <' . $adminRecipient . '>';

  // schema から件名を取得
  $adminSubject = trim((string)($schema['mail']['admin_subject'] ?? ''));
  if ($adminSubject === '') {
    $adminSubject = '【[_site_title]】お問い合わせを受信しました';
  }

  $replySubject = trim((string)($schema['mail']['reply_subject'] ?? ''));
  if ($replySubject === '') {
    $replySubject = '【[_site_title]】お問い合わせありがとうございます。';
  }

  $mail = array_merge($current_mail, [
    'active'             => true,
    'recipient'          => $adminRecipient,
    'sender'             => $commonSender,
    'subject'            => $adminSubject,
    'additional_headers' => km_cf7_build_admin_headers($schema),
    'body'               => km_cf7_build_admin_mail_body($schema),
    'attachments'        => km_cf7_build_admin_attachments($schema),
    'use_html'           => false,
    'exclude_blank'      => false,
  ]);

  $mail_2 = array_merge($current_mail_2, [
    'active'             => true,
    'recipient'          => '[your-email]',
    'sender'             => $commonSender,
    'subject'            => $replySubject,
    'additional_headers' => 'Reply-To: ' . $adminRecipient,
    'body'               => km_cf7_build_reply_mail_body($schema),
    'attachments'        => '',
    'use_html'           => false,
    'exclude_blank'      => false,
  ]);

  $contact_form->set_title($schema['title'] ?? $key);
  $contact_form->set_properties([
    'form'                => km_cf7_build_form($schema),
    'mail'                => $mail,
    'mail_2'              => $mail_2,
    'additional_settings' => km_cf7_build_additional_settings($schema),
  ]);

  $saved = $contact_form->save();

  if (!$saved) {
    return new WP_Error('cf7_save_failed', 'CF7 フォームの保存に失敗しました。');
  }

  update_option('km_cf7_schema_map_' . $cf7_id, $key);

  return true;
}

/**
 * フォーム生成
 */
function km_cf7_build_form(array $schema): string
{
  $chunks = [];

  foreach ((array)($schema['fields'] ?? []) as $field) {
    $component = $field['component'] ?? '';
    if ($component === '') {
      continue;
    }

    $chunks[] = km_cf7_render_component($component, [
      'field'  => $field,
      'schema' => $schema,
    ]);
  }

  $submit_label = $schema['submit']['label'] ?? '送信';
  $submit_id    = $schema['submit']['id'] ?? 'submit';

  $chunks[] = '[submit id:' . km_cf7_escape_token($submit_id) . ' "' . km_cf7_escape_quoted($submit_label) . '"]';

  return implode("\n\n", array_filter($chunks));
}

function km_cf7_render_component(string $component, array $props = []): string
{
  $candidates = [
    get_template_directory() . '/components/elements/inputs/' . $component . '/' . $component . '.php',
    get_template_directory() . '/components/parts/forms/' . $component . '/' . $component . '.php',
  ];

  $file = '';

  foreach ($candidates as $candidate) {
    if (file_exists($candidate)) {
      $file = $candidate;
      break;
    }
  }

  if ($file === '') {
    return '<!-- component not found: ' . esc_html($component) . ' -->';
  }

  ob_start();
  $args = $props;
  include $file;
  return trim((string) ob_get_clean());
}

/**
 * CF7タグ生成
 */
function km_cf7_build_tag(array $field): string
{
  $type     = $field['type'] ?? 'text';
  $name     = $field['name'] ?? '';
  $required = !empty($field['required']);

  if ($name === '') {
    return '';
  }

  if ($type === 'acceptance') {
    $tagType = 'acceptance' . ($required ? '*' : '');
    return '[' . $tagType . ' ' . km_cf7_escape_token($name) . ' "' . km_cf7_escape_quoted($field['label'] ?? $name) . '"]';
  }

  $tagType = $type . ($required ? '*' : '');
  $parts   = [$tagType, $name];

  $parts[] = 'id:' . (!empty($field['id']) ? $field['id'] : $name);

  if (!empty($field['autocomplete'])) {
    $parts[] = 'autocomplete:' . $field['autocomplete'];
  }

  if (!empty($field['maxlength'])) {
    $parts[] = 'maxlength:' . (int)$field['maxlength'];
  }

  if (!empty($field['minlength'])) {
    $parts[] = 'minlength:' . (int)$field['minlength'];
  }

  if ($type === 'select') {
    $parts[] = 'first_as_label';
  }

  if (in_array($type, ['radio', 'checkbox'], true)) {
    $parts[] = 'use_label_element';
  }

  if ($type === 'file') {
    if (!empty($field['filetypes'])) {
      $parts[] = 'filetypes:' . $field['filetypes'];
    }
    if (!empty($field['limit'])) {
      $parts[] = 'limit:' . $field['limit'];
    }
  }

  if ($type === 'radio' && !empty($field['default']) && !empty($field['options'])) {
    $index = array_search($field['default'], $field['options'], true);
    if ($index !== false) {
      $parts[] = 'default:' . ($index + 1);
    }
  }

  $values = [];

  if ($type === 'select') {
    if (!empty($field['first_option_label'])) {
      $values[] = $field['first_option_label'];
    }
    foreach ((array)($field['options'] ?? []) as $option) {
      $values[] = $option;
    }
  } elseif (in_array($type, ['radio', 'checkbox'], true)) {
    foreach ((array)($field['options'] ?? []) as $option) {
      $values[] = $option;
    }
  } elseif (array_key_exists('placeholder', $field)) {
    $parts[] = 'placeholder';
    $values[] = (string)$field['placeholder'];
  }

  $tag = '[' . implode(' ', array_map('km_cf7_escape_token', $parts));

  if ($values) {
    $tag .= ' ' . implode(' ', array_map(
      static fn($value) => '"' . km_cf7_escape_quoted((string)$value) . '"',
      $values
    ));
  }

  $tag .= ']';

  return $tag;
}

/**
 * schema.json から会社情報取得
 * 空文字の項目は出力しない
 */
function km_cf7_get_company_info_from_schema(): string
{
  $file = get_template_directory() . '/components/utilities/schema.json';

  if (!file_exists($file)) {
    return '';
  }

  $json = file_get_contents($file);
  if ($json === false || trim($json) === '') {
    return '';
  }

  $data = json_decode($json, true);
  if (!is_array($data)) {
    return '';
  }

  $org = $data['organization'] ?? null;
  if (!is_array($org)) {
    return '';
  }

  $name = trim((string)($org['name'] ?? ''));

  $address = is_array($org['address'] ?? null) ? $org['address'] : [];
  $postalCode = trim((string)($address['postalCode'] ?? ''));
  $addressLocality = trim((string)($address['addressLocality'] ?? ''));
  $streetAddress = trim((string)($address['streetAddress'] ?? ''));

  $contactPoint = is_array($org['contactPoint'] ?? null) ? $org['contactPoint'] : [];
  $telephone = trim((string)($contactPoint['telephone'] ?? ''));

  $lines = [];

  if ($name !== '') {
    $lines[] = $name;
  }

  $addressParts = [];
  if ($postalCode !== '') {
    $addressParts[] = '〒' . $postalCode;
  }
  if ($addressLocality !== '') {
    $addressParts[] = $addressLocality;
  }
  if ($streetAddress !== '') {
    $addressParts[] = $streetAddress;
  }

  $addressLine = trim(implode(' ', $addressParts));
  if ($addressLine !== '') {
    $lines[] = $addressLine;
  }

  if ($telephone !== '') {
    $lines[] = 'TEL: ' . $telephone;
  }

  return implode("\n", $lines);
}

/**
 * 受信メール本文
 */
function km_cf7_build_admin_mail_body(array $schema): string
{
  $intro = trim((string)($schema['mail']['admin_intro'] ?? ''));
  if ($intro === '') {
    $intro = '[_site_title] のコンタクトフォームから以下の内容でお問合せが届きました。';
  }

  $lines = [];
  $lines[] = $intro;
  $lines[] = 'URL : [_site_url]';
  $lines[] = '';
  $lines[] = '------------------------------';
  $lines[] = '';

  foreach ((array)($schema['fields'] ?? []) as $field) {
    if (array_key_exists('mail', $field) && !$field['mail']) {
      continue;
    }

    if (($field['type'] ?? '') === 'acceptance') {
      continue;
    }

    $lines[] = ($field['label'] ?? $field['name']);
    $lines[] = '[' . $field['name'] . ']';
    $lines[] = '';
  }

  $lines[] = '------------------------------';

  return implode("\n", $lines);
}

/**
 * 自動返信本文
 */
function km_cf7_build_reply_mail_body(array $schema): string
{
  $replyIntro = trim((string)($schema['mail']['reply_intro'] ?? ''));
  if ($replyIntro === '') {
    $replyIntro = 'この度はお問合せありがとうございます。';
  }

  $lines = [];
  $lines[] = '[your-name] 様';
  $lines[] = '';
  $lines[] = $replyIntro;
  $lines[] = '以下の内容で承りました。';
  $lines[] = '追ってご連絡差し上げますので今しばらくお待ちくださいませ。';
  $lines[] = '';
  $lines[] = '※ このメールは [_site_title] のウェブサイトから自動返信されています。';
  $lines[] = 'URL : [_site_url]';
  $lines[] = '';
  $lines[] = '';
  $lines[] = 'お問い合わせ内容 ---------------';
  $lines[] = '';

  foreach ((array)($schema['fields'] ?? []) as $field) {
    if (array_key_exists('mail', $field) && !$field['mail']) {
      continue;
    }

    if (($field['type'] ?? '') === 'acceptance') {
      continue;
    }

    $lines[] = ($field['label'] ?? $field['name']);
    $lines[] = '[' . $field['name'] . ']';
    $lines[] = '';
  }

  $lines[] = '------------------------------';

  $companyInfo = km_cf7_get_company_info_from_schema();
  if ($companyInfo !== '') {
    $lines[] = '';
    $lines[] = $companyInfo;
  } elseif (!empty($schema['mail']['reply_footer'])) {
    $fallbackFooter = trim((string)$schema['mail']['reply_footer']);
    if ($fallbackFooter !== '') {
      $lines[] = '';
      $lines[] = $fallbackFooter;
    }
  }

  return implode("\n", $lines);
}

function km_cf7_build_admin_headers(array $schema): string
{
  foreach ((array)($schema['fields'] ?? []) as $field) {
    if (!empty($field['reply_to'])) {
      return 'Reply-To: [' . $field['name'] . ']';
    }
  }

  return '';
}

function km_cf7_build_admin_attachments(array $schema): string
{
  $attachments = [];

  foreach ((array)($schema['fields'] ?? []) as $field) {
    if (($field['type'] ?? '') === 'file' && !empty($field['attach_to_admin_mail'])) {
      $attachments[] = '[' . $field['name'] . ']';
    }
  }

  return implode("\n", $attachments);
}

function km_cf7_build_additional_settings(array $schema): string
{
  return implode("\n", (array)($schema['additional_settings'] ?? []));
}

/**
 * ユーティリティ
 */
function km_cf7_escape_token(string $value): string
{
  return preg_replace('/\s+/', '', $value);
}

function km_cf7_escape_quoted(string $value): string
{
  return str_replace('"', '\"', $value);
}
