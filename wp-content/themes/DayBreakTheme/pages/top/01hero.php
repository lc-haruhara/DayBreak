<?php
$hero_images = [
  ['file' => 'hero-image1.jpg', 'alt' => ''],
  ['file' => 'hero-image2.jpg', 'alt' => ''],
  ['file' => 'hero-image3.jpg', 'alt' => ''],
  ['file' => 'hero-image4.jpg', 'alt' => ''],
];
?>
<section class="p-top-hero">
  <div class="p-top-hero-body">

    <!--::::::::::::::::::::::::::::::::::::::::::::

      BackgroundCatch

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-hero-background-catch">
      <div class="p-top-hero-background-catch-body">

        <!-- Item ::::::::::::::::::::::::::::-->
        <div class="p-top-hero-background-catch-item">
          <?php
          C_Elements('TextLoop', [
            'text' => ['Every Daybreak', 'Begins in Okinawa'],
            'speed' => 200,
          ]);
          ?>
        </div>

        <!-- Item ::::::::::::::::::::::::::::-->
        <div class="p-top-hero-background-catch-item">
          <?php
          C_Elements('TextLoop', [
            'text' => ['世界へ。', '沖縄から、'],
            'speed' => 80,
            'class' => ['is-jp'],
          ]);
          ?>
        </div>
      </div>
    </div>

    <!--::::::::::::::::::::::::::::::::::::::::::::

      HeroImage

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-hero-image">
      <div class="p-top-hero-image-body">
        <?php foreach ($hero_images as $i => $image) : ?>
          <div class="p-top-hero-image-item">
            <img
              src="<?php echo esc_url(get_template_directory_uri() . '/images/' . $image['file']); ?>"
              alt="<?php echo esc_attr($image['alt']); ?>"
              <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
              decoding="async">
          </div>
        <?php endforeach; ?>
        <div class="p-top-hero-image-shutter" aria-hidden="true"></div>
      </div>
    </div>

    <?php
    C_Elements('DecorationCircle', [
      'rotate' => false,
    ]);
    ?>

    <!--::::::::::::::::::::::::::::::::::::::::::::

      Links

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-hero-links">
      <div class="p-top-hero-links-body">
        <?php
        C_Elements('ButtonLink', [
          'text' => 'Concept',
          'text_jp' => 'コンセプト',
          'url' => '/concept/',
          'icon' => 'chevron_forward',
        ]);
        ?>
        <?php
        C_Elements('ButtonLink', [
          'text' => 'Recruit',
          'text_jp' => '採用情報',
          'url' => '/recruit/',
          'icon' => 'chevron_forward',
          'class' => ['_type-primary'],
        ]);
        ?>
      </div>
    </div>

    <!--::::::::::::::::::::::::::::::::::::::::::::

      Scroll

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-hero-scroll" aria-hidden="true">
      <span class="p-top-hero-scroll-text">Scroll</span>
      <span class="p-top-hero-scroll-line"></span>
    </div>

  </div>
</section>