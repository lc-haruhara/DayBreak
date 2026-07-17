# WordPress 開発環境セットアップ README

## 概要

このプロジェクトは以下の方針でローカル開発を行います。

- WordPress 実行環境は `wp-env`
- CSS / 共通 JS は `Vite`
- PHP 変更時は自動リロード
- 外部ライブラリ本体は CDN のまま利用
- ライブラリ設定ファイルは `resource/library/config/` に集約
- ページ専用の短い inline script はそのページ内に残してよい
- DB やサイト状態は Git ではなく `WPvivid` または `All-in-One WP Migration` / SQL export・import で管理
- `dist` は、本番サーバー側で build しない場合は Git 管理してアップロードする

---

## ディレクトリ構成

```
(プロジェクトルート / WordPress コアと wp-config.php が置かれている場所)
├── .wp-env.json           ← wp-env の設定（ここにある）
├── wp-config.php
└── wp-content/
    └── themes/
        └── Absolute5Ver3Theme/   ← テーマルート（ここで pnpm コマンドを実行）
            ├── mise.toml
            ├── package.json
            └── vite.config.js
```

wp-env のコマンドは **テーマルート**（`package.json` のある場所）から `pnpm` 経由で実行します。

---

## 前提条件

以下がインストール済みであること。

1. Docker Desktop
   https://www.docker.com/ja-jp/products/docker-desktop/
2. mise

---

## mise のインストール

### mac の場合

#### 1. mise を入れる

```bash
brew install mise
```

macOS の推奨インストール方法は Homebrew です。

#### 2. zsh に activate を入れる

```bash
echo 'eval "$(mise activate zsh)"' >> ~/.zshrc
source ~/.zshrc
```

#### 3. プロジェクトで tool を入れる

テーマルートで:

```bash
mise install
node -v
pnpm -v
```

`mise.toml` に記載の Node 22.12.0 と pnpm 10.32.1 がインストールされます。

### Windows (WSL2) の場合

#### 1. mise を入れる

Scoop があるなら

```bash
scoop install mise
```

winget なら

```bash
winget install jdx.mise
```

#### 2. PowerShell に activate を入れる

PowerShell の `$PROFILE` にこれを追加します。

```powershell
mise activate pwsh | Out-String | Invoke-Expression
```

#### 3. プロジェクトで tool を入れる

```bash
mise install
node -v
pnpm -v
```

---

## 初回セットアップ

### 1. テーマルートへ移動

```bash
cd /path/to/wp-content/themes/Absolute5Ver3Theme
```

### 2. 依存関係をインストール

```bash
pnpm install
```

`@wordpress/env` はここで一緒にインストールされます。別途グローバルインストールは不要です。

### 3. `wp-env` を起動

```bash
pnpm run env:start
```

### 4. 管理画面へアクセス

```
http://localhost:8888/wp-admin/
```

初期ログイン情報:

- ユーザー名: `admin`
- パスワード: `password`

ログイン後、対象テーマを有効化してください。

### 5. DB をインポート

WPvivid または AllInOneWpMigration でインポートしてください。

### 6. Vite を起動

```bash
pnpm run dev
```

---

### 補足: dev / build の切り替えについて

`Vite.php` は `WP_ENVIRONMENT_TYPE === 'local'` を条件に Vite dev サーバーへのリクエストを注入します。
`.wp-env.json` で `"WP_ENVIRONMENT_TYPE": "local"` が設定されているため、`wp-env` 環境では常に `localhost:5173` からアセットを読もうとします。

- **dev 時**: `pnpm run dev` で Vite dev サーバーを起動しておく
- **build 確認時**: `pnpm run dev` を停止して `pnpm run build` を実行し、`dist/` からアセットが読まれていることを確認する

---

## `EnqueueResources.php` の方針

`functions/individuals/EnqueueResources.php` には、以下だけを残します。

- CDN 本体
- まだ npm / Vite 側へ寄せないライブラリ本体

Vite 管理に移したものは enqueue しません。

### 例: CDN のまま残す対象

- Swiper 本体
- GSAP / ScrollTrigger 本体
- smooth-scroll 本体
- Google Maps API

---

## 普段の開発

### 起動

```bash
pnpm run env:start
pnpm run dev
```

### 開発時の確認項目

- SCSS 変更 → 即反映
- PHP 変更 → 自動リロード
- `app.js` 経由の共通 JS が動作すること

### 検証ツールでの見分け方

#### dev のとき

- `@vite/client` が見える
- `resource/js/app.js` が見える
- `5173` のリクエストがある

#### build のとき

- `dist/assets/*.css`
- `dist/assets/*.js`

が見える

- `5173` のリクエストがない

---

## build

```bash
pnpm run build
```

### build 確認

1. `pnpm run dev` を停止
2. `http://localhost:8888/` を開く
3. `dist/assets/*.css` と `dist/assets/*.js` が読み込まれていれば OK

---

## デプロイ

更新したファイルをサーバーにアップロードは通常通り。
build すると Vite が `manifest.json` / css / js を出力するので、テーマディレクトリ内にある `dist` を丸ごとサーバーに必ずアップロードすること。

---

## 停止 / 削除

### 停止

```bash
pnpm run env:stop
```

### 完全削除

```bash
pnpm run env:destroy
```

---

## Git 管理方針

### Git 管理するもの

- テーマ
- 必要なプラグインコード
- 必要なら WordPress コア
- `.wp-env.json`
- `package.json`
- `vite.config.js`
- `mise.toml`
- `functions/individuals/Vite.php`
- `resource/js/app.js`
- `resource/library/config/`
- `dist`（本番サーバー側で build しない場合）

### Git 管理しないもの

- `node_modules`
- `wp-env` の内部実体
- Docker 実体
- DB 実体
- 必要に応じて `uploads`

---

## JS 方針

### `app.js` に入れるもの

- 共通で使う JS
- `common.js`
- `scroll-target.js`
- `resource/library/config/*.js`

### inline のままでよいもの

- そのページでしか使わない短い script
- PHP テンプレートと密結合な処理
- 他ページで再利用しない処理

---

## ライブラリ方針

### CDN のまま残すもの

- Swiper 本体
- GSAP / ScrollTrigger 本体
- smooth-scroll 本体
- Google Maps API

### Vite 管理するもの

- 共通 JS
- テーマ内 JS
- 各ライブラリの設定ファイル
- SCSS

---

## DB / サイト状態の扱い

Git では管理しません。必要に応じて以下で移行します。

- WPvivid（推奨）
- All-in-One WP Migration
- SQL export / import

### 考え方

- ファイル資産と DB 資産は分けて管理する
- WordPress コアやテーマやプラグインを Git 管理しても、DB は別で必要
- ACF 入力値や投稿データやプラグイン設定の多くは DB 側の資産

---

## Google Maps API について

Google Maps API キーはフロントに見えていても問題ありません。
ただし、制限を必ず設定してください。

### 推奨

- HTTP referrer 制限
- Maps JavaScript API のみに API 制限

---

## よく使うコマンド

### 初回

```bash
pnpm install
pnpm run env:start
pnpm run dev
```

### 通常開発

```bash
pnpm run env:start
pnpm run dev
```

### build

```bash
pnpm run build
```

### 停止

```bash
pnpm run env:stop
```

### 完全削除

```bash
pnpm run env:destroy
```

### SCSS Lint / Format

```bash
pnpm run lint:scss
pnpm run lint:scss:fix
pnpm run format:scss
```
