<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

// New custom user role
add_action('init', 'cloneRole');

function cloneRole() {
	global $wp_roles;
	if (empty($wp_roles))
		$wp_roles = new WP_Roles();

	$admin = $wp_roles->get_role('administrator');

	$wp_roles->add_role('subadmin', 'Subbeheerder', $admin->capabilities);
}


// Remove Administrator role from roles list
add_action('editable_roles', 'hide_adminstrator_editable_roles');
function hide_adminstrator_editable_roles($roles) {
	if (!empty($roles['administrator'])) {
		$current_user = wp_get_current_user();
		if ($current_user->roles[0] != 'administrator')
			unset($roles['administrator']);
	}
	return $roles;
}

// Make subadmin the default user role
add_filter('pre_option_default_role', function ($default_role) {
	return 'subadmin';
	return $default_role;
});


// Giving subadmins Access to Gravity Forms
function add_grav_forms() {
	$role = get_role('subadmin');
	$role->add_cap('gform_full_access');
}
add_action('admin_init', 'add_grav_forms');


// WP Schema pro access
add_filter('wp_schema_pro_role', 'wp_schema_pro_access');
function wp_schema_pro_access($roles) {
	$new_roles = ['subadmin'];
	$roles = array_merge($roles, $new_roles);
	return $roles;
}
