<?php
$id = $args['id'] ?? '';
?>

<h2 class="c-modal-heading"
  id="modal-<?= esc_attr($id); ?>-title">
  mogemoge モーダルのタイトル
</h2>

<h3 class="c-modal-contents-heading">
  モーダルのセクションタイトル
</h3>
<p class="c-modal-contents-description"
  id="modal-contents-<?= esc_attr($id); ?>-description">
  モーダルのセクション説明
</p>

<!--::::::::::::::::::::::::::::::::::::::::::::
    Contents
  ::::::::::::::::::::::::::::::::::::::::::::-->