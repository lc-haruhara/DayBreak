# Absolute5Ver3Theme

WordPress テーマスターターキット。Vite によるモダンなアセットビルドと、PHP コンポーネントシステムを組み合わせた構成。

## 動作環境

| ツール | バージョン |
|---|---|
| Node.js | 22.12.0 |
| pnpm | 10.32.1 |

バージョン管理には [mise](https://mise.jdx.dev/) を使用。`mise install` で揃えられる。

## セットアップ

```bash
pnpm install
```

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

## ディレクトリ構成

```
.
├── components/          PHP コンポーネント（elements / parts / layouts）
│   └── utilities/       Includes.php など共通ユーティリティ
├── functions/
│   └── individuals/     Vite, CF7Sync, PostSubmitAutoAction など機能別クラス
├── pages/               ページ別テンプレート・SCSS
├── resource/
│   ├── js/              app.js をエントリポイントとした JS モジュール群
│   └── scss/            main.scss をエントリポイントとした SCSS
├── dist/                ビルド成果物（.gitignore 推奨）
├── vite.config.js
└── style.css            WordPress テーマ識別用
```

## アーキテクチャ

### Vite 統合

`functions/individuals/Vite.php` が環境を判定してアセット読み込みを切り替える。

- **ローカル**（`WP_ENVIRONMENT_TYPE === 'local'`）: Vite dev サーバー（port 5173）からモジュールを直接注入
- **本番**: `dist/.vite/manifest.json` を読んでハッシュ付きアセットを `wp_enqueue_*` で登録

### PHP コンポーネントシステム

`components/utilities/Includes.php` がコンポーネントをファイル名から自動解決する（`require_once` 不要）。

```php
C_Elements('ButtonLink', $args);    // components/elements/ 配下
C_Parts('NavigationGlobal', $args); // components/parts/ 配下
C_Layouts('SectionCta', $args);     // components/layouts/ 配下
```

- コンポーネントの PHP ファイルは `$args` 配列でデータを受け取る規約
- 同名コンポーネントが複数存在すると `E_USER_WARNING` が出る

### SCSS 構造

```
resource/scss/
  foundation/   リセット・基本設定
  layout/       共通レイアウト
  object/       汎用コンポーネント（旧来系）
components/     PHP コンポーネントに対応する SCSS
pages/          ページ別スタイル
```

`resource/scss`、`pages`、`components` が `loadPaths` に設定されており、これらのパスから `@use` で直接参照できる。

### JavaScript

エントリポイントは `resource/js/app.js`。

| ファイル | 用途 |
|---|---|
| `common.js` | 全ページ共通処理 |
| `scroll-target.js` | `[data-js-scroll-target]` の IntersectionObserver 代替実装 |
| `hybrid-scroll.js` | 縦スクロールを横移動に変換（現在コメントアウト中） |
| `loading.js` / `loading-top-only.js` | ローディング演出 |
| `library/config/` | Swiper・GSAP・SmoothScroll 等の初期設定（コメントアウトで取捨選択） |

### ContactForm7 Sync

管理画面ツール（`/wp-admin/tools.php?page=km-cf7-sync`）。`components/elements/inputs/cf7/.Cf7Schema/` 配下のスキーマファイルで CF7 フォームをコード管理・同期できる。

### PostSubmitAutoAction

投稿保存時の自動処理（`functions/individuals/PostSubmitAutoAction.php`）。

- **アイキャッチ自動設定**: ACF FlexibleContents の画像からランダム選択（未設定時のみ）
- **Description 自動設定**: FlexibleContents の最初のエディタブロックから 120 字を抽出（未設定時のみ）

## オプション機能の有効化

以下はコメントアウトで用意されており、必要に応じて解除する。

- **hybrid-scroll**: `resource/js/app.js` のコメントを解除
- **Swiper / GSAP / Google Maps 等の CDN**: `functions/individuals/EnqueueResources.php` のコメントを解除
- **ローディング演出の切り替え**: `loading.js`（全ページ）と `loading-top-only.js`（トップのみ）を用途に応じて選択
