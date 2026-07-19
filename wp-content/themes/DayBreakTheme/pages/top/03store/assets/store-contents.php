<!--::::::::::::::::::::::::::::::::::::::::::::

  Detail

::::::::::::::::::::::::::::::::::::::::::::-->

<div class="p-top-store-contents-detail">

  <!--::::::::::::::::::::::::::::::::::::::::::::
    Heading
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <?php
  C_Elements('HeadingSection', [
    'en' => 'Store',
    'ja' => '店舗紹介',
    'tag' => 'h2',
  ]);
  ?>

  <dl class="p-top-store-contents-detail-contents">
    <div class="p-top-store-contents-detail-contents-item">
      <dt class="p-top-store-contents-detail-contents-title">国内店舗数</dt>
      <dd class="p-top-store-contents-detail-contents-value">
        <?php
        C_Elements('DecorationCounter', [
          'value' => 8,
          'unit'  => '店舗',
          'once'     => false,
          'digits' => 2,   // 00 から始まり 01,02...99
        ]);
        ?>
      </dd>
    </div>
    <div class="p-top-store-contents-detail-contents-item">
      <dt class="p-top-store-contents-detail-contents-title">海外店舗数</dt>
      <dd class="p-top-store-contents-detail-contents-value">
        <?php
        C_Elements('DecorationCounter', [
          'value' => 99,
          'unit'  => '店舗',
          'once'     => false,
          'digits' => 2,   // 00 から始まり 01,02...99
        ]);
        ?>
      </dd>
    </div>
  </dl>

  <!--::::::::::::::::::::::::::::::::::::::::::::
    ButtonLink
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <?php
  C_Elements('ButtonLink', [
    'text' => '',
    'text_jp' => '',
    'url' => '/store/',
    'icon' => 'chevron_forward',
    'class' => ['_type-primary'],
  ]);
  ?>

</div>


<!--::::::::::::::::::::::::::::::::::::::::::::

  Swiper

::::::::::::::::::::::::::::::::::::::::::::-->

<?php
// images/logo-store{n}.svg に対応。左列＝下方向、右列＝上方向に流す
$store_logos = [
  'down' => ['store1', 'store2', 'store3', 'store4', 'store5'],
  'up'   => ['store6', 'store7', 'store8', 'store9', 'store10'],
];
?>

<div class="p-top-store-contents-images">

  <?php foreach ($store_logos as $direction => $logos) : ?>
    <div class="p-top-store-contents-images-group swiper" data-js-store-slider="<?= esc_attr($direction); ?>">
      <div class="swiper-wrapper">
        <?php foreach ($logos as $logo) : ?>
          <div class="swiper-slide p-top-store-contents-images-item">
            <?php C_Elements('LogoCommon', [
              'name'   => $logo,
              'inline' => false,
            ]); ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

</div>