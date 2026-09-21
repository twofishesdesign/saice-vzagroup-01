<?php
/**
 * Plugin Name: TXP
 * Version: 1.1.0
 * Description: Theme Extension Plugin.
 */
 
defined( 'ABSPATH' ) || exit;

require 'inc/gravity.php';

// Ensure all restrictions run only in the admin area. 
if ( is_admin() ) {
    require 'admin/core.php';
    require 'admin/email.php';
    require 'admin/media.php';
    require 'admin/roles.php';
    require 'admin/theme.php';
}

