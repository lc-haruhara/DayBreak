<?php
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// XML-RPC を無効
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('xmlrpc_enabled', '__return_false');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// アイキャッチ画像の有効化
// 機能: WordPressテーマでアイキャッチ画像（投稿サムネイル）を有効化します。
// 目的: 投稿やページに画像を簡単に設定できるようにする。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_theme_support('post-thumbnails');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// SVG画像のアップロード許可とサニタイズ
// 機能: SVGファイルのアップロードを許可し、アップロード時に内容を検証（サニタイズ）します。
// 目的: SVGによるセキュリティリスクを軽減しつつ、SVGを利用可能にします。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('upload_mimes', function ($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
});

add_filter('wp_handle_upload_prefilter', function ($file) {
  if ($file['type'] === 'image/svg+xml') {
    $svg_content = file_get_contents($file['tmp_name']);
    try {
      new SimpleXMLElement($svg_content); // SVGの検証
    } catch (Exception $e) {
      $file['error'] = 'Invalid SVG file.';
    }
  }
  return $file;
});

add_filter('wp_prepare_attachment_for_js', function ($response, $attachment, $meta) {
  if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
    $svg_path = get_attached_file($attachment->ID);
    $svg_content = file_get_contents($svg_path);
    $response['image'] = 'data:image/svg+xml;base64,' . base64_encode($svg_content);
    $response['thumb'] = $response['image'];
  }
  return $response;
}, 10, 3);

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 管理バー非表示
// 機能: ログインユーザーに表示される管理バーを非表示にします。
// 目的: フロントエンドでの管理バーが不要な場合、ユーザーインターフェースを簡潔に保つ。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('show_admin_bar', '__return_false');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Google Maps APIキー設定
// 機能: Advanced Custom Fields（ACF）プラグイン用のGoogle Maps APIキーを設定。
// 目的: カスタムフィールドでGoogle Mapsを利用可能にする。
// キーは管理画面「サイト設定 > Google Maps API キー」から入力する。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('acf/init', function () {
  $key = get_field('google_maps_api_key', 'option');
  if ($key) {
    acf_update_setting('google_api_key', $key);
  }
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Google Material Symbols をインストール
// 機能: 管理画面にGoogle Material Symbols を読み込ませる
// 目的: resource\scss\_dashboard\admin-ui\_icons.scss でアイコンを置換するため
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function my_admin_enqueue_styles()
{
  wp_enqueue_style(
    'google-material-symbols',
    'https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
    array(),
    null
  );
}
add_action('admin_enqueue_scripts', 'my_admin_enqueue_styles');


//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ビジュアルエディタのCSS追加
// 機能: ビジュアルエディタ用のスタイルシートを適用。
// 機能: エディタでの見た目がフロントエンドに近づき、編集が簡単になる。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_editor_style('resource/css/_dashboard/editor-style.css');

add_filter('tiny_mce_before_init', function ($mce_init) {
  $mce_init['cache_suffix'] = 'v=' . time();
  return $mce_init;
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 管理画面のCSS追加
// 機能: 管理画面にカスタムCSSを適用。
// 目的: 管理画面の外観をカスタマイズする。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('admin_footer', function () {
  echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/resource/css/_dashboard/main.css">';
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 編集者の不要メニューを非表示
// 機能: 管理者以外のユーザーに不要なメニューを非表示。
// 目的: 管理画面をシンプルに保ち、不要な機能を隠す。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('admin_menu', function () {
  if (!current_user_can('administrator')) {
    remove_menu_page('index.php'); // ダッシュボード
    remove_menu_page('edit.php'); // 投稿
    remove_menu_page('edit.php?post_type=page');    // 固定ページ
    remove_menu_page('edit-comments.php'); // コメント
    remove_menu_page('themes.php'); // 外観
    remove_menu_page('plugins.php'); // プラグイン
    remove_menu_page('users.php'); // ユーザー
    remove_menu_page('tools.php'); // ツール
    remove_menu_page('options-general.php'); // 設定
    remove_menu_page('profile.php'); // プロフィール
    remove_menu_page('wpcf7'); // お問い合わせ
    remove_menu_page('wpseo_workouts'); // YoastSeo
    remove_menu_page('edit.php?post_type=hoge'); // カスタムポストタイプを隠したい場合
  }
  if (!current_user_can('editor')) {
    remove_menu_page('edit.php?post_type=hoge'); // 管理者権限でも消す場合
  }
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 管理画面の body タグに .acf-internal-post-type クラスを追加
// 目的: ACFのcssを全画面に適用するため
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('admin_body_class', function ($classes) {
  // クラスを追加
  $classes .= ' acf-internal-post-type';
  return $classes;
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// サンドウィッチエディタを使用している投稿画面には body に classを追加する
// 目的: サンドウィッチエディタ編集画面のレイアウトを調整しやすくするため
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function add_acf_field_group_key_to_admin_body_class($classes)
{
  global $post;
  // 投稿編集画面でのみ処理
  if (!is_admin() || !isset($post)) {
    return $classes;
  }
  // 投稿IDを取得
  $post_id = $post->ID;
  // ACFのフィールドグループを取得
  $field_groups = acf_get_field_groups(array('post_id' => $post_id));
  // フィールドグループキーをクラスに追加
  if (!empty($field_groups)) {
    foreach ($field_groups as $group) {
      if (isset($group['key'])) {
        $classes .= ' acf-group-' . sanitize_html_class($group['key']);
      }
    }
  }
  return $classes;
}
add_filter('admin_body_class', 'add_acf_field_group_key_to_admin_body_class');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// tinyMCE スタイルボタン追加
// 機能: ビジュアルエディタにスタイルを調整できるボタンを追加
// 目的: 大きい文字やハイライトなどを簡単につけられるようにする
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// スタイルセレクトボックスをツールバーに追加
function tinymce_add_buttons($buttons)
{
  // ツールバーに「スタイル」のセレクトボックスを追加
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons', 'tinymce_add_buttons');

// スタイルセレクトボックスの中身を設定
function my_tiny_mce_before_init($init_array)
{
  // スタイルセレクトボックスに表示するスタイルを定義
  $style_formats = array(
    array(
      'title' => 'フォントサイズ[大]',
      'inline' => 'span',
      'classes' => 'p-single-size-l',
    ),
    array(
      'title' => 'フォントサイズ[小]',
      'inline' => 'span',
      'classes' => 'p-single-size-s',
    ),
    array(
      'title' => '強調', // 表示名
      'inline' => 'span',      // 適用するHTMLタグ
      'classes' => 'p-single-accent', // クラス名
    ),
    array(
      'title' => '太字',
      'inline' => 'span',
      'classes' => 'p-single-bold',
    ),
  );

  // JSON形式に変換してTinyMCEに渡す
  $init_array['style_formats'] = json_encode($style_formats);

  // ブロックフォーマット（段落や見出しの設定）を変更（必要に応じて）
  $init_array['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';

  return $init_array;
}
add_filter('tiny_mce_before_init', 'my_tiny_mce_before_init');

// カスタムスタイルのCSSを管理画面用に追加
function enqueue_editor_styles()
{
  // 管理画面用のCSSを読み込む
  add_editor_style('editor-style.css');
}
add_action('admin_init', 'enqueue_editor_styles');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 投稿一覧画面にサムネイルを追加
// 機能: カスタムポストタイプを含む全ての投稿一覧画面に投稿のアイキャッチを表示
// 目的: 視認しやすくするため
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function add_thumbnail_column($columns)
{
  $new_columns = [];
  foreach ($columns as $key => $value) {
    if ($key === 'title') {
      $new_columns['thumbnail'] = __('Thumbnail');
    }
    $new_columns[$key] = $value;
  }
  return $new_columns;
}

function display_thumbnail_column($column_name, $post_id)
{
  if ($column_name === 'thumbnail') {
    if (has_post_thumbnail($post_id)) {
      // サムネイル画像を編集画面リンクで囲む
      $edit_link = get_edit_post_link($post_id);
      echo '<a href="' . esc_url($edit_link) . '">' . get_the_post_thumbnail($post_id, [100, 100]) . '</a>';
    } else {
      echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '">' . __('No Thumbnail') . '</a>';
    }
  }
}

function add_thumbnail_column_to_post_types()
{
  $post_types = get_post_types(['public' => true], 'names');
  foreach ($post_types as $post_type) {
    add_filter("manage_{$post_type}_posts_columns", 'add_thumbnail_column');
    add_action("manage_{$post_type}_posts_custom_column", 'display_thumbnail_column', 10, 2);
  }
}
add_action('admin_init', 'add_thumbnail_column_to_post_types');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 編集者権限限定css
// 機能: 編集者権でログインした場合に読み込まれるcssを追加
// 目的: クライアント用
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function custom_admin_css_for_non_admin_users()
{
  // 現在のユーザー情報を取得
  $current_user = wp_get_current_user();

  // 管理者権限がない場合にCSSを追加
  if (!in_array('administrator', $current_user->roles)) {
    add_action('admin_footer', function () {
      echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/resource/css/_dashboard/non-admin.css">';
    });
  }
}
add_action('admin_enqueue_scripts', 'custom_admin_css_for_non_admin_users', PHP_INT_MAX); // 優先度を最大値に設定

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 投稿画面のカテゴリ選択をチェックボックスからラジオボタンにする
// 目的: チェックボックスは不要
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function convert_category_to_radio()
{
?>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      // 現在の投稿タイプを取得
      const postTypeField = document.getElementById('post_type');
      if (!postTypeField) {
        return; // 投稿タイプが見つからない場合は終了
      }

      const currentPostType = postTypeField.value;

      // カテゴリー選択エリアを取得
      const taxonomyDivs = document.querySelectorAll('[id^="taxonomy-"]');
      if (taxonomyDivs.length === 0) {
        return; // タクソノミーエリアがない場合は終了
      }

      taxonomyDivs.forEach(function(taxonomyDiv) {
        const checkboxes = taxonomyDiv.querySelectorAll('input[type="checkbox"]');
        if (checkboxes.length > 0) {
          // チェックボックスをラジオボタンに変換
          checkboxes.forEach(function(checkbox) {
            checkbox.type = 'radio';
          });
        }
      });
    });
  </script>
<?php
}
add_action('admin_head', 'convert_category_to_radio');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 記事のディスクリプションに、カスタムフィールド "Description" を必ず含める
// 機能: カスタムポストタイプを増やした際の設定漏れ防止
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_filter('wpseo_metadesc', function ($metadesc) {

  // 個別ページ以外では何もしない
  if (!is_singular()) {
    return $metadesc;
  }

  global $post;
  if (!$post) {
    return $metadesc;
  }

  $post_type = get_post_type($post);

  // 投稿・固定ページは対象外にしたいならここで除外
  // （含めたければこの if ブロックを削除）
  if (in_array($post_type, array('post', 'page'), true)) {
    return $metadesc;
  }

  // ACFフィールド "Description" を取得
  $cf_description = get_field('Description', $post->ID);
  if (empty($cf_description)) {
    return $metadesc;
  }

  // 念のため Description も整形（万が一手入力で変な改行等が入ってもきれいにする）
  $cf_description = sanitize_description_text($cf_description, 160);

  // すでに含まれているなら何もしない
  if (!empty($metadesc) && strpos($metadesc, $cf_description) !== false) {
    return $metadesc;
  }

  // Yoast側のメタディスクリプションが空なら、Description だけを使う
  if (empty($metadesc)) {
    return $cf_description;
  }

  // それ以外は、先頭に Description を必ず付ける
  return $cf_description . ' ' . $metadesc;
});

add_action('admin_footer', function () {

  if (!isset($_GET['page']) || $_GET['page'] !== 'cfturnstile') {
    return;
  }
?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      // hr を特定
      const hr = document.querySelector('hr[style="margin: 20px 0 0 0;"]');
      if (!hr) return;

      // 挿入要素
      const wrapper = document.createElement('div');
      wrapper.style.margin = "15px 0";
      wrapper.innerHTML = `
        <a href="https://docs.craft.do/editor/d/46223f79-ad83-b73c-9c57-c8780bec99f2/01d1bef7-cdb3-4501-b63c-16b54b8abfe3?s=NXHFrEx3oZ9i8dTC8oya4zG16LE9cZnff7aC9TSswJzT" target="_blank" rel="noopener noreferrer" style="font-size: 1.5rem; font-weight: 700;">
          設定マニュアルはこちら (Craftドキュメント)
        </a>
      `;

      // hr の直後に挿入
      hr.parentNode.insertBefore(wrapper, hr.nextSibling);

    });
  </script>
<?php
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// YOAST SEO 同期
// 機能: 一般設定で設定した項目と同一の内容に書き換える。wp-admin トップにアクセスした際に発火。
// 目的: ヒューマンエラー防止。
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('load-index.php', 'sync_wp_title_to_yoast');

function sync_wp_title_to_yoast()
{

  if (!current_user_can('manage_options')) return;

  $site_name = get_option('blogname');
  $yoast = get_option('wpseo_titles');

  if (!$yoast) return;

  $updated = false;

  if (($yoast['company_name'] ?? '') !== $site_name) {
    $yoast['company_name'] = $site_name;
    $updated = true;
  }

  if (($yoast['website_name'] ?? '') !== $site_name) {
    $yoast['website_name'] = $site_name;
    $updated = true;
  }

  if (($yoast['alternate_website_name'] ?? '') !== $site_name) {
    $yoast['alternate_website_name'] = $site_name;
    $updated = true;
  }

  if ($updated) {
    update_option('wpseo_titles', $yoast);
  }
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// 編集者に WP Mail SMTP 権限付与 / 管理者に manage_theme_tools 付与
// manage_theme_tools : 管理者専用ページ（Rewrite Flush / CF7 Sync）の制御に使用
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// true : 編集者に WP Mail SMTP を表示する
// false: 管理者のみ表示する
const EDITOR_CAN_ACCESS_WP_MAIL_SMTP = true;

add_filter('user_has_cap', function ($allcaps, $caps, $args, $user) {

  if (!is_admin()) {
    return $allcaps;
  }

  $roles = (array) $user->roles;

  if (in_array('administrator', $roles, true)) {
    $allcaps['manage_theme_tools'] = true;
    return $allcaps;
  }

  if (in_array('editor', $roles, true) && EDITOR_CAN_ACCESS_WP_MAIL_SMTP) {
    $allcaps['manage_options'] = true;
  }

  return $allcaps;
}, 10, 4);


// ::::::::::::::::::::::::::::::::::::::::::::::::::
// Rewrite Flush メニュー追加
// 機能 : 押下するとパーマリンクをフラッシュするボタン
// 目的 : DBのインポート後、下層ページが404になるかもしれない対策
// ::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('admin_menu', function () {

  add_menu_page(
    'Rewrite Flush',
    'Rewrite Flush',
    'manage_theme_tools',
    // 'edit_pages', //編集者権限でも見せる場合
    'rewrite-flush',
    'rewrite_flush_page',
    'dashicons-update',
    99
  );
}, 999);

// 管理画面 に追加
function rewrite_flush_page()
{
  if (!current_user_can('edit_pages')) {
    wp_die('権限がありません');
  }

  if (isset($_POST['rewrite_flush'])) {

    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rewrite_flush_nonce')) {
      wp_die('Security check failed');
    }

    flush_rewrite_rules();

    echo '<div class="notice notice-success"><p>Rewrite rules を更新しました。</p></div>';
  }
?>
  <div class="wrap">
    <h1>Rewrite Flush</h1>

    <p>パーマリンク設定を再生成します。</p>
    <p>データべーインポート後、下層ページが404になった場合はこちらを押下してください。</p>

    <form method="post">
      <?php wp_nonce_field('rewrite_flush_nonce'); ?>

      <p>
        <input type="submit" name="rewrite_flush" class="button button-primary" value="Rewrite Flush 実行">
      </p>
    </form>

  </div>
<?php
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// マニュアルメニュー追加
// 機能: Craft ドキュメントを iframe で埋め込んで表示
// 目的: 管理画面からマニュアルをすぐ参照できるようにする
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
add_action('admin_menu', function () {
  add_menu_page(
    'マニュアル',
    'マニュアル',
    'edit_posts',
    'manual',
    'absolute5_manual_page',
    'dashicons-book',
    2
  );
}, 999);

function absolute5_manual_page()
{
  if (!current_user_can('edit_posts')) {
    wp_die('権限がありません');
  }
?>
  <style>
    #absolute5-manual-wrap {
      margin: 20px;
    }

    #absolute5-manual-wrap iframe {
      width: 100%;
      height: 80vh;
      border: none;
      display: block;
    }
  </style>
  <div id="absolute5-manual-wrap">
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
      <p style="margin: 0;">
        閲覧パスワード: <strong id="absolute5-manual-password">lcdoc2025</strong>
        <button type="button" class="button button-secondary" style="margin-left: 8px;" onclick="
          navigator.clipboard.writeText('lcdoc2025').then(function() {
            var btn = this;
            btn.textContent = 'コピーしました';
            setTimeout(function() { btn.textContent = 'コピー'; }, 2000);
          }.bind(this));
        ">コピー</button>
      </p>
      <a href="https://www.craft.do/s/9garu2A3o37WM6" target="_blank" rel="noopener noreferrer" class="button button-secondary">別窓で開く</a>
    </div>
    <iframe src="https://www.craft.do/s/9garu2A3o37WM6" title="マニュアル" allowfullscreen></iframe>
  </div>
<?php
}

?>