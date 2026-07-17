<?php

/**
 * .vscode/sftpignore に記載のパターンを WPvivid バックアップの除外リストに反映する。
 *
 * 対応フック:
 *   wpvivid_default_exclude_folders — 新バックアップクラス（主流）
 *   wpvivid_get_backup_exclude_regex — 旧バックアップクラス（コンテンツ型のみ）
 */

add_filter('wpvivid_default_exclude_folders', function (array $exclude_files): array {
    $sftpignore_path = get_stylesheet_directory() . '/.vscode/sftpignore';
    if (!file_exists($sftpignore_path)) {
        return $exclude_files;
    }

    $theme_dir = get_stylesheet_directory();
    $lines     = file($sftpignore_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $additions = [];
    $negations = [];

    foreach ($lines as $line) {
        $pattern = trim($line);
        if ($pattern === '' || $pattern[0] === '#') {
            continue;
        }

        if ($pattern[0] === '!') {
            $negations[] = $theme_dir . '/' . ltrim(substr($pattern, 1), '/');
            continue;
        }

        if (strpos($pattern, '*') !== false) {
            if (preg_match('/^\*\.([a-zA-Z0-9]+)$/', $pattern, $m)) {
                // *.ext 形式 → 拡張子除外（存在チェック不要）
                $additions[] = ['type' => 'ext', 'path' => $m[1]];
            } else {
                // *CheatSheet.php 等の複合ワイルドカード → glob で実在ファイルに展開
                $matches = glob($theme_dir . '/' . $pattern);
                if (is_array($matches)) {
                    foreach ($matches as $match) {
                        $type        = is_dir($match) ? 'folder' : 'file';
                        $additions[] = ['type' => $type, 'path' => $match];
                    }
                }
            }
        } else {
            // 固定名のファイル・フォルダ
            $path        = $theme_dir . '/' . $pattern;
            $type        = is_dir($path) ? 'folder' : 'file';
            $additions[] = ['type' => $type, 'path' => $path];
        }
    }

    foreach ($additions as $entry) {
        if (!in_array($entry['path'], $negations, true)) {
            $exclude_files[] = $entry;
        }
    }

    return $exclude_files;
});

// 旧バックアップクラス対応（コンテンツ型バックアップのみ有効）
add_filter('wpvivid_get_backup_exclude_regex', function (array $exclude_regex, string $backup_type): array {
    if (!defined('WPVIVID_BACKUP_TYPE_CONTENT') || $backup_type !== WPVIVID_BACKUP_TYPE_CONTENT) {
        return $exclude_regex;
    }

    $sftpignore_path = get_stylesheet_directory() . '/.vscode/sftpignore';
    if (!file_exists($sftpignore_path)) {
        return $exclude_regex;
    }

    $theme_dir = rtrim(str_replace('\\', '/', get_stylesheet_directory()), '/');
    $lines     = file($sftpignore_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $patterns  = [];
    $negations = [];

    foreach ($lines as $line) {
        $pattern = trim($line);
        if ($pattern === '' || $pattern[0] === '#') {
            continue;
        }
        if ($pattern[0] === '!') {
            $negations[] = ltrim(substr($pattern, 1), '/');
            continue;
        }
        $patterns[] = $pattern;
    }

    foreach ($patterns as $pattern) {
        if (strpos($pattern, '*') !== false) {
            if (preg_match('/^\*\.([a-zA-Z0-9]+)$/', $pattern, $m)) {
                // *.ext は拡張子マッチ、パスベースの否定対象外
                $exclude_regex[] = _wpvivid_sftpignore_to_regex($pattern, $theme_dir);
            } else {
                // glob 展開して否定パターンを除去
                $matches = glob($theme_dir . '/' . $pattern);
                if (is_array($matches)) {
                    foreach ($matches as $match) {
                        $rel = ltrim(str_replace($theme_dir . '/', '', $match), '/');
                        if (!in_array($rel, $negations, true)) {
                            $regex = _wpvivid_sftpignore_to_regex($rel, $theme_dir);
                            if ($regex !== '') {
                                $exclude_regex[] = $regex;
                            }
                        }
                    }
                }
            }
        } else {
            if (!in_array($pattern, $negations, true)) {
                $regex = _wpvivid_sftpignore_to_regex($pattern, $theme_dir);
                if ($regex !== '') {
                    $exclude_regex[] = $regex;
                }
            }
        }
    }

    return $exclude_regex;
}, 10, 2);

function _wpvivid_sftpignore_to_regex(string $pattern, string $theme_dir): string
{
    $escaped_theme = preg_quote($theme_dir, '#');

    if (strpos($pattern, '*') !== false) {
        // * を .* に変換してテーマ内スコープなしで適用
        $regex_body = str_replace('\*', '.*', preg_quote($pattern, '#'));
        return '#' . $regex_body . '$#';
    }

    // 固定名またはサブパス（テーマルート直下に限定）
    return '#^' . $escaped_theme . '/' . preg_quote($pattern, '#') . '(/|$)#';
}
