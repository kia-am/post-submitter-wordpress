<?php
function wl_ps_settings_init(){
	register_setting(PLUGIN_NAME,'wl_ps_opts');
	$opts = get_option('wl_ps_opts');
	
	add_settings_section(
		'settings-sections',
		__('Settings Post Submitter','post-submitter'),
		'wl_ps_settings_section_cb',
		PLUGIN_NAME
	);
	
	add_settings_field(
		'bot-token',
		__('Enter Your Telegram Bot Token','post-submitter'),
		'wl_ps_settings_bot_token_cb',
		PLUGIN_NAME,
		'settings-sections',
		[
			'name' => 'bot_token',
			'label_for' => 'psot-submitter-settings-bot-token',
			'class' => 'psot-submitter-settings-bot-token',
			'options' => $opts
		]
	);
	
	add_settings_field(
		'channels_ids',
		__('List Of Channels','post-submitter'),
		'wl_ps_settings_channel_ids_cb',
		PLUGIN_NAME,
		'settings-sections',
		[
			'name' => 'channels_ids',
			'label_for' => 'psot-submitter-settings-channels-ids',
			'class' => 'psot-submitter-settings-channels-ids',
			'options' => $opts
		]
	);
	
	add_settings_field(
		'post_types',
		__('Show to','post-submitter'),
		'wl_ps_settings_post_types_cb',
		PLUGIN_NAME,
		'settings-sections',
		[
			'name' => 'post_types',
			'label_for' => 'psot-submitter-settings-post-types',
			'class' => 'psot-submitter-settings-post-types',
			'options' => $opts
		]
	);
}
add_action('admin_init','wl_ps_settings_init');

function wl_ps_settings_section_cb(){
	_e('Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.','post-submitter');
}

function wl_ps_settings_bot_token_cb($args){
	$opts = $args['options'];
?>
<input type="text" size="60" name="wl_ps_opts[<?php echo esc_attr($args['name']); ?>]" id="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_attr(@$opts[$args['name']]); ?>">
<?php
	$botinfo = telegram_get_me(@$opts['bot_token']);
	echo '<table class="post-submitter-settings-bot-token">';
	if(!empty($opts[$args['name']])){
		if($botinfo->ok){
			$botinfo = $botinfo->result;
			echo "<tr><td>".__('Bot ID:','post-submitter')."</td><td class='info'>$botinfo->id</td></tr>";
			echo "<tr><td>".__('Bot Name:','post-submitter')."</td><td class='info'>$botinfo->first_name</td></tr>";
			echo "<tr><td>".__('UserName:','post-submitter')."</td><td class='info'>$botinfo->username</td></tr>";
		}else{
			echo "<tr><td class='alert'>".__('The Entered Token is Wrong.','post-submitter')."</td></tr>";
		}
	}
	echo '</table>';
}
function wl_ps_settings_channel_ids_cb($args){
	$opts = $args['options'];
?>
<textarea name="wl_ps_opts[<?php echo esc_attr($args['name']); ?>]" id="<?php echo esc_attr($args['label_for']); ?>" cols="60" rows="10"><?php echo esc_html(@$opts[$args['name']]); ?></textarea>
<p class="description">
	<?php esc_html_e('Please Write Every ID in a Line. Example:','post-submitter'); echo '<br>'; esc_html_e('@welearn','post-submitter'); echo '<br>'; esc_html_e('@welearn2','post-submitter'); ?>
</p>
<?php
}
function wl_ps_settings_post_types_cb($args){
	$opts = $args['options'];
	
	$args_post_types = array('public' => true);
	$post_types = get_post_types($args_post_types,'names');
	foreach($post_types as $post_type){
		if($post_type != 'attachment'){
			$result[] = $post_type;
		}
	}
	
	foreach($result as $val){
	?>
	<label>
		<input type="checkbox" name="wl_ps_opts[<?php echo esc_attr($args['name']); ?>][]" value="<?php echo esc_attr($val); ?>" <?php array_checked( @$opts['post_types'], $val, true ); ?>>
		<span><?php echo esc_html($val); ?></span>
	</label>
	<?php
	}
}

function wl_ps_settings_page(){
	if(isset($_POST['submit_test'])){
		$options = get_option('wl_ps_opts');
		$content = urlencode($_POST['wl_ps_test_send']);
		$channel_ids = $options['channels_ids'];
		$channel_ids = explode("\n",$channel_ids);
		$token = $options['bot_token'];
		$telegram = telegram_send_message($token,$channel_ids,$content);
	}
	
	if(isset($telegram)){
		foreach($telegram as $val){
		
			$body = json_decode($val['body']);
			if($body->ok){
			?>
			<div class="notice notice-success is-dismissibale">
				<p><?php echo sprintf(esc_html__('Success, Posted to %s Channel','post-submitter'),$val['channel_id']) ?></p>
			</div>
			<?php
			}else{
			?>
			<div class="notice notice-error is-dismissibale">
				<p><?php echo sprintf(esc_html__('Error, Posted to %s Channel','post-submitter'),$val['channel_id']) ?></p>
			</div>
			<?php
			}
		}
	}
	
?>
<div class="wrap wl-psot-submitter">
<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form action="options.php" method="post">
<?php
settings_fields(PLUGIN_NAME); 
do_settings_sections(PLUGIN_NAME); 
submit_button(__('Save Settings','post-submitter'),'post-submitter');
?>
	
</form>

<h2><?php esc_html_e('Send For Testing','post-submitter'); ?></h2>
<form method="post" action="" class="post-submitter-test-send">
	<?php
	wp_editor('','wl_ps_test_send');
	?>
	<button class="button button-primary button-large" name="submit_test"><?php esc_html_e('Send a Test','post-submitter'); ?></button>
</form>
</div>
<?php
}

/* Admin Enqueue */
function wl_ps_settings_admin_enqueue( $hook_suffix ) {
	if('toplevel_page_post-submitter' != $hook_suffix)
		return;

	wp_enqueue_style( 'post-submitter-style', PLUGIN_URL.'/'.CSS_DIR.'/post-submitter.css', array(), VERSION );
}
add_action( 'admin_enqueue_scripts', 'wl_ps_settings_admin_enqueue' );