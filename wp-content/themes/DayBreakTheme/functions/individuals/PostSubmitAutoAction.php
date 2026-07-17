<?php

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// サンドウィッチエディタの自動アイキャッチ設定
// 機能: アイキャッチ未設定時のみ FlexibleContents 内の画像からランダムで設定
//      - アイキャッチ設定済みの場合は一切触らない（手動設定を尊重）
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function auto_set_featured_image_from_flexible_content($post_id)
{
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // すでにアイキャッチが設定済みなら何もしない
  if (has_post_thumbnail($post_id)) return;

  $flex_field = 'FlexibleContents';
  $image_ids  = [];

  if (have_rows($flex_field, $post_id)) {
    while (have_rows($flex_field, $post_id)) {
      the_row();
      $layout = get_row_layout();

      if ($layout === 'Col1Layout') {
        $col1 = get_sub_field('col1');
        if (!empty($col1['ImageGroup1']['image1']['ID'])) $image_ids[] = (int) $col1['ImageGroup1']['image1']['ID'];
      } elseif ($layout === 'Col2Layout') {
        $images = get_sub_field('col2')['ImageGroup2'] ?? [];
        if (!empty($images['image1']['ID'])) $image_ids[] = (int) $images['image1']['ID'];
        if (!empty($images['image2']['ID'])) $image_ids[] = (int) $images['image2']['ID'];
      } elseif ($layout === 'Col3Layout') {
        $images = get_sub_field('col3')['ImageGroup3'] ?? [];
        if (!empty($images['image1']['ID'])) $image_ids[] = (int) $images['image1']['ID'];
        if (!empty($images['image2']['ID'])) $image_ids[] = (int) $images['image2']['ID'];
        if (!empty($images['image3']['ID'])) $image_ids[] = (int) $images['image3']['ID'];
      }
    }
  }

  if (!empty($image_ids)) {
    set_post_thumbnail($post_id, $image_ids[array_rand($image_ids)]);
  }
}
add_action('save_post', 'auto_set_featured_image_from_flexible_content');

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// Description 用テキスト整形関数
// ・タグ除去
// ・エンティティデコード
// ・空白正規化
// ・最初の1文抽出
// ・文字数制限
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function sanitize_description_text($text, $length = 120)
{
  // HTMLタグ削除
  $text = wp_strip_all_tags($text);

  // HTMLエンティティをデコード
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

  // 全角スペース・nbsp を半角に
  $text = str_replace(["\xC2\xA0", '　'], ' ', $text);

  // 改行・連続空白を1つに
  $text = preg_replace('/\s+/u', ' ', $text);

  // トリム
  $text = trim($text);

  // 最初の1文を抽出（。！？）
  if (preg_match('/^(.+?[。！？])/u', $text, $matches)) {
    $text = $matches[1];
  }

  // 文字数制限
  if ($length > 0 && mb_strlen($text) > $length) {
    $text = mb_strimwidth($text, 0, $length, '…', 'UTF-8');
  }

  return $text;
}

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// サンドウィッチエディタの自動ディスクリプション設定
// ・Description が空のときのみ
// ・Flexible Content の最初の文章を使用
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function auto_set_description_from_flexible_content($post_id)
{
  // 自動保存・管理画面以外を除外
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!is_admin()) return;

  // すでに Description がある場合は何もしない
  $description = get_field('Description', $post_id);
  if (!empty($description)) return;

  $flex_field = 'FlexibleContents';
  $found_text = '';

  if (have_rows($flex_field, $post_id)) {
    while (have_rows($flex_field, $post_id)) {
      the_row();
      $layout = get_row_layout();
      $editor = '';

      if ($layout === 'Col1Layout') {
        $col1 = get_sub_field('col1');
        $editor = $col1['editor1'] ?? '';
      } elseif ($layout === 'Col2Layout') {
        $col2 = get_sub_field('col2');
        $editor = $col2['editor1'] ?? $col2['editor2'] ?? '';
      } elseif ($layout === 'Col3Layout') {
        $col3 = get_sub_field('col3');
        $editor = $col3['editor1'] ?? $col3['editor2'] ?? $col3['editor3'] ?? '';
      }

      if (!empty($editor)) {
        $found_text = sanitize_description_text($editor, 120);
        break;
      }
    }
  }

  // Description を更新
  if (!empty($found_text)) {
    update_field('Description', $found_text, $post_id);
  }
}

// ACF 保存完了後に実行（最重要）
add_action('acf/save_post', 'auto_set_description_from_flexible_content', 20);
