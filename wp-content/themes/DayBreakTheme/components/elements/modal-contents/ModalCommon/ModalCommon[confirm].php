<?php
$id = $args['id'] ?? 'confirm';
?>

<div class="c-modal-header">
  <h2 id="modal-<?php echo esc_attr($id); ?>-title" class="c-modal-title">
    送信内容の確認
  </h2>
  <p id="modal-contents-<?php echo esc_attr($id); ?>-description" class="c-modal-description">
    以下の内容で送信してよろしければ「送信する」を押してください。
  </p>
</div>

<dl class="c-list-name" id="<?php echo esc_attr($id); ?>">
  <div class="c-list-name-item">
    <dt class="c-list-name-title">名前</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-name"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">メールアドレス</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-email"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">電話番号</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-tel"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">セレクトボックス</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-select"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">ラジオボタン</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-radio"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">チェックボックス</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-checkbox"></dd>
  </div>

  <div class="c-list-name-item">
    <dt class="c-list-name-title">お問い合わせ内容</dt>
    <dd class="c-list-name-detail" id="<?php echo esc_attr($id); ?>-contents"></dd>
  </div>
</dl>

<div class="c-input-confirm-window-buttons">

  <?php
  C_Elements('ButtonLink', [
    'text'  => '修正する',
    'icon'  => 'close',
    'icon_position'  => 'left',
    'aria'  => [
      'label'    => '修正する'
    ],
    'data' => [
      'modal-close' => 'true'
    ],
  ]);
  ?>
  <?php
  // 送信は <button> で行い、クリック時に隠れた #submit をトリガー（CF7 の送信処理をそのまま利用）
  C_Elements('ButtonLink', [
    'text'  => '送信する',
    'icon'  => 'send',
    'icon_position'  => 'right',
    'aria'  => [
      'label'    => '送信する'
    ],
    'data' => [
      'submit-trigger' => 'submit',
    ],
  ]);
  ?>
</div>