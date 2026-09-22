<?php

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_type = 'sfwd-question';

add_filter( 'manage_' . $post_type . '_posts_columns', 'wpex_question_add_columns' );
add_action( 'manage_' . $post_type . '_posts_custom_column', 'wpex_question_display_columns', 10, 2);

/**
 * Add columns.
 */
function wpex_question_add_columns( $columns )
{
    $columns['question'] = __( 'Question', 'smashing' );
    $columns['answer'] = __( 'Answer', 'smashing' );
    return $columns;
}

/**
 * Display columns.
 */
function wpex_question_display_columns ( $column, $post_id )
{
    // Question (WP Content) column
    if ( 'question' === $column ) {
        the_content();
    }
    // Answer (True / False) column
    if ( 'answer' === $column ) {

        global $wpdb;
        $question_id = get_post_meta($post_id, 'question_pro_id', true);
        $table_name = $wpdb->prefix . 'wp_pro_quiz_question';

        if ($question_id != '') {

            $results = $wpdb->get_results(
                "SELECT answer_data FROM $table_name WHERE id = $question_id"
            );

            $data = unserialize( $results[0]->answer_data );

            foreach ($data as $data_object) {
                $correct = '';
                $correct = $data_object->isCorrect();
                if ( $correct == 1) {
                    echo '<strong>'. $data_object->getAnswer() . '</strong>';
                }
            }
        }
    }
}