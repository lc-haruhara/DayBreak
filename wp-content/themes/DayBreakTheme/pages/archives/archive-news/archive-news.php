<section>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()): the_post(); ?>

      <div>
        <a href="<?php the_permalink(); ?>">
          <?php echo get_the_title(); ?>
        </a>
      </div>

    <?php endwhile; ?>
  <?php endif; ?>

</section>

<?php C_Parts('PaginationCommon'); ?>