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

        do_action( 'wp_log_init' ); 
    }

    private function init_default_logger() {
        Log::instance();

        Log::add_logger( self::create_file_logger( 'default', 'default.log' ) );
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

    public static function create_file_logger( string $log_name, string $log_file ) : Logger {
        $stream_handler = new StreamHandler( self::get_logs_folder_path() . '/' . $log_file, Level::Debug );
        $formatter      = new LineFormatter( null, null, false, true );
        $stream_handler->setFormatter( $formatter );

        $logger = new Logger( $log_name );
        $logger->pushHandler( $stream_handler );

        return $logger;
    }
}