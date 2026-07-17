<!DOCTYPE html>
<html lang="ja" class="no-js">

<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#">

  <meta charset="utf-8">

  <!--::::::::::::::::::::::::::::::::::::::::::::
    Head
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <!-- Generate -->
  <?php wp_head(); ?>

  <!--::::::::::::::::::::::::::::::::::::::::::::
    GA4 Consent Mode (default: denied)
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('consent', 'default', {
      analytics_storage: 'denied',
      ad_storage: 'denied',
      ad_user_data: 'denied',
      ad_personalization: 'denied',
      wait_for_update: 500
    });
  </script>
  <!-- /Generate -->

  <!--::::::::::::::::::::::::::::::::::::::::::::
    scheme.org
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <?php require COMPONENTS_UTILITY . 'SchemeOrg.php'; ?>

  <!--::::::::::::::::::::::::::::::::::::::::::::
    RequireMobile
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
  <meta name="format-detection" content="telephone=no">
  <meta name="msapplication-tap-highlight" content="no">

  <!--::::::::::::::::::::::::::::::::::::::::::::
    Favicon - https://realfavicongenerator.net/
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon.svg" sizes="any" />
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon.ico" sizes="48x48" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/favicons/favicon-96x96.png" sizes="96x96" />
  <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicons/apple-touch-icon.png" />
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/favicons/site.webmanifest" />

  <!--::::::::::::::::::::::::::::::::::::::::::::
    RssFeed
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>">

  <!--::::::::::::::::::::::::::::::::::::::::::::
    Fonts（設定は functions/config/fonts.config.php）
  ::::::::::::::::::::::::::::::::::::::::::::-->
  <?php absolute5_render_fonts(); ?>

</head>