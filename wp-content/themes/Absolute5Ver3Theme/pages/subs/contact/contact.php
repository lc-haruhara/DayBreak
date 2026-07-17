<section class="p-contact-main">

  <div class="c-input">
    <div class="c-input-body">

      <?php echo apply_shortcodes('[contact-form-7 id="98110d1" title="お問い合わせ"]'); ?>

      <?php
      //::::::::::::::::::::::::::::::::::::::::::::::::
      // 画面遷移を必要としない確認画面を使う場合はインクルード
      // components\Parts\IncludeModal.php に確認用モーダルをインクルード
      //::::::::::::::::::::::::::::::::::::::::::::::::
      include('assets/contact/contact-active-confirm.php');
      ?>

    </div>
  </div>

</section>

<?php
//::::::::::::::::::::::::::::::::::::::::::::::::
// ContactFormCommonScripts
//::::::::::::::::::::::::::::::::::::::::::::::::
include('assets/contact/contact-scripts.php');
?>