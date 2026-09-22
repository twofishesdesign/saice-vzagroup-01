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

require 'inc/cert-list.php';
require 'inc/get-records.php';
require 'inc/quiz-stats.php';

// Ensure all restrictions run only in the admin area. 
if ( is_admin() ) {
    require 'admin/admin-columns.php';
}

// UPDATE Show Certificates Anywhere for LearnDash plugin
// show-certificates-anywhere-for-learndash / show-learndash-certificate-anywhere.php
//
// function slca_hydrate_certificate_records( $records, $user_id ) {
// 	$certificates = array();
// 	$user_id      = absint( $user_id );

// 	/* TFD */
// 	$records = apply_filters( 'wpex_learndash_records', $records, $user_id);
// 	/* TFD */

// 	// ...
// }

