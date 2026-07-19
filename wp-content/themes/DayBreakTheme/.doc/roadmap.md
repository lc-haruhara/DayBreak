# ロードマップ

未着手の実装方針をメモしておくファイル。実装が完了したら該当セクションを CLAUDE.md 側へ移すか、削除する。

---

## 求人検索機能の自前実装（Search & Filter Pro 相当）

### ステータス

未着手。求人カスタムポスト自体がまだ存在しない段階。
方針のみ確定済み（2026-07-19 時点）。

### 背景

求人情報のカスタムポストを追加する予定があり、そこに絞り込み検索が必要。
Search & Filter Pro のようなプラグインは導入せず、ACF Pro + `WP_Query` で自前実装する。
このテーマは `archive.php` の `$sections` 分岐、`C_Parts()` のコンポーネント解決、
wp-pagenavi によるページネーションが揃っているため、素の WordPress 機能でほぼ再現できる。

### 確定した仕様

| 項目 | 決定内容 |
|---|---|
| CPT | `recruit`（アーカイブ有効）。テーマ内にコードで登録する |
| 絞り込み項目 | 雇用形態 / 職種 / エリア / フリーワード の 4 つ |
| フリーワードの対象 | タイトル + 本文のみ（ACF フィールドの中身は対象外） |
| UI 方式 | ページリロード方式（GET パラメータ）。AJAX は v1 では実装しない |
| 選択肢の件数表示 | なし（「東京都（12）」のような表示はしない） |

### 設計方針

#### 絞り込み項目はタクソノミーで持つ

最重要。CPT を作る前に確定させる必要がある（後からの移行コストが非常に高い）。

| 項目の性質 | 持ち方 | 理由 |
|---|---|---|
| 選択式（勤務地 / 職種 / 雇用形態 など） | カスタムタクソノミー | `tax_query` が速い。選択肢一覧が `get_terms()` で取れる |
| 数値・自由値（給与レンジ / 日付など） | ACF フィールド + `meta_query` | タクソノミーでは範囲比較ができない |

全項目を ACF のテキスト / セレクトで持つと、postmeta の JOIN が重くなり、
絞り込み UI の選択肢生成も全投稿を走査する必要が出てくる。

#### 登録するタクソノミー

| 名称 | slug |
|---|---|
| 雇用形態 | `employment_type` |
| 職種 | `job_category` |
| エリア | `job_area` |

#### クエリの流れ

```
/recruit/?employment=full-time&area=tokyo&keyword=エンジニア
  ↓ pre_get_posts
    条件: is_post_type_archive('recruit') && !is_admin() && $query->is_main_query()
  ↓ tax_query（3 タクソノミーを AND 結合）+ $query->set('s', $keyword)
  ↓ メインクエリが書き換わる
  ↓ archive-recruit.php の have_posts() がそのまま絞り込み結果になる
  ↓ PaginationCommon（wp-pagenavi）も追加実装なしで動作する
```

サブクエリを別途組まず、メインクエリを書き換えるのがポイント。
これによりページネーションが自動的に絞り込み後の件数で成立する。

### 作成予定ファイル

既存の命名・配置規約に合わせること。

| ファイル | 種別 | 内容 |
|---|---|---|
| `functions/individuals/PostTypes.php` | 新規 | `register_post_type` / `register_taxonomy` |
| `functions/config/recruit-search.config.php` | 新規 | 絞り込み項目の定義（GET パラメータ名 ↔ タクソノミー slug の対応表） |
| `functions/individuals/RecruitSearch.php` | 新規 | `pre_get_posts` フック |
| `components/parts/forms/FormRecruitSearch/FormRecruitSearch.php` | 新規 | 絞り込みフォーム |
| `components/parts/forms/FormRecruitSearch/_FormRecruitSearch.scss` | 新規 | スタイル |
| `pages/archives/archive-recruit/archive-recruit.php` | 新規 | 一覧本体 |
| `pages/archives/archive-recruit/_archive-recruit.scss` | 新規 | スタイル |
| `functions.php` | 追記 | 新規 2 ファイルの `require_once` |
| `archive.php` | 追記 | `is_post_type_archive('recruit')` の分岐 |
| `components/_parts.scss` | 追記 | `@use "./parts/forms/FormRecruitSearch/FormRecruitSearch";` |
| `pages/_archives.scss` | 追記 | `@use "./archives/archive-recruit/archive-recruit";` |

設定ファイル（`recruit-search.config.php`）に
「どの GET パラメータがどのタクソノミーに対応するか」を配列で持たせておけば、
絞り込み項目の追加は config への 1 行追記だけで済む構造にできる。

### 実装時の注意点

先に潰しておくべき落とし穴。

- **キーワード欄の `name` を `s` にしない**
  `s` を送ると WordPress が検索結果ページ（`is_search`）へ分岐し、
  `archive.php` ではなく `search.php` / `index.php` を読みに行ってしまう。
  `keyword` など独自名で受け取り、`pre_get_posts` 内で `$query->set('s', ...)` に流す。

- **空パラメータを URL に残さない**
  `?employment=&area=` のようなゴミが付かないよう、
  送信時に空の入力を除去する（JS 数行、または `disabled` 属性の付与）。

- **サニタイズを徹底する**
  タクソノミーの値は `sanitize_title()`、キーワードは `sanitize_text_field()` を通す。

- **パーマリンクの再構築が必要**
  CPT を新規登録した直後は、管理画面 → 設定 → パーマリンク → 保存を 1 回実行する。
  これを忘れると求人ページが 404 になる。

- **WordPress 標準の `s` はタイトルと本文しか検索しない**
  今回は仕様上それで足りるが、将来 ACF フィールドの中身まで検索対象にしたくなった場合は
  `posts_search` / `posts_join` フィルターの自作が必要になる（JOIN と DISTINCT の考慮が要る）。

### 将来の拡張余地（v1 では実装しない）

- **AJAX 化**：同じクエリロジックの上に REST API エンドポイントを被せる。
  `history.pushState` による URL 同期と、ページネーションの JS 再実装が別途必要。
- **給与レンジ絞り込み**：ACF の数値フィールド + `meta_query` の `BETWEEN` / `>=`。
- **選択肢ごとの件数表示**：他の絞り込み選択に連動して増減させる場合、
  選択肢ごとに追加クエリが走るため実装コスト・パフォーマンス共に最も高い。
  固定件数（タクソノミー全体の投稿数）でよければ `get_terms()` の `count` で低コストに取れる。
