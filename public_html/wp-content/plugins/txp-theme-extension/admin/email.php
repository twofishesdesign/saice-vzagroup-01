<?php

defined( 'ABSPATH' ) || exit;

add_filter('auto_plugin_update_send_email', '__return_false');
add_filter('auto_theme_update_send_email', '__return_false');

/**
 * Update core email notification.
 */
function txp_update_core_email(bool $send, string $type, object $core_update, mixed $result ): bool
{
    if ( !empty( $type ) && $type == 'critical' ) {
        return $send;
    }

    return false;
}

add_filter('auto_core_update_send_email', 'txp_update_core_email', 10, 4);
