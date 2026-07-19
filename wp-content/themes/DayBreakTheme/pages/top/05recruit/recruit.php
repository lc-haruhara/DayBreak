<section class="p-top-recruit">
  <div class="p-top-recruit-body">

    <!--::::::::::::::::::::::::::::::::::::::::::::

      Heading

    ::::::::::::::::::::::::::::::::::::::::::::-->
    <?php
    C_Elements('HeadingSection', [
      'en' => 'Recruit',
      'ja' => '採用情報',
      'tag' => 'h2',
      'align' => 'center',
    ]);
    ?>

    <div class="p-top-recruit-description">
      <h3 class="p-top-recruit-description-heading">
        「おいしい」のその先へ。
      </h3>
      <p class="p-top-recruit-description-text">
        株式会社 DAY BREAK は、共に挑戦する仲間を募集しています。
        沖縄を拠点に、多様なブランドで世界へ。その一歩を、あなたと踏み出したい。
      </p>
    </div>

    <?php
    C_Parts('ListNumbers', []);
    ?>
    <div class="p-top-recruit-links">
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
  <div class="p-top-recruit-background">
    <div class="p-top-recruit-background-body" data-js-parallax="translateX:-50->-20" data-scrub="0.015">
      <?php
      C_Elements('LogoCommon', []);
      ?>
    </div>
  </div>
</section>