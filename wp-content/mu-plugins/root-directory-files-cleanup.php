<?php
/**
 * 管理者ログイン時に WP ルート直下の開発専用ファイルを削除する。
 * 削除したファイルは error_log に記録する。
 */

add_action('wp_login', function (string $user_login, WP_User $user): void {
    if (wp_get_environment_type() === 'local') {
        return;
    }
    if (!$user->has_cap('manage_options')) {
        return;
    }

    $root = untrailingslashit(ABSPATH);

    $targets = [
        '.wp-env.json',
        '.git',
        '.gitignore',
        'local-xdebuginfo.php',
        'wp-cli.yml',
        'README.md',
        'wp-config-sample.php',
    ];

    $deleted = [];

    foreach ($targets as $name) {
        $path = $root . '/' . $name;
        if (!file_exists($path) && !is_link($path)) {
            continue;
        }
        if (_tc_delete($path)) {
            $deleted[] = $name;
        }
    }

    if (!empty($deleted)) {
        error_log('[theme-cleanup] Deleted: ' . implode(', ', $deleted));
    }
}, 10, 2);

function _tc_delete(string $path): bool
{
    if (is_link($path) || is_file($path)) {
        return @unlink($path);
    }

    if (is_dir($path)) {
        try {
            $items = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                if ($item->isLink() || $item->isFile()) {
                    @unlink($item->getPathname());
                } elseif ($item->isDir()) {
                    @rmdir($item->getPathname());
                }
            }
        } catch (Exception $e) {
            error_log('[theme-cleanup] Error deleting ' . $path . ': ' . $e->getMessage());
            return false;
        }
        return @rmdir($path);
    }

    return false;
}
