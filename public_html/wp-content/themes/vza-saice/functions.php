<?php
/**
 * Child theme functions
 *
 * Text Domain: wpex
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load css files
 *
 * @link http://codex.wordpress.org/Child_Themes
 */

function total_child_enqueue_scripts() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'Total' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), $version );
	wp_enqueue_style( 'child-custom', get_stylesheet_directory_uri() .'/assets/css/custom.css', array(), $version );

}

add_action( 'wp_enqueue_scripts', 'total_child_enqueue_scripts' );

function total_login() {
	?>
<div>
	<div class="xoo-el-login-tgr ld-button">Login to Enroll</div>
</div>
	<?php
}

add_action('learndash-course-infobar-action-cell-after', 'total_login');
