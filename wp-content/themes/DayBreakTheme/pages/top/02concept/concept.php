<section class="p-top-concept">
  <div class="p-top-concept-body">

    <!--::::::::::::::::::::::::::::::::::::::::::::
      HeroAnimationTrigger
    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-concept-trigger"></div>

    <!--::::::::::::::::::::::::::::::::::::::::::::

      Heading

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <?php
    C_Elements('HeadingSection', [
      'en' => 'Concept',
      'ja' => 'コンセプト',
      'tag' => 'h2',
    ]);
    ?>

    <!--::::::::::::::::::::::::::::::::::::::::::::

      Contents

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <div class="p-top-concept-contents">

      <!--::::::::::::::::::::::::::::::::::::::::::::
        Description
      ::::::::::::::::::::::::::::::::::::::::::::-->
      <div class="p-top-concept-contents-description">
        <p class="p-top-concept-contents-description-text">
          株式会社 DAY BREAK<br>
          私達は、沖縄を拠点に地域とともに成長しながら世界へ挑戦し続けるフードカンパニーです。
        </p>
        <p class="p-top-concept-contents-description-text">
          多様なブランドを展開し、「おいしい」のその先を作ります。
        </p>
        <?php
        C_Elements('ButtonLink', [
          'text' => '',
          'text_jp' => '',
          'url' => '/recruit/',
          'icon' => 'chevron_forward',
          'class' => ['_type-primary'],
        ]);
        ?>
      </div>

      <!--::::::::::::::::::::::::::::::::::::::::::::
        Image
      ::::::::::::::::::::::::::::::::::::::::::::-->
      <div class="p-top-concept-contents-image" data-js-scroll-target="once" data-js-parallax="translateY:-50->20" data-scrub="0.015">
        <!--::::::::::::::::::::::::::::::::::::::::::::
          Logo
        ::::::::::::::::::::::::::::::::::::::::::::-->
        <div
          class="p-top-concept-contents-image-logo"
          data-js-lottie
          data-lottie-src="<?php echo esc_url(get_template_directory_uri() . '/images/lottie/logo-day-break-main.json'); ?>">
        </div>
      </div>

    </div>

  </div>

  <!--::::::::::::::::::::::::::::::::::::::::::::

    BackgroundImage

  ::::::::::::::::::::::::::::::::::::::::::::-->
  <div class="p-top-concept-contents-background">
    <div class="p-top-concept-contents-background-item" data-js-scroll-target="once" data-js-parallax="translateY:0->-60" data-scrub="0.03">
      <div class="p-top-concept-contents-background-item-body" data-js-parallax="opacity:0->1">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/hero-image1.jpg'); ?>" alt="Concept Image">
      </div>
    </div>
    <div class="p-top-concept-contents-background-item" data-js-scroll-target="once" data-js-parallax="translateY:0->-30" data-scrub="0.015">
      <div class="p-top-concept-contents-background-item-body" data-js-parallax="opacity:0->1">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/hero-image2.jpg'); ?>" alt="Concept Image">
      </div>
    </div>
    <!-- <div class="p-top-concept-contents-background-item" data-js-scroll-target="once" data-js-parallax="translateY:0->-100" data-scrub="0.03">
      <div class="p-top-concept-contents-background-item-body" data-js-parallax="opacity:0->1">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/hero-image3.jpg'); ?>" alt="Concept Image">
      </div>
    </div>
    <div class="p-top-concept-contents-background-item" data-js-scroll-target="once" data-js-parallax="translateY:0->-100" data-scrub="0.015">
      <div class="p-top-concept-contents-background-item-body" data-js-parallax="opacity:0->1">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/hero-image4.jpg'); ?>" alt="Concept Image">
      </div>
    </div> -->
  </div>

</section>