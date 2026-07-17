<?php

/**
 * Local Admin Auto Login
 * ローカル環境のみ
 * ログイン画面にユーザー選択UI
 */

add_action('init', function () {

  $host = $_SERVER['HTTP_HOST'] ?? '';
  $ip   = $_SERVER['REMOTE_ADDR'] ?? '';

  $is_local =
    wp_get_environment_type() === 'local' ||
    $ip === '127.0.0.1' ||
    $ip === '::1' ||
    strpos($host, 'localhost') !== false ||
    preg_match('/\.(local|dev|test|wp)$/', $host);

  // -----------------------------
  // ローカル以外 → 削除
  // -----------------------------

  if (!$is_local) {

    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'wp-admin') !== false) {

      if (basename(__FILE__) === 'local-admin-auto-login.php') {
        @unlink(__FILE__);
      }
    }

    return;
  }

  // -----------------------------
  // quick login
  // -----------------------------

  if (isset($_GET['quick_login'])) {

    $user_id = (int) $_GET['quick_login'];

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    wp_redirect(admin_url());
    exit;
  }
});


// =====================================
// ログイン画面 UI
// =====================================

add_action('login_form', function () {

  $host = $_SERVER['HTTP_HOST'] ?? '';
  $ip   = $_SERVER['REMOTE_ADDR'] ?? '';

  $is_local =
    wp_get_environment_type() === 'local' ||
    $ip === '127.0.0.1' ||
    $ip === '::1' ||
    strpos($host, 'localhost') !== false ||
    preg_match('/\.(local|dev|test|wp)$/', $host);

  if (!$is_local) return;

  // 全ユーザー取得
  $users = get_users([
    'orderby' => 'ID',
    'order'   => 'ASC'
  ]);

  if (!$users) return;

  echo '<div>';
  echo '<strong>Quick Login (Local)</strong><br>';

  foreach ($users as $user) {

    $url = add_query_arg(
      'quick_login',
      $user->ID,
      wp_login_url()
    );

    $roles = implode(', ', $user->roles);

    echo '<p>';
    echo '<a class="button button-primary" style="width:100%;margin-bottom:8px" href="' . esc_url($url) . '">';
    echo esc_html($user->user_login . ' (' . $roles . ')');
    echo '</a>';
    echo '</p>';
  }

  echo '</div>';
});
