<?php

/**
 * Disable automatic installation of default Twenty themes.
 */
add_filter('wp_install_default_theme', '__return_false');
