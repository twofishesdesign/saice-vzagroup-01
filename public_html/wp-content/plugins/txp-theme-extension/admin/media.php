<?php

defined( 'ABSPATH' ) || exit;

/**
 * Add auto alt tags to images.
 */
function txp_add_auto_alt_tags(int $post_id): void
{
    // Check if uploaded file is an image.
    if ( wp_attachment_is_image($post_id) ) {
        $get_title = get_post($post_id)->post_title;

        $set_title = txp_process_title($get_title);

        // Set the image Alt-Text.
        update_post_meta($post_id, '_wp_attachment_image_alt', $set_title);

        // Set the image meta.
        wp_update_post([
            'ID'			=> $post_id,	// Specify the image (ID) to be updated
            'post_title'	=> $set_title,	// Set image title to sanitized title
            'post_content'	=> $set_title,	// Set image description to sanitized title
        ]);
    }
}

add_action('add_attachment', 'txp_add_auto_alt_tags', 100);

/**
 * Process title.
 */
function txp_process_title(string $get_title): string
{
    // Split title into keywords by dash, underscore and open space.
    $keywords = preg_split("/[-_ ]+/", $get_title);

    $set_title = [];

    foreach ($keywords as $key => $value) {
        $set_title[] = txp_sanitize_string($value);
    }

    return implode(' ', $set_title);
}

/**
 * Sanitize string.
 */
function txp_sanitize_string(string $string): string
{
    // Ignore uppercase strings.
    if ( ctype_upper($string) ) {
        return $string;
    }

    // Sanitize:  capitalize first letter (remaining letters lowercase).
    return ucwords( strtolower($string) );
}
