<?php

/**
 * Plugin Name: Force HTTPS URL
 * Description: WordPress URL が http の場合、ダッシュボードまたは一般設定ページで https に自動修正
 */

function force_https_url_fix()
{
  if ('local' === wp_get_environment_type()) {
    return;
  }

  $home    = get_option('home');
  $siteurl = get_option('siteurl');

  // home
  if (strpos($home, 'http://') === 0) {
    update_option('home', preg_replace('#^http://#', 'https://', $home));
  }

  // siteurl
  if (strpos($siteurl, 'http://') === 0) {
    update_option('siteurl', preg_replace('#^http://#', 'https://', $siteurl));
  }
}

add_action('load-index.php', 'force_https_url_fix');          // /wp-admin/
add_action('load-options-general.php', 'force_https_url_fix'); // /wp-admin/options-general.php