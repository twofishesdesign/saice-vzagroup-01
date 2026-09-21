<?php
/**
 * Plugin Name:       LearnDash Extension
 * Plugin URI:        https://lmscrafter.com/downloads/show-learndash-certificate-anywhere/
 * Description:       Extends Learndash and certificate functionality
 * Version:           1.0.0
 * Requires at least: 7.1.1
 * Requires PHP:      8.4
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appends a <a> tag with URL to the content.
 * 
 * @param array $atts the shortcode attributes.
 * @param sring $content the editor enclosed content.
 * @return string
 */
function certificate_list()
{
	if ( is_user_logged_in() ) {
		$allowed_roles = array( 'administrator', 'subscriber', 'customer' );
		
		$user = wp_get_current_user();

		// Check if any of the user's roles match the allowed roles
		if ( array_intersect( $allowed_roles, (array) $user->roles ) ) {
			
				$shortcode = '[ld_earned_certificates user_id="'. $user->ID .'" type="course"]';
	
				return sprintf( '%s', do_shortcode( $shortcode ) );	
		} else {
			// The user doesn't match any of the roles
			echo 'Access denied.';
		}
	}

    // 	Return 'You must be logged in.';
	return do_shortcode('[xoo_el_inline_form forms="login" active="login" pattern="separate" navstyle="tabs" profile="yes" register_redirect="https://saice.vzagroup.com/certificates/" width="700"]');
}

add_shortcode( 'certlist', 'certificate_list' );
