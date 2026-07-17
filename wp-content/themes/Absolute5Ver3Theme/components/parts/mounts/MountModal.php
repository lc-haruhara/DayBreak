<?php
// :::::::::::::::::::::::::::::::::::
// モーダルをまとめてインクルードしておくファイル
// :::::::::::::::::::::::::::::::::::
ob_start();
?>

<?php
// :::::::::::::::::::::::::::::::::::
// 全ページ共通のモーダルが必要な場合はここにインクルード
// :::::::::::::::::::::::::::::::::::
?>

<?php
//Sample ※必ず消してください
C_Parts('ModalCommon', [
  'id'      => 'hogehoge',
]);
?>
<?php
//Sample ※必ず消してください
C_Parts('ModalCommon', [
  'id'      => 'mogemoge',
]);
?>

<?php
// :::::::::::::::::::::::::::::::::::
// Contact のみ
// :::::::::::::::::::::::::::::::::::
if (is_page('contact')) : ?>

  <?php
  // プライバシーポリシー
  C_Parts('ModalCommon', [
    'id'      => 'pp',
  ]);
  ?>

  <?php
  // 入力内容確認
  C_Parts('ModalCommon', [
    'id'      => 'confirm',
    'close_buttons' => false,
  ]);
  ?>

<?php endif; ?>

<?php
// :::::::::::::::::::::::::::::::::::
// Hogehoge のみ
// :::::::::::::::::::::::::::::::::::
if (is_page('hogehoge')) : ?>

  <?php
  // ここに内容
  C_Parts('ModalCommon', [
    'id'      => 'hogehoge',
  ]);
  ?>

<?php endif; ?>

<?php
// :::::::::::::::::::::::::::::::::::
// 出力
// :::::::::::::::::::::::::::::::::::
$modal_content = trim(ob_get_clean());
if ($modal_content) :
?>

  <div class="l-main-root-modal" aria-hidden="true" inert>
    <?php echo $modal_content; ?>
  </div>

<?php endif; ?>