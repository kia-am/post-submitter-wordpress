<?php
function wl_ps_add_publish_meta_otpions($post_obj){
	global $post;
	$get_option = get_option('wl_ps_opts');
	$post_types = @$get_option['post_types'];
	if(is_array($post_types)){
		foreach($post_types as $post_type){
			if($post_type == $post->post_type){
				echo '<div class="misc-pub-section">';
				echo '<label><input type="checkbox" name="send_tlg">'.__('Send to my Telegram Channel','post-submitter').'</label><br><br>';
				echo '<label style="display:none"><input type="checkbox" name="send_image_tlg">'.__('Send Featured Image','post-submitter').'</label>';
				echo '</div>';
			}
		}
	}
}
add_action('post_submitbox_misc_actions','wl_ps_add_publish_meta_otpions');

function wl_ps_render_send_messag($post_id,$post_obj,$update){
	$get_option = get_option('wl_ps_opts'); 
	$post_types = $get_option['post_types'];
	$access = 0;
	foreach($post_types as $post_type){
		if($post_type == $post_obj->post_type){
			$access++;
		}
	}
	
	if($access == 0) return;
	
	if(wp_is_post_revision($post_id)) return;
	
	if(isset($_POST['send_tlg']) && $_POST['send_tlg'] == 'on'){
		
		$token = $get_option['bot_token'];
		$channel_ids = $get_option['channels_ids'];
		$channel_ids = explode("\n",$channel_ids);
		$content = $post_obj->post_content."\n";
		$content .= $post_obj->guid;
		$content = urlencode($content);
		$url = get_the_post_thumbnail_url($post_id,'post-thumbnail');
		if(isset($_POST['send_image_tlg']) && $_POST['send_image_tlg'] == 'on' && $url != false){
			$telegram = telegram_send_photo($token,$channel_ids,$content,$url);
		}else{
			$telegram = telegram_send_message($token,$channel_ids,$content);
		}
		telegram_set_logs($telegram);
	}
	
}
add_action('save_post','wl_ps_render_send_messag',10,3);

/* Post Admin Enqueue */
function post_admin_enqueue( $hook_suffix ) {
	if('post.php' != $hook_suffix && 'post-new.php' != $hook_suffix)
		return;

	wp_enqueue_script( 'post-submitter-jqeury', PLUGIN_URL.'/'.JS_DIR.'/post-submitter.js', array('jquery'), VERSION, false );
}
add_action( 'admin_enqueue_scripts', 'post_admin_enqueue' );
?>