<?php
/*
Plugin Name: WPS Hide Login Loader
Description: Keeps WPS Hide Login always active and provides settings page.
Author: LAUNCH CRAFT
Version: 1.0
*/

// --------------------------------
// ローカル判定
// --------------------------------
$host = $_SERVER['HTTP_HOST'] ?? '';

$is_local =
  wp_get_environment_type() === 'local' ||
  strpos($host, 'localhost') !== false ||
  strpos($host, '.local') !== false ||
  strpos($host, '.dev') !== false ||
  strpos($host, '.test') !== false ||
  strpos($host, '.wp') !== false;


// --------------------------------
// ローカルではプラグイン無効
// --------------------------------
if ($is_local) {

  add_filter('option_active_plugins', function ($plugins) {
    return array_diff($plugins, ['wps-hide-login/wps-hide-login.php']);
  });
} else {

  include_once WP_PLUGIN_DIR . '/wps-hide-login/wps-hide-login.php';
}
// --------------------------------
// 本番ではプラグイン読み込み
// --------------------------------
if ($is_local) {

  add_filter('option_active_plugins', function ($plugins) {
    return array_diff($plugins, ['wps-hide-login/wps-hide-login.php']);
  });
} else {

  include_once WP_PLUGIN_DIR . '/wps-hide-login/wps-hide-login.php';

  // 管理画面では「有効」に見せる
  add_action('admin_init', function () {

    $target = 'wps-hide-login/wps-hide-login.php';
    $active = get_option('active_plugins', []);

    if (!in_array($target, $active)) {
      $active[] = $target;
      update_option('active_plugins', $active);
    }
  });
}

// --------------------------------
// 管理画面メニュー
// --------------------------------
add_action('admin_menu', function () {

  add_options_page(
    'ログイン画面隠蔽',
    'ログイン画面隠蔽',
    'manage_options',
    'custom-login-url',
    'custom_login_url_page'
  );
});


// --------------------------------
// 設定ページ
// --------------------------------
function custom_login_url_page()
{

  global $is_local;

  if (isset($_POST['login_slug'])) {

    update_option('whl_page', sanitize_title($_POST['login_slug']));
    update_option('whl_redirect_admin', sanitize_text_field($_POST['redirect_admin']));

    echo '<div class="updated"><p>保存しました。</p></div>';
  }

  $slug = get_option('whl_page', 'login');
  $redirect = get_option('whl_redirect_admin', '/');
  $login_url = home_url('/' . $slug . '/');

?>

  <div class="wrap">

    <h1>ログイン画面隠蔽</h1>

    <?php if ($is_local) : ?>

      <div style="background:#fff3cd;padding:12px;border-left:4px solid #ffb900;margin-bottom:20px; letter-spacing: 0.05em; line-height: 2;">

        <h2 style="font-size:1.25rem;font-weight:900;">
          開発時にログイン画面URLを設定しておいてください
        </h2>

        <strong>ローカル環境での挙動</strong><br>

        1. ローカル環境では <strong>WPS Hide Login プラグインは有効になりません。</strong><br>
        2. ローカル環境では常に <strong><?php echo esc_url(home_url('/wp-admin')); ?></strong> がログインURLになります。<br>
        3. ステージング環境 / 本番環境では <strong>自動的に WPS Hide Login が有効になります。</strong><br>
        4. ここで設定したログインURLはDBに保存され、 <strong>ステージング / 本番環境へインポートした際にそのまま適用されます。</strong><br>

      </div>

    <?php endif; ?>

    <form method="post">

      <table class="form-table">

        <tr>
          <th>ログインURL</th>
          <td>

            <?php echo esc_url(home_url('/')); ?>
            <input type="text" name="login_slug" value="<?php echo esc_attr($slug); ?>">

            <button
              type="button"
              class="button"
              id="copy-login-url"
              data-url="<?php echo esc_url($login_url); ?>"
              style="margin-left:10px;">
              ログインURLをコピー
            </button>

            <p class="description">ログインURLのスラッグ</p>

          </td>
        </tr>

        <tr>
          <th>wp-admin リダイレクト</th>
          <td>

            <?php echo esc_url(home_url('/')); ?>
            <input
              type="text"
              name="redirect_admin"
              value="<?php echo esc_attr($redirect); ?>"
              style="width:350px;">

            <p class="description">
              wp-admin に直接アクセスした際のリダイレクト先
            </p>

          </td>
        </tr>

      </table>

      <?php submit_button(); ?>

    </form>

  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {

      const btn = document.getElementById("copy-login-url");

      if (!btn) return;

      btn.addEventListener("click", async () => {

        const url = btn.dataset.url;

        try {

          await navigator.clipboard.writeText(url);

          btn.textContent = "コピーしました";

          setTimeout(() => {
            btn.textContent = "ログインURLをコピー";
          }, 2000);

        } catch (e) {

          alert("コピーできませんでした");

        }

      });

    });
  </script>

<?php
}
