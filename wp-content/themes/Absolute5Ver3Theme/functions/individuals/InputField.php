<?php

function km_build_input_tag(array $field): string
{
  return _km_dispatch_input($field, false);
}

function km_build_cf7_input_tag(array $field): string
{
  return _km_dispatch_input($field, true);
}

function _km_dispatch_input(array $field, bool $cf7): string
{
  $type = $field['type'] ?? 'text';
  $name = $field['name'] ?? '';

  if ($name === '') {
    return '';
  }

  $id       = !empty($field['id']) ? $field['id'] : $name;
  $required = !empty($field['required']);

  switch ($type) {
    case 'text':
    case 'email':
    case 'tel':
    case 'url':
    case 'number':
    case 'password':
      return _km_build_input($field, $type, $name, $id, $required, $cf7);
    case 'textarea':
      return _km_build_textarea($field, $name, $id, $required, $cf7);
    case 'select':
      return _km_build_select($field, $name, $id, $required, $cf7);
    case 'radio':
    case 'checkbox':
      return _km_build_choices($field, $type, $name, $id, $required, $cf7);
    default:
      return '';
  }
}

function _km_build_input(array $field, string $type, string $name, string $id, bool $required, bool $cf7): string
{
  $attrs = [
    'type="' . esc_attr($type) . '"',
    'name="' . esc_attr($name) . '"',
    'id="' . esc_attr($id) . '"',
  ];
  if ($cf7) {
    $classes = ['wpcf7-form-control', 'wpcf7-' . $type];
    if ($required) {
      $classes[] = 'wpcf7-validates-as-required';
    }
    $attrs[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';
  }
  if ($required)                             $attrs[] = 'required';
  if (!empty($field['autocomplete']))        $attrs[] = 'autocomplete="' . esc_attr($field['autocomplete']) . '"';
  if (!empty($field['maxlength']))           $attrs[] = 'maxlength="' . (int)$field['maxlength'] . '"';
  if (!empty($field['minlength']))           $attrs[] = 'minlength="' . (int)$field['minlength'] . '"';
  if (array_key_exists('placeholder', $field)) {
    $attrs[] = 'placeholder="' . esc_attr((string)$field['placeholder']) . '"';
  }
  $attrs[] = 'aria-describedby="' . esc_attr($name) . '-help"';

  $html = '<span class="c-input-item-input-body"><input ' . implode(' ', $attrs) . '></span>';

  if ($cf7) {
    return '<span class="wpcf7-form-control-wrap" data-name="' . esc_attr($name) . '">' . $html . '</span>';
  }

  return $html;
}

function _km_build_textarea(array $field, string $name, string $id, bool $required, bool $cf7): string
{
  $attrs = [
    'name="' . esc_attr($name) . '"',
    'id="' . esc_attr($id) . '"',
  ];
  if ($cf7) {
    $classes = ['wpcf7-form-control', 'wpcf7-textarea'];
    if ($required) {
      $classes[] = 'wpcf7-validates-as-required';
    }
    $attrs[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';
  }
  if ($required)                      $attrs[] = 'required';
  if (!empty($field['autocomplete'])) $attrs[] = 'autocomplete="' . esc_attr($field['autocomplete']) . '"';
  $attrs[] = 'aria-describedby="' . esc_attr($name) . '-help"';

  $html = '<span class="c-input-item-input-body"><textarea ' . implode(' ', $attrs) . '></textarea></span>';

  if ($cf7) {
    return '<span class="wpcf7-form-control-wrap" data-name="' . esc_attr($name) . '">' . $html . '</span>';
  }

  return $html;
}

function _km_build_select(array $field, string $name, string $id, bool $required, bool $cf7): string
{
  $attrs = [
    'name="' . esc_attr($name) . '"',
    'id="' . esc_attr($id) . '"',
  ];
  if ($cf7) {
    $classes = ['wpcf7-form-control', 'wpcf7-select'];
    if ($required) {
      $classes[] = 'wpcf7-validates-as-required';
    }
    $attrs[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';
  }
  if ($required) $attrs[] = 'required';
  $attrs[] = 'aria-describedby="' . esc_attr($name) . '-help"';

  $options = '';
  if (!empty($field['first_option_label'])) {
    $options .= '<option value="" disabled selected>' . esc_html($field['first_option_label']) . '</option>';
  }
  foreach ((array)($field['options'] ?? []) as $opt) {
    $options .= '<option value="' . esc_attr($opt) . '">' . esc_html($opt) . '</option>';
  }

  $html = '<span class="c-input-item-input-body"><select ' . implode(' ', $attrs) . '>' . $options . '</select></span>';

  if ($cf7) {
    return '<span class="wpcf7-form-control-wrap" data-name="' . esc_attr($name) . '">' . $html . '</span>';
  }

  return $html;
}

function _km_build_choices(array $field, string $type, string $name, string $id, bool $required, bool $cf7): string
{
  $options = (array)($field['options'] ?? []);
  if (empty($options)) {
    return '';
  }

  $default = $field['default'] ?? null;
  $items   = '';

  foreach ($options as $index => $option) {
    $itemId    = $id . '-' . ($index + 1);
    $inputName = $type === 'checkbox' ? $name . '[]' : $name;
    $checked   = ($type === 'radio' && $option === $default) ? ' checked' : '';
    $reqAttr   = $required ? ' required' : '';

    $items .= '<label for="' . esc_attr($itemId) . '" class="c-input-radio-item">';
    $items .= '<span class="c-input-radio-item-body">';
    $items .= '<input type="' . esc_attr($type) . '" name="' . esc_attr($inputName) . '" id="' . esc_attr($itemId) . '" value="' . esc_attr($option) . '"' . $reqAttr . $checked . '>';
    $items .= '<span class="c-input-radio-item-elm"><span></span></span>';
    $items .= '<span class="c-input-radio-item-text">' . esc_html($option) . '</span>';
    $items .= '</span></label>';
  }

  $controlClasses = 'wpcf7-form-control wpcf7-' . $type;
  if ($cf7 && $required) {
    $controlClasses .= ' wpcf7-validates-as-required';
  }

  $inner = '<span class="' . esc_attr($controlClasses) . '">' . $items . '</span>';

  if ($cf7) {
    return '<span class="wpcf7-form-control-wrap" data-name="' . esc_attr($name) . '">' . $inner . '</span>';
  }

  return $inner;
}
