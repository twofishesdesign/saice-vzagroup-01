<?php

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'quizstats', 'wpex_quizstats' );
add_shortcode( 'userlink', 'wpex_userlink' );

/**
 * "quizstats" Shortcode.
 */
function wpex_quizstats() {

    $user_id = get_current_user_id();

    $quizinfo = get_user_meta( $user_id);

    $timestamp = do_shortcode('[quizinfo show="time"]');

    $quizstats = unserialize($quizinfo['_sfwd-quizzes'][0]);

    $ref_id = '';

    foreach ($quizstats as $quizstat) {
        if ($quizstat['time'] == $timestamp) {
            $ref_id = $quizstat['statistic_ref_id'];
        }
    }

    global $wpdb;

    $results = $wpdb->get_results( 
        $wpdb->prepare( 
            "SELECT form_data FROM {$wpdb->prefix}wp_pro_quiz_statistic_ref WHERE statistic_ref_id = %d", 
            $ref_id
        ) 
    );


    if (!empty($results)) {
        $quiz_results = json_decode($results[0]->form_data, true);

        ob_start();

        echo $quiz_results[1];

        return ob_get_clean();
    }

    return '';
}

/**
 * "userlink" Shortcode.
 */
function wpex_userlink() {
    $user_id = get_current_user_id();
    return '<a href="'.site_url().'/wp-admin/user-edit.php?user_id='.$user_id.'" target="_blank" rel="noopener">Link</a>';
}
