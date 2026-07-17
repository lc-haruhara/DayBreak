# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

WordPress テーマスターターキット。Vite によるモダンなアセットビルドと、PHP コンポーネントシステムを組み合わせた構成。

## 開発コマンド

```bash
# ローカル WordPress 環境の起動（wp-env）
pnpm env:start

# Vite 開発サーバー（HMR 付き）
pnpm dev

# プロダクションビルド → dist/ を生成
pnpm build

# SCSS Lint
pnpm lint:scss
pnpm lint:scss:fix

# SCSS フォーマット
pnpm format:scss
```

ツールバージョンは `mise.toml` で管理（Node 22.12.0 / pnpm 10.32.1）。

## アーキテクチャ

### Vite 統合

`functions/individuals/Vite.php` が環境を判定して読み込み方を切り替える。

- **ローカル**（`WP_ENVIRONMENT_TYPE === 'local'`）: Vite dev サーバー（port 5173）からモジュールを直接注入
- **本番**: `dist/.vite/manifest.json` を読んでハッシュ付きアセットを `wp_enqueue_*` で登録

### PHP コンポーネントシステム

`components/utilities/Includes.php` がコンポーネントを動的解決する。各コンポーネントは **ファイル名 = コンポーネント名** で自動登録される（`require_once` 不要）。

```php
C_Elements('ButtonLink', $args);   // components/elements/ 配下
C_Parts('NavigationGlobal', $args); // components/parts/ 配下
C_Layouts('SectionCta', $args);    // components/layouts/ 配下
```

- 同名コンポーネントが複数存在すると `E_USER_WARNING` が出る
- コンポーネントの PHP ファイルは `$args` 配列でデータを受け取る規約

### SCSS 構造

エントリポイントは `resource/scss/main.scss`。各コンポーネント・ページディレクトリに同名の `.scss` が置かれ、まとめて `@use` でインポートされる。

```
resource/scss/
  foundation/   リセット・基本設定
  layout/       共通レイアウト
  object/       汎用コンポーネント（旧来系）
components/     PHP コンポーネントに対応する SCSS
pages/          ページ別スタイル
```

Vite の `css.preprocessorOptions.scss.loadPaths` に `resource/scss`、`pages`、`components` が設定されているため、これらのパスから `@use` で直接参照できる。

### JavaScript

エントリポイントは `resource/js/app.js`。機能別に分割されている。

| ファイル | 用途 |
|---|---|
| `common.js` | 全ページ共通処理 |
| `scroll-target.js` | `[data-js-scroll-target]` の IntersectionObserver 代替実装 |
| `hybrid-scroll.js` | 縦スクロールを横移動に変換（現在コメントアウト中） |
| `loading.js` / `loading-top-only.js` | ローディング演出 |
| `library/config/` | Swiper・GSAP・SmoothScroll 等の初期設定ファイル（コメントアウトで取捨選択） |

### ContactForm7 Sync

`functions/individuals/ContactForm7Sync.php` は独自の管理画面ツール（`/wp-admin/tools.php?page=km-cf7-sync`）。`components/elements/inputs/cf7/.Cf7Schema/` 配下のスキーマファイルで CF7 フォームを定義・同期できる。

入力コンポーネントは `components/elements/inputs/InputField*/` に配置されており、CF7 フォーム内でも、UI コンポーネントとしての単体利用でも使える。`$args['field']` の有無で自動的に CF7 モード / UI モードを切り替える。

### PostSubmitAutoAction

`functions/individuals/PostSubmitAutoAction.php` は投稿保存時の自動処理。

- **アイキャッチ自動設定**: ACF FlexibleContents の画像からランダム選択（未設定時のみ）
- **Description 自動設定**: FlexibleContents の最初のエディタブロックから 120 字を抽出（未設定時のみ）

## 既知の問題・改善すべき点

### 未完成・要対応

- `resource/js/hybrid-scroll.js` は `app.js` でコメントアウト中。使用する場合は `app.js` のコメントを解除する。
- `resource/js/loading.js` と `loading-top-only.js` の 2 種類が存在するが、現在は `loading-top-only.js` のみ有効。用途に応じて切り替える。
- `EnqueueResources.php` に Swiper / GSAP / Google Maps 等の CDN 読み込みがコメントアウトで用意されている。使用するライブラリのコメントを解除して利用する。

### CF7 スキーマ

`components/elements/inputs/cf7/.Cf7Schema/` にスキーマを追加することで CF7 フォームをコード管理できるが、スキーマファイルが `Cf7Schema.php` 1 件のみの状態。実際のフォームに合わせてスキーマを追加していく。スキーマの `component` には `InputFieldText` など新しいコンポーネント名を指定する。
