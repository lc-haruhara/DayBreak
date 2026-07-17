<?php
$name  = $args['name'] ?? 'your-pp-confirm';
$label = $args['label'] ?? 'プライバシーポリシーに同意する';
?>
<div class="c-input-pp-confirm">

  <p class="c-input-pp-confirm-paragraph">
    <button
      class="c-btn-link _icon-right"
      type="button"
      data-modal-open="pp"
      aria-controls="modal-pp"
      aria-expanded="false"
      aria-label="プライバシーポリシーを確認する">
      プライバシーポリシー
    </button>
    の内容をご確認いただきご同意のうえ、送信ボタンを押してください。
  </p>

  <div class="c-input-radio">
    <label for="<?= esc_attr($name); ?>" class="c-input-radio-item">
      <span class="c-input-radio-item-body">
        <input
          type="checkbox"
          name="<?= esc_attr($name); ?>"
          id="<?= esc_attr($name); ?>">
        <span class="c-input-radio-item-elm"><span></span></span>
        <span class="c-input-radio-item-text"><?= esc_html($label); ?></span>
      </span>
    </label>
  </div>

</div>