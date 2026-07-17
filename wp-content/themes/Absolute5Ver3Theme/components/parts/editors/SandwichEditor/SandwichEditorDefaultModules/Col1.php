<?php if (have_rows('col1')) : // 1カラム目を取得 
?>
  <?php while (have_rows('col1')) : the_row(); ?>

    <?php if (have_rows('ImageGroup1')) : // 画像設定のグループを取得 
    ?>
      <?php while (have_rows('ImageGroup1')) : the_row(); // ループ 
      ?>

        <?php
        $image1 = get_sub_field('image1');
        if ($image1) : // image1 が存在する場合のみ出力
        ?>
          <!-- Img -->
          <figure class="c-swe-grid-img-wrap">
            <?php
            $alt1 = !empty($image1['alt']) ? $image1['alt'] : $image1['title']; // alt が空ならタイトルを使用
            echo '<img src="' . esc_url($image1['url']) . '" alt="' . esc_attr($alt1) . '">';
            ?>
            <?php if (get_sub_field('caption1')) : // caption1 があれば表示 
            ?>
              <figcaption class="c-swe-grid-caption" data-js-auto-hyper-link><?php the_sub_field('caption1'); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; // image1 の存在確認終了 
        ?>

      <?php endwhile; ?>
    <?php endif; // 画像設定のグループを取得 End 
    ?>

    <!-- Editor1 -->
    <?php if (get_sub_field('editor1')) : // editor1 に対しての値があれば表示 
    ?>
      <div class="c-swe-grid-text c-md-basic-paragraph" data-js-auto-hyper-link><?php the_sub_field('editor1'); ?></div>
    <?php endif; ?>

  <?php endwhile; // End ---------------------------------- 
  ?>
<?php endif; ?>