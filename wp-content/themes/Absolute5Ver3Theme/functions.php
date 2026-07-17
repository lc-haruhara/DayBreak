<?php
// 管理画面関連
require_once get_template_directory() . '/functions/Admin.php';

// フロントエンド関連
require_once get_template_directory() . '/functions/Frontend.php';

// リソース関連
require_once get_template_directory() . '/functions/individuals/EnqueueResources.php';

// フォント読み込み（設定は functions/config/fonts.config.php）
require_once get_template_directory() . '/functions/individuals/Fonts.php';

// Vite
require_once get_template_directory() . '/functions/individuals/Vite.php';

// ContactForm7
require_once get_template_directory() . '/functions/individuals/ContactForm7.php';
require_once get_template_directory() . '/functions/individuals/ContactForm7Sync.php';

// ネイティブ入力タグビルダー
require_once get_template_directory() . '/functions/individuals/InputField.php';

// ACF オプションページ（サイト設定）
require_once get_template_directory() . '/functions/individuals/AcfOptions.php';

// 投稿保存時に自動処理するアクション
require_once get_template_directory() . '/functions/individuals/PostSubmitAutoAction.php';

// WPvivid バックアップ除外設定（.vscode/sftpignore のパターンを反映）
require_once get_template_directory() . '/functions/individuals/WPvividExclude.php';
