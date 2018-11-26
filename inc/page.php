<?php
function wl_ps_add_page(){
	add_menu_page(
		__('Post Submitter','post-submitter'),
		__('Post Submitter','post-submitter'),
		'manage_options',
		'post-submitter',
		'wl_ps_settings_page',
		PLUGIN_URL.'/'.IMG_DIR.'/icon-post-submitter.png',
		99
	);
	
	add_submenu_page(
		'post-submitter',
		__('Post Submitter','post-submitter'),
		__('Settings','post-submitter'),
		'manage_options',
		'post-submitter',
		'wl_ps_settings_page'
	);
	
	add_submenu_page(
		'post-submitter',
		__('Logs','post-submitter'),
		__('Logs','post-submitter'),
		'manage_options',
		'post-submitter-logs',
		'wl_ps_logs_page'
	);
}
add_action('admin_menu','wl_ps_add_page');

function wl_ps_add_action_links($links){
	$links[] = '<a href="'.esc_url(admin_url('admin.php?page='.PLUGIN_NAME)).'">'.esc_html__('Settings','post-submitter').'</a>';
	return $links;
	
}
add_filter('plugin_action_links_'.BASE_PLUGIN_DIR,'wl_ps_add_action_links');