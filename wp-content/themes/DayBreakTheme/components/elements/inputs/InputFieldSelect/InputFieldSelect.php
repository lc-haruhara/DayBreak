<?php
$isCf7 = isset($args['field']);
$field  = $isCf7 ? $args['field'] : $args;

$name       = $field['name'] ?? '';
$label      = $field['label'] ?? '';
$required   = !empty($field['required']);
$labelClass = 'c-input-label' . (!$required ? ' _any' : '');
$tag        = $isCf7 ? km_cf7_build_tag($field) : km_build_input_tag($field);

if ($name === '' || $tag === '') {
  return;
}
?>

<div class="c-input-item">
  <label for="<?= esc_attr($name); ?>" class="<?= esc_attr($labelClass); ?>">
    <?= esc_html($label); ?>
  </label>
  <div class="c-input-item-input">
    <?= $tag; ?>
  </div>
</div>