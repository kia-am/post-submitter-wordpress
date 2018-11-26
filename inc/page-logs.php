<?php
function wl_ps_logs_page(){
	if($_GET['page'] == 'post-submitter-logs' && isset($_GET['clearall']) && $_GET['clearall'] == '1'){
		telegram_clear_logs();
	}
?>
<div class="wrap post-submitter-logs">
	<h2><?php esc_html_e('All Logs','post-submitter'); ?>
	<a href="<?php echo admin_url('admin.php?page=post-submitter-logs&clearall=1') ?>" class="add-new-h2"><?php esc_html_e('Clear All','post-submitter'); ?></a></h2>
	
	<table class="widefat fixed">
		<thead>
			<tr>
				<th style="width:15%" class="manage-column" scope="col"><?php esc_html_e('Date','post-submitter'); ?></th>
				<th style="width:15%" class="manage-column" scope="col"><?php esc_html_e('Channel','post-submitter'); ?></th>
				<th style="width:15%" class="manage-column" scope="col"><?php esc_html_e('Status','post-submitter'); ?></th>
				<th class="manage-column" scope="col"><?php esc_html_e('Message','post-submitter'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><?php telegram_table_logs(); ?></tr>
		</tbody>
	</table>
</div>
<?php
}
/* Admin Enqueue */
function wl_ps_logs_admin_enqueue( $hook_suffix ) {
	if('post-submitter_page_post-submitter-logs' != $hook_suffix)
		return;

	wp_enqueue_style( 'post-submitter-style', PLUGIN_URL.'/'.CSS_DIR.'/post-submitter.css', array(), VERSION );
}
add_action( 'admin_enqueue_scripts', 'wl_ps_logs_admin_enqueue' );