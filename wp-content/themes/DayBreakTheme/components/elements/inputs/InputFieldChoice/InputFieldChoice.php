<?php
$isCf7 = isset($args['field']);
$field  = $isCf7 ? $args['field'] : $args;

$label = $field['label'] ?? '';
$tag   = $isCf7 ? km_cf7_build_tag($field) : km_build_input_tag($field);

if ($tag === '') {
  return;
}
?>

<fieldset class="c-input-radio">
  <legend class="c-input-label">
    <?= esc_html($label); ?>
  </legend>
  <?= $tag; ?>
</fieldset>