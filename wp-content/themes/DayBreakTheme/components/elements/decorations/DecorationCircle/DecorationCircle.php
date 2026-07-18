<?php

/* ==========================================================
   Args
========================================================== */

$rotate = $args['rotate'] ?? true; // false で回転を停止

$classes = ['c-deco-circle-text'];

if (!$rotate) {
  $classes[] = '_no-rotate';
}
?>

<span class="<?= esc_attr(implode(' ', $classes)); ?>" aria-hidden="true">
  <div class="c-deco-circle-text-svg">
    <div class="c-deco-circle-text-svg-body">
      <svg viewBox="0 0 100 100">
        <defs>
          <path id="circlePath" d="M50,50 m-37,0 a37,37 0 1,1 74,0 a37,37 0 1,1 -74,0"></path>
        </defs>
        <text>
          <textPath href="#circlePath" startOffset="50%" text-anchor="middle" lang="en">
            食を通じて、新しい価値を創造する
          </textPath>
        </text>
      </svg>
    </div>
  </div>
  <div class="c-deco-circle-text-svg">
    <div class="c-deco-circle-text-svg-body">
      <svg viewBox="0 0 100 100">
        <defs>
          <path id="circlePath" d="M50,50 m-37,0 a37,37 0 1,1 74,0 a37,37 0 1,1 -74,0"></path>
        </defs>
        <text>
          <textPath href="#circlePath" startOffset="50%" text-anchor="middle" lang="en">
          </textPath>
        </text>
      </svg>
    </div>
  </div>
</span>