<?php

defined( 'ABSPATH' ) || exit;

/**
 * Removes wp from title text (browser bar).
 */
function txp_browser_title(): string
{
    return sprintf("%s - Admin", get_bloginfo('name'));
}

add_filter('admin_title', 'txp_browser_title', 9999);


/**
 * Add copyright to footer.
 */
function txp_admin_footer_credit($name = ''): void
{
    $name = get_bloginfo('name');

    if ($name != '') {
        printf("<span>&copy; %s %s.</span>", date("Y"), $name);
    }

    return;
}

add_filter('admin_footer_text', 'txp_admin_footer_credit', 9999);

/**
 * Add PHP and theme version to footer.
 */
function txp_admin_footer_info(string $content): string
{
    global $wp_version;

    $php = phpversion();
	$theme = wp_get_theme('Total');

    return sprintf("<span>WP %s / PHP %s / %s %s</span>", $wp_version, $php, $theme->get('Name'), $theme->get('Version'));
}

add_filter('update_footer', 'txp_admin_footer_info', 9999);

/**
 * Removes wp logo from the admin menu.
 */
function txp_remove_wp_logo(object $admin_bar) : void
{
    if ( ! is_admin_bar_showing() ) return;
    $admin_bar->remove_node('wp-logo');
}

add_action('admin_bar_menu', 'txp_remove_wp_logo', 9999);
