<?php
$id = $args['id'] ?? '';
?>

<h2 class="c-modal-heading"
  id="modal-<?= esc_attr($id); ?>-title">
  hogehoge モーダルのタイトル
</h2>

<p class="c-modal-contents-description"
  id="modal-contents-<?= esc_attr($id); ?>-description">
  モーダルのセクション説明
</p>

<!--::::::::::::::::::::::::::::::::::::::::::::
    Contents
  ::::::::::::::::::::::::::::::::::::::::::::-->