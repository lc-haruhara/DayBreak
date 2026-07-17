<?php if (have_rows('col2')) : //2カラム目を取得 
?>
  <?php while (have_rows('col2')) : the_row(); ?>

    <?php if (have_rows('ImageGroup2')) : //画像設定のグループを取得 
    ?>
      <?php while (have_rows('ImageGroup2')) :  the_row(); //ループ 
      ?>

        <?php
        $image2 = get_sub_field('image2');
        if ($image2) : // image2 が存在する場合のみ出力
        ?>

          <!-- Img -->
          <figure class="c-swe-grid-img-wrap">
            <?php
            $image2 = get_sub_field('image2');
            if ($image2) {
              $alt2 = !empty($image2['alt']) ? $image2['alt'] : $image2['title']; // altが空ならタイトルを使用
              echo '<img src="' . esc_url($image2['url']) . '" alt="' . esc_attr($alt2) . '">';
            }
            ?>
            <?php if (get_sub_field('caption2')): //caption2に対しての値があれば表示 
            ?>
              <figcaption class="c-swe-grid-caption" data-js-auto-hyper-link><?php the_sub_field('caption2'); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; // image2 の存在確認終了 
        ?>

      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; //画像設定のグループを取得END ------------------------------ 
    ?>

    <!-- Editor -->
    <?php if (get_sub_field('editor2')): //editor2に対しての値があれば表示 
    ?>
      <div class="c-swe-grid-text c-md-basic-paragraph" data-js-auto-hyper-link><?php the_sub_field('editor2'); ?></div>
    <?php endif; ?>

  <?php endwhile; //End ---------------------------------- 
  ?>
<?php else : ?>
<?php endif; ?>