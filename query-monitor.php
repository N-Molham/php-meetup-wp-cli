<?php
/**
 * Plugin Name: PHP Melbourne - Custom WP-CLI Command
 * Description: Introduction to registering custom WP-CLI commands
 * Version: 1.0.0
 * Plugin URI: https://github.com/N-Molham/php-meetup-wp-cli
 * Author: Nabeel Molham
 * Author URI: https://nabeel.molham.me/
 * Requires PHP: 8.1
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! defined('WP_CLI') || ! WP_CLI) {
    return;
}

WP_CLI::add_command('random-posts', static function () {
    WP_CLI\Utils\format_items(
        'table',
        get_posts(['orderby' => 'rand', 'posts_per_page' => '10']),
        ['ID', 'post_title', 'post_status', 'post_date']
    );
});
