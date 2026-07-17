<div class="c-swe-link-button">

  <?php if (get_sub_field('LinkButtonWindow')): //新しいウィンドウが選択されている場合----------------------- 
  ?>

    <a class="c-btn-link" target="_blank" rel="noopener noreferrer" href="<?php the_sub_field('LinkButtonUrl'); ?>">
      <?php the_sub_field('LinkButtonText'); ?>
    </a>

  <?php else: //新しいウィンドウが選択されていない場合----------------------- 
  ?>

    <a class="c-btn-link" href="<?php the_sub_field('LinkButtonUrl'); ?>">
      <?php the_sub_field('LinkButtonText'); ?>
    </a>

  <?php endif; ?>

</div>