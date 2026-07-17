<!-- Description ::::::::::::::::::::::::::::::::::::::::::::

	このファイルはABSOLUTE5のデモページ用のサンプルファイルです。
	削除してください。

:::::::::::::::::::::::::::::::::::::::::::: -->

<style>
  .p-top-sample {
    width: 100%;
    height: 100svh;
    position: relative;
    overflow: clip;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .p-top-sample .p-top-sample-body {
    width: 30%;
    z-index: 100;
    position: relative;
    text-align: center;
  }

  .p-top-sample .main {
    border-radius: 30px;
  }

  .p-top-sample .background {
    height: 100%;
    object-fit: cover;
    position: absolute;
    margin: auto;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 10;
    filter: brightness(0.5) blur(10px);
    scale: 1.2;
  }

  .scroll-down {
    width: 20px;
    height: 20px;
    border-bottom: 2px solid #fff;
    border-right: 2px solid #fff;
    content: "";
    margin: auto;
    position: absolute;
    right: 0;
    bottom: 4rem;
    left: 0;
    rotate: 45deg;
    z-index: 100;
  }

  section.common {
    width: 100%;
    padding: 8rem 2rem;
    background: #1e1d21;
    color: #fff;
  }

  section.common:nth-of-type(odd) {
    background: rgb(24 23 26)
  }

  section.common .common-body {
    width: min(100%, 1000px);
    margin: auto;
  }

  h2.common-heading {
    margin-bottom: 2rem;
  }

  .c-btn-link,
  .common-btn {
    border-radius: 50px;
    background: #fff;
    font-size: 0.9rem;
    padding: 0.75em 3em;
    margin-top: 2rem;
    display: inline-block;
    transition: 0.15s ease;
  }

  .common-btn:has(.icon) .common-btn-body {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
  }

  .common-btn .icon {
    width: 20px;
  }

  .common-btn .text {
    flex: 1;
  }

  .common-btn.is-on {
    color: #fff;
    background: #7801ff;
  }

  .common-scroll-element {
    width: 300px;
    height: 300px;
    background: #fff;
    transition: 1s ease;
    margin-top: 1000px;
    color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
  }

  .common-scroll-element.is-on {
    background: #7801ff;
    color: #fff;
    translate: 100%;
    rotate: 360deg;
  }

  .common-modal {
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    opacity: 0;
    pointer-events: none;
    transition: 0.5s ease;
    color: #fff;
  }

  .common-modal.is-open {
    opacity: 1;
    pointer-events: auto;
  }

  .c-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 1s ease;
    opacity: 1;
    background: #000;
  }

  .c-loading::after {
    content: "Now Loading...";
    color: #fff;
    font-weight: 700;
  }
</style>


<!--::::::::::::::::::::::::::::::::::::::::::::
	HERO
::::::::::::::::::::::::::::::::::::::::::::-->
<section class="p-top-sample">
  <div class="p-top-sample-body">
    <img class="main" src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="">
    <a href="https://docs.craft.do/editor/d/46223f79-ad83-b73c-9c57-c8780bec99f2/6196c339-8faf-4500-83b5-983b9792a1df?s=Y9Xeu5uw7aUSW66MTLwxxr6dWWX7VNRLNTmiRAGtFH6n" class="common-btn" target="_blank" rel="noopener noreferrer">
      <div class="common-btn-body">
        <div class="icon">
          <svg width="111" height="111" viewBox="0 0 111 111" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_43_2)">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M59.6199 107.6C59.6199 109.42 61.0899 110.89 62.9099 110.89H107.7C109.52 110.89 111 109.41 110.88 107.6C109.25 81.5398 88.9699 61.0598 62.9099 59.4398C61.0999 59.3298 59.6199 60.8098 59.6199 62.6198V107.6Z" fill="#1B1B1B" />
              <path fill-rule="evenodd" clip-rule="evenodd" d="M51.65 62.7199C51.65 60.8999 50.18 59.4299 48.36 59.4299H3.18999C1.36999 59.4299 -0.110008 60.9099 0.00999179 62.7199C1.63999 88.7799 22.3 109.26 48.36 110.88C50.17 110.99 51.65 109.51 51.65 107.7V62.7199Z" fill="#1B1B1B" />
              <path fill-rule="evenodd" clip-rule="evenodd" d="M51.65 48.1699C51.65 49.9899 50.18 51.4599 48.36 51.4599H3.18999C1.36999 51.4599 -0.110008 49.9799 0.00999179 48.1699C1.62999 22.1099 22.3 1.6299 48.36 -9.85744e-05C50.17 -0.110099 51.65 1.3699 51.65 3.1799V48.1599V48.1699Z" fill="#1B1B1B" />
              <path fill-rule="evenodd" clip-rule="evenodd" d="M59.6199 3.29C59.6199 1.47 61.0899 0 62.9099 0H107.7C109.52 0 111 1.48 110.88 3.29C109.25 29.35 88.9699 49.83 62.9099 51.45C61.0999 51.56 59.6199 50.08 59.6199 48.27V3.29Z" fill="#1B1B1B" />
            </g>
            <defs>
              <clipPath id="clip0_43_2">
                <rect width="110.89" height="110.89" fill="white" />
              </clipPath>
            </defs>
          </svg>
        </div>
        <span class="text" lang="en">Craft Doc</span>
      </div>
    </a>
  </div>
  <img class="background" src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="">
  <span class="scroll-down"></span>
</section>

<!--::::::::::::::::::::::::::::::::::::::::::::
	JavaScript のトリガー命名規則の変更
::::::::::::::::::::::::::::::::::::::::::::-->
<div id="js"></div>
<section class="common">
  <!-- wp-env test -->
  <div class="common-body">
    <h2 class="common-heading"><span>JavaScript のトリガー命名規則の変更 DEMO</span></h2>
    <button class="common-btn" data-js-toggle-on><span lang="en">Click Me!</span></button>
  </div>
</section>

<!--::::::::::::::::::::::::::::::::::::::::::::
	ScrollTarget のトリガー命名規則の変更
::::::::::::::::::::::::::::::::::::::::::::-->
<div id="scroll-target"></div>
<section class="common">
  <div class="common-body">

    <h2 class="common-heading"><span>ScrollTarget のトリガー命名規則の変更 DEMO</span></h2>
    <div class="common-scroll-element" data-js-scroll-target lang="en">
      data-js-scroll-target
    </div>
    <div class="common-scroll-element" data-js-scroll-target="once" lang="en">
      data-js-scroll-target="once"
    </div>

  </div>
</section>

<!--::::::::::::::::::::::::::::::::::::::::::::
	Modal用のJS
::::::::::::::::::::::::::::::::::::::::::::-->
<div id="modal"></div>
<section class="common">
  <div class="common-body">

    <?php
    C_Elements('InputFieldChoice', [
      'name'    => 'category',
      'label'   => 'カテゴリー',
      'type'    => 'radio',
      'options' => ['すべて', 'ニュース', 'ブログ'],
      'default' => 'すべて',
    ]);
    ?>

    <h2 class="common-heading"><span>Modal用のJS DEMO</span></h2>
    <?php
    C_Elements('ButtonLink', [
      'icon'  => 'chevron_forward',
      'text'  => 'Open hogehoge modal',
      'data' => [
        'modal-open' => 'hogehoge'
      ],
      'aria'  => [
        'controls' => 'modal-hogehoge',
        'expanded' => 'false',
        'label'    => 'Open hogehoge modal'
      ],
    ]);
    ?>
    <?php
    C_Elements('ButtonLink', [
      'icon'  => 'bucket_check',
      'icon_position'  => 'left',
      'text'  => 'Open mogemoge modal',
      'data' => [
        'modal-open' => 'mogemoge'
      ],
      'aria'  => [
        'controls' => 'modal-mogemoge',
        'expanded' => 'false',
        'label'    => 'Open mogemoge modal'
      ],
    ]);
    ?>
    <?php
    C_Elements('ButtonLink', [
      'url'   => '/news/123/',
      'aria'  => [
        'label' => 'Example'
      ],
    ]);
    ?>
    <?php
    C_Elements('ButtonLink', [
      'url'   => 'https://example.com',
      'aria'  => [
        'label' => 'Example'
      ],
    ]);
    ?>

    <?php
    C_Elements('IconCommon', [
      'icon'  => 'chevron_forward',
      'aria' => [
        'label' => '会社概要'
      ]
    ]);
    ?>

    <?php
    C_Elements('IconCommon', [
      'icon'  => 'chevron_forward',
      'button'  => 'true',
      'aria' => [
        'label' => '会社概要'
      ]
    ]);
    ?>

    <?php
    C_Elements('IconCommon', [
      'span'  => true,
      'svg'  => 'sns/color/x.svg',
    ]);
    ?>

    <?php
    C_Elements('IconCommon', [
      'icon'  => 'chevron_forward',
      'url' => 'https://google.com',
      'size'  => 'xxxxx',
      'color'  => 'xxxxx',
      'aria' => [
        'label' => '会社概要'
      ]
    ]);
    ?>

  </div>
</section>

<!--::::::::::::::::::::::::::::::::::::::::::::
	ViewTransitionTest
::::::::::::::::::::::::::::::::::::::::::::-->
<div id="modal"></div>
<section class="common" data-js-scroll-sample>
  <div class="common-body">

    <h2 class="common-heading"><span lang="en">ViewTransitionTest</span></h2>
    <a href="https://absolute5v3.mag-dev.xyz/news/123/" class="common-btn"><span lang="en">Link</span></a>

  </div>
</section>