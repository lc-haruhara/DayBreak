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
      <div class="p-top-concept-contents-image" data-js-scroll-target="once">
        <div
          class="p-top-concept-contents-image-logo"
          data-js-lottie
          data-lottie-src="<?php echo esc_url(get_template_directory_uri() . '/images/lottie/logo-day-break-main.json'); ?>">
        </div>
      </div>

    </div>

  </div>
</section>