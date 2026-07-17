<div class="c-swe-list">
  <dl class="c-list-name">
    <?php if (have_rows('ListNameRepeat')) : ?>
      <?php while (have_rows('ListNameRepeat')) : the_row(); ?>
        <dt class="c-list-name-title"><?php the_sub_field('Name'); ?></dt>
        <dd class="c-list-name-detail" data-js-auto-hyper-link><?php the_sub_field('text'); ?></dd>
      <?php endwhile; ?>
    <?php else : ?>
    <?php endif; ?>
  </dl>
</div>