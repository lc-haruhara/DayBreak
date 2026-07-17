<?php
$id = $args['id'] ?? '';
?>

<?php
$heading_en = 'Privacy Policy';
$heading_jp = 'プライバシーポリシー';
$heading_mb   = 2;
$text_center   = true;
$heading_id = 'modal-' . $id . '-title';
require COMPONENTS_ELEMENTS . 'Heading/HeadingSection.php';
?>

<div class="c-modal-contents" id="modal-<?= esc_attr($id); ?>-contents">
  <h3 class="c-modal-contents-heading">
    モーダルのセクションタイトル
  </h3>
</div>