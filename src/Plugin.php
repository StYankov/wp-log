<?php

namespace WPLog;

use WowLog\Vendor\Monolog\Formatter\LineFormatter;
use WowLog\Vendor\Monolog\Handler\BufferHandler;
use WowLog\Vendor\Monolog\Handler\FingersCrossedHandler;
use WowLog\Vendor\Monolog\Handler\StreamHandler;
use WowLog\Vendor\Monolog\Level;
use WowLog\Vendor\Monolog\Logger;

final class Plugin {
    private static self $instance;

    private function __construct() {
        $this->init_default_logger();
    }

    private function init_default_logger() {
        Log::instance();

        $stream_handler = new StreamHandler( self::get_logs_folder_path() . '/wp-log.log', Level::Debug );
        $formatter = new LineFormatter( null, null, false, true );
        $stream_handler->setFormatter( $formatter );

        $default_logger = new Logger( 'default' );
        $default_logger->pushHandler( $stream_handler );

        Log::add_logger( $default_logger );
    }

    private function get_email_handler() {
        $email_handler           = new MailHandler( 'st.yankov00@gmail.com', 'Fatal error', 'Gifto alert facility' );
        $fingers_crossed_handler = new FingersCrossedHandler(
            $email_handler,
            Level::Critical
        );

        $bufferHandler = new BufferHandler($fingers_crossed_handler, 10);

        return $bufferHandler;
    }

    public static function instance(): self {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get_logs_folder_path(): string {
        $uploads_dir = wp_upload_dir();

        return $uploads_dir['basedir'] . '/logs';
    }
}