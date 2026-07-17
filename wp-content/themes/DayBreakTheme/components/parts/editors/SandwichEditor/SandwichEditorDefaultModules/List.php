<div class="c-swe-list">
  <ul class="c-list-common">
    <?php if (have_rows('ListRepeat')) : ?>
      <?php while (have_rows('ListRepeat')) : the_row(); ?>
        <li class="c-list-common-item" data-js-auto-hyper-link><span><?php the_sub_field('text'); ?></span></li>
      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; ?>
  </ul>
</div>