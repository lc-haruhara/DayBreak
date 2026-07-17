<section class="p-single-contents">

  <!-- Title -->
  <h1 class="p-single-contents-page-title">
    <span><?php echo get_the_title(); ?></span>
  </h1>

  <!-- Date -->
  <time class="p-single-contents-date">
    <!-- <?php echo get_post_time('F jS, Y'); //July 5th, 2018 
          ?> -->
    <!-- <?php echo get_post_time('Y年 n月 j日'); //2018年 7月 5日 
          ?> -->
    <!-- <?php echo get_post_time('Y年 m月 d日'); //2018年 07月 05日 
          ?> -->
    <?php echo get_post_time('Y / m / d'); //2018 / 07 / 04 
    ?>
  </time>

  <!-- SandwichEditor -->
  <?php C_Parts('SandwichEditorDefault'); ?>

  <!-- ShareButton -->
  <?php C_Elements('ListSnsShare'); ?>

</section>

inc