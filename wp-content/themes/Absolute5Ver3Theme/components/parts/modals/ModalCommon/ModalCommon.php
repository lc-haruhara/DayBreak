<?php

$id      = $args['id']      ?? '';
$content = $args['content'] ?? '';
$close_buttons = $args['close_buttons'] ?? true;

?>

<div
  class="c-modal-wrap"
  id="modal-<?= esc_attr($id); ?>"
  role="dialog"
  aria-modal="true"
  aria-hidden="true"
  aria-labelledby="modal-<?= esc_attr($id); ?>-title"
  aria-describedby="modal-contents-<?= esc_attr($id); ?>-description">

  <div class="c-modal-wrap-inner">
    <div class="c-modal-body">

      <?php
      //::::::::::::::::::::::::::::::::::::::::::
      // CloseButton
      //::::::::::::::::::::::::::::::::::::::::::
      ?>
      <button
        class="c-modal-close-button"
        aria-label="モーダルを閉じる"
        data-modal-close>
      </button>

      <?php
      //::::::::::::::::::::::::::::::::::::::::::
      // Inner
      //::::::::::::::::::::::::::::::::::::::::::
      ?>
      <div class="c-modal-body-inner">

        <?php
        if (!empty($id)) {
          C_Elements('ModalCommon[' . $id . ']', [
            'id' => $id
          ]);
        }
        ?>

        <?php
        //::::::::::::::::::::::::::::::::::::::::::
        // CloseButton
        //::::::::::::::::::::::::::::::::::::::::::
        ?>
        <?php if ($close_buttons) : ?>
          <div class="c-modal-contents-buttons">
            <?php
            C_Elements('ButtonLink', [
              'text'  => '閉じる',
              'icon'  => 'close',
              'iconPosition'  => 'left',
              'aria'  => [
                'label'    => 'モーダルを閉じる'
              ],
              'data' => [
                'modal-close' => 'true'
              ],
            ]);
            ?>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <div class="c-modal-close-ovl" data-modal-close></div>
  </div>
</div>