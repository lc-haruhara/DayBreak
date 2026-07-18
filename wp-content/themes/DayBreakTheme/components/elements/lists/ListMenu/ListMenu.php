<?php
$contact = (bool) ($args['contact'] ?? false);
$pp = (bool) ($args['pp'] ?? false);
$english = (bool) ($args['english'] ?? false);
?>

<li class="item">
  <?php
  $logo_link = (is_home() || is_front_page()) ? '#home' : home_url('/');
  ?>
  <a href="<?= esc_url($logo_link); ?>" class="item-body">
    <?php if ($english) : ?>
      <span class="english">Home</span>
    <?php endif; ?>
    <span class="japanese">トップページ</span>
  </a>
</li>

<li class="item">
  <a href="/about" class="item-body">
    <?php if ($english) : ?>
      <span class="english">About Us</span>
    <?php endif; ?>
    <span class="japanese">会社概要</span>
  </a>
</li>

<li class="item">
  <a href="/news" class="item-body">
    <?php if ($english) : ?>
      <span class="english">Concept</span>
    <?php endif; ?>
    <span class="japanese">コンセプト</span>
  </a>
</li>

<li class="item">
  <a href="/news" class="item-body">
    <?php if ($english) : ?>
      <span class="english">Store</span>
    <?php endif; ?>
    <span class="japanese">店舗情報</span>
  </a>
</li>

<li class="item">
  <a href="/news" class="item-body">
    <?php if ($english) : ?>
      <span class="english">News</span>
    <?php endif; ?>
    <span class="japanese">お知らせ</span>
  </a>
</li>

<li class="item">
  <a href="/news" class="item-body">
    <?php if ($english) : ?>
      <span class="english">Recruit</span>
    <?php endif; ?>
    <span class="japanese">求人情報</span>
  </a>
</li>

<?php if ($contact) : ?>
  <li class="item">
    <a href="/contact" class="item-body">
      <?php if ($english) : ?>
        <span class="english">Contact</span>
      <?php endif; ?>
      <span class="japanese">お問い合わせ</span>
    </a>
  </li>
<?php endif; ?>

<?php if ($pp) : ?>
  <li class="item">
    <a href="/privacy-policy" class="item-body">
      <?php if ($english) : ?>
        <span class="english">Privacy Policy</span>
      <?php endif; ?>
      <span class="japanese">お問い合わせ</span>
    </a>
  </li>
<?php endif; ?>