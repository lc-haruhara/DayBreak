<?php
$id = $args['id'] ?? '';
?>

<h2 class="c-modal-heading"
  id="modal-<?= esc_attr($id); ?>-title">
  プライバシーポリシー モーダルのタイトル
</h2>

<div class="c-modal-contents-description"
  id="modal-contents-<?= esc_attr($id); ?>-description">
  <!--::::::::::::::::::::::::::::::::::::::::::::
    Contents
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <?php
  C_Elements('ListPP', [
    'analytics'  => true,
    'turnstile'  => true,
  ]);
  ?>
</div>