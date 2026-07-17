<?php
$contact = (bool) ($args['contact'] ?? false);
$pp = (bool) ($args['pp'] ?? false);
?>

<li class="item">
  <?php
  $logo_link = (is_home() || is_front_page()) ? '#home' : home_url('/');
  ?>
  <a href="<?= esc_url($logo_link); ?>" class="item-body">
    Home
  </a>
</li>

<li class="item">
  <a href="/about" class="item-body">
    About
  </a>
</li>

<li class="item">
  <a href="/news" class="item-body">
    News
  </a>
</li>

<?php if ($contact) : ?>
  <li class="item">
    <a href="/contact" class="item-body">
      Contact
    </a>
  </li>
<?php endif; ?>

<?php if ($pp) : ?>
  <li class="item">
    <a href="/privacy-policy" class="item-body">
      Privacy Policy
    </a>
  </li>
<?php endif; ?>