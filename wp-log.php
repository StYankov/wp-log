<?php
/**
 * Plugin Name: WP Log
 * Plugin URI: https://stoilyankov.com
 * Description: Integrate Monolog into WordPress
 * Version: 1.0.0
 * Author: Stoil Yankov
 * Author URI: https://stoilyankov.com.
 * License: GPL2
 */

require_once __DIR__ . '/vendor/autoload.php';

\WPLog\Plugin::instance();

function wp_log() {
    return \WPLog\Log::instance();
}