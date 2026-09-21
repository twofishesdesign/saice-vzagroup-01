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


add_action('learndash-course-infobar-action-cell-after', 'total_login');

function total_login() {
	?>
<div>
	<div class="xoo-el-login-tgr ld-button">Login to Enroll</div>
</div>


	<?php
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
	
// 	return 'You must be logged in.';
	return do_shortcode('[xoo_el_inline_form forms="login" active="login" pattern="separate" navstyle="tabs" profile="yes" register_redirect="https://saice.vzagroup.com/certificates/" width="700"]');
}

add_shortcode( 'certlist', 'certificate_list' );
