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
            'speed' => 100,
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


  </div>
</section>