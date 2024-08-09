<?php

namespace WPLog;

use WowLog\Vendor\Monolog\Logger;

class Log {
    private static self|null $instance    = null;
    private static array $loggers         = [];
    private static string $default_logger = 'default';
    private static string $current_logger = 'default';

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public static function add_logger( Logger $logger ): void {
        self::$loggers[ $logger->getName() ] = $logger;
    }

    public static function get_logger( string $name = 'default' ): Logger|null {
        return self::$loggers[ $name ] ?? null;
    }

    public static function remove_logger( string $name ): void {
        unset( self::$loggers[ $name ] );
    }

    public static function info( string $message, array $args = [] ): void {
        self::log( __FUNCTION__, sprintf( $message, ...$args ) );
    }

    public static function warning( string $message, array $args = [] ) {
        self::log( __FUNCTION__, sprintf( $message, ...$args ) );
    }

    public static function error( string $message, array $args = [] ) {
        self::log( __FUNCTION__, sprintf( $message, ...$args ) );
    }

    public static function debug( string $message, array $args = [] ) {
        self::log( __FUNCTION__, sprintf( $message, ...$args ) );
    }

    public static function log( string $level, string $message, array $context = [] ) {
        self::get_logger( self::$current_logger )->{ $level }( $message, $context );

        self::$current_logger = self::$default_logger;
    }

    public static function logger( string $name ): self {
        self::$current_logger = $name;

        return self::instance();
    }
}