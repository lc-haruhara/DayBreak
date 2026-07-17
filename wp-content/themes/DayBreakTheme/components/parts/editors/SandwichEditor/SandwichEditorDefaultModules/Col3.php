<?php if (have_rows('col3')) : //2カラム目を取得 
?>
  <?php while (have_rows('col3')) : the_row(); ?>

    <?php if (have_rows('ImageGroup3')) : //画像設定のグループを取得 
    ?>
      <?php while (have_rows('ImageGroup3')) :  the_row(); //ループ 
      ?>

        <?php
        $image3 = get_sub_field('image3');
        if ($image3) : // image3 が存在する場合のみ出力
        ?>

          <!-- Img -->
          <figure class="c-swe-grid-img-wrap">
            <?php
            $image3 = get_sub_field('image3');
            if ($image3) {
              $alt3 = !empty($image3['alt']) ? $image3['alt'] : $image3['title']; // altが空ならタイトルを使用
              echo '<img src="' . esc_url($image3['url']) . '" alt="' . esc_attr($alt3) . '">';
            }
            ?>
            <?php if (get_sub_field('caption3')): //caption3に対しての値があれば表示 
            ?>
              <figcaption class="c-swe-grid-caption" data-js-auto-hyper-link><?php the_sub_field('caption3'); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; // image3 の存在確認終了 
        ?>

      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; //画像設定のグループを取得END ------------------------------ 
    ?>

    <!-- Editor -->
    <?php if (get_sub_field('editor3')): //editor3に対しての値があれば表示 
    ?>
      <div class="c-swe-grid-text c-md-basic-paragraph" data-js-auto-hyper-link><?php the_sub_field('editor3'); ?></div>
    <?php endif; ?>

  <?php endwhile; //End ---------------------------------- 
  ?>
<?php else : ?>
<?php endif; ?>