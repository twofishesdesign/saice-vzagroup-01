<?php

defined( 'ABSPATH' ) || exit;

/**
 * Add "Admin" role and capabilities
 */
function txp_add_admin()
{
    // Check if the role already exists to avoid redundant database writes
    if ( ! get_role( 'admin' ) ) {
        global $wp_roles;

        // 1. Get the existing administrator role object
        $admin_role = get_role('administrator');

        // 2. Fetch its capabilities (an associative array)
        $admin_caps = $admin_role->capabilities;

        // 3. Remove capabilities
        $removeCaps = [
            'add_users',
            'create_users',
            'edit_users',
            'remove_users',
            'list_users',
            'promote_users',
            'activate_plugins',
            'edit_plugins',
            'update_plugins',
            'delete_plugins',
            'install_plugins',
            'delete_themes',
            'install_themes',
            'edit_themes',
            'install_themes',
            'switch_themes',
            'import',
            'export',
        ];

        foreach ($removeCaps as $caps) {
            unset($admin_caps[$caps]);
        }

        // 4. Add capabilities
        $addCaps = [
            'gravityforms_create_form',
            'gravityforms_edit_forms',
            'gravityforms_preview_forms',
            'gravityforms_view_entries',
            'gravityforms_edit_entries',
            'gravityforms_view_entry_notes',
            'gravityforms_edit_entry_notes',
            'gravityforms_export_entries',
            'gravityforms_system_status',
            'wpseo_manage_options',
            'wpseo_edit_advanced_metadata',
            'wpseo_bulk_edit',
        ];

        foreach ($addCaps as $caps) {
            $admin_caps[$caps] = true;
        }

        // 5. Update role and capabilities
        add_role(
            'admin',
            __( 'Admin' ),
            $admin_caps
        );
    }
}

add_action('init', 'txp_add_admin');
