<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
	exit();
}

delete_option('wl_ps_opts');
delete_option('wl_ps_logs_opts');
?>