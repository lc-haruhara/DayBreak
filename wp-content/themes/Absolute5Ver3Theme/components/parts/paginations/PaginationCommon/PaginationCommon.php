<?php
global $wp_query;

if (!$wp_query || $wp_query->max_num_pages <= 1) {
  return;
}

$current = max(1, get_query_var('paged'), get_query_var('page'));
?>
<div class="c-pagination">

  <?php
  $maxPages = $wp_query->max_num_pages;
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  ?>

  <a
    href="<?php global $wp_query;
          select_pagination_new($maxPages, $paged); ?>"
    class="c-pagination-btn _prev">
    <
      </a>

      <div class="c-input-item _type-select">
        <div class="c-input-text">
          <span>ページ</span>
        </div>
        <div class="c-input-input">
          <?php wp_pagenavi(); ?>
        </div>
        <div class="c-input-text">
          / <?php echo $maxPages ?>
        </div>
      </div>

      <a
        href="<?php global $wp_query;
              select_pagination_old($maxPages, $paged); ?>"
        class="c-pagination-btn _next">
        >
      </a>

</div>