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

use Nmolham\PhpMeetupWpCli\Plugin;

if (! defined('ABSPATH')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

Plugin::init();
