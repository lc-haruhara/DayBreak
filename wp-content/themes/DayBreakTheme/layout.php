<?php get_header(); ?>

<body <?php body_class($body_class ?? ''); ?> ontouchstart="">
  <div id="home"></div>

  <?php
  //::::::::::::::::::::::::::::::::::::
  // ページの共通要素をインクルード MountContentsCommon.php に個別インクルード
  // 追従ロゴやグロナビやドロワーメニューなど
  //::::::::::::::::::::::::::::::::::::
  C_Parts('MountContentsCommon');
  ?>

  <div class="l-main-root-wrap
    <?php
    //Loadingが全ページに入る場合は is-loading をベタ書き
    if (is_home() || is_front_page()) echo ' is-loading';
    ?>
    ">

    <main>

      <?php
      if (!empty($sections)) {
        foreach ((array) $sections as $section) {
          $path = get_theme_file_path($section);
          if (file_exists($path)) {
            require $path;
          }
        }
      } else {
        echo 'Error Page.';
      }
      ?>

    </main>

    <?php
    // ::::::::::::::::::::::::::::::::::::
    // ページ固有コンテンツ
    // ::::::::::::::::::::::::::::::::::::
    C_Parts('MountContentsPage'); ?>

    <?php
    // ::::::::::::::::::::::::::::::::::::
    //Footer本体
    // ::::::::::::::::::::::::::::::::::::
    C_Layouts('Footer'); ?>

  </div>


  <?php
  // :::::::::::::::::::::::::::::::::::
  // トップページのみにローディングインクルード
  // :::::::::::::::::::::::::::::::::::
  if (is_home() || is_front_page()) : ?>
    <?php C_Parts('LoadingCommon'); ?>
  <?php else: ?>
  <?php endif; ?>

  <?php
  // :::::::::::::::::::::::::::::::::::
  // モーダルコンテンツ
  // :::::::::::::::::::::::::::::::::::
  C_Parts('MountModal'); ?>

  <?php get_footer(); ?>
</body>

</html>