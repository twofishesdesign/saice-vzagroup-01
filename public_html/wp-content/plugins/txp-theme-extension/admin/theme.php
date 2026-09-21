<?php

defined( 'ABSPATH' ) || exit;

/**
 * Remove theme update notice.
 */
function txp_remove_theme_update_notice(): void
{
    ?>
        <script>
            const elements = document.querySelectorAll('.notice p');

            elements.forEach(function(node, index) {
                let check = node.textContent.search("Please activate your theme license. Each domain where the theme is used requires a valid license.");

                if (check > -1) {
                    node.parentNode.remove();
                }
            });
        </script>
    <?php
}

/**
 * Remove theme license notices.
 */
function txp_admin_inline_css(): void
{
    ob_start(); 
    ?>

        <style>
            #wpbody .nav-tab.wpex-theme-license,
            #wpbody .totaltheme-notice.totaltheme-notice--license.notice.notice-error,
            #wpbody .totaltheme-plugins-panel__license-notice {
                display: none;
            }
        </style>

    <?php
    echo ob_get_clean();
}

/**
 * Check if theme/site license is active.
 */
add_action ('init', function(){
    if ( ! \TotalTheme\License_Manager::instance()->is_license_active() ) {
        add_action('admin_footer', 'txp_remove_theme_update_notice', 100);
        add_action('admin_head', 'txp_admin_inline_css', 20 );
    }
});
