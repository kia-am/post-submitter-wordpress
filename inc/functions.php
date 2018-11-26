<?php
function array_checked( $checked, $current, $echo = true ) {
	if(is_array($checked)){
	
	foreach( $checked as $val ){
		if($val === $current){
			$result = 'checked="checked"';
			break;
		}
		else
			$result = '';
	}
	
	if ( $echo )
		 echo $result;

	return $result;
	
	}
}

function telegram_get_me($token){
	$botifno = wp_remote_get("https://api.telegram.org/bot$token/getMe");
	return json_decode($botifno['body']);
	
}

function telegram_send_message($token,$channel_ids,$content){
	if(empty($token) || !isset($token))
		return __('Telegram Bot Token Was Not Found!','post-submitter');
	
	if(is_array($channel_ids)){
		$i = 0;
		foreach($channel_ids as $channel_id){
			if(!empty($channel_id)){
				$remote[$i]['body'] = wp_remote_retrieve_body(wp_remote_post("https://api.telegram.org/bot$token/sendMessage?chat_id=$channel_id&text=$content&parse_mode=HTML"));
				$remote[$i]['time'] = time();
				$remote[$i]['channel_id'] = $channel_id;
				$remote[$i]['text'] = urldecode($content);
				$i++;
			}
		}
	}
	return $remote;
}

function telegram_send_photo($token,$channel_ids,$content,$url){
	if(empty($token) || !isset($token))
		return __('Telegram Bot Token Was Not Found!','post-submitter');
	
	if(is_array($channel_ids)){
		$i = 0;
		foreach($channel_ids as $channel_id){
			if(!empty($channel_id)){
				$remote[$i]['body'] = wp_remote_retrieve_body(wp_remote_post("https://api.telegram.org/bot$token/sendPhoto?chat_id=$channel_id&caption=$content&photo=$url"));
				$remote[$i]['time'] = time();
				$remote[$i]['channel_id'] = $channel_id;
				$remote[$i]['text'] = urldecode($content);
				$i++;
			}
		}
	}
	return $remote;
}

function telegram_set_logs($telegram){
	$options = get_option('wl_ps_logs_opts');
	if(empty($options))
		$total = $telegram;
	else
		$total = array_merge($options,$telegram);
	
	update_option('wl_ps_logs_opts',$total);
}

function telegram_table_logs(){
	if(telegram_get_logs()){
		foreach(telegram_get_logs() as $log){
			$status = ($log['body']->ok)? '<span class="success">'.esc_html__('Sent','post-submitter').'</span>': '<span class="alert">'.esc_html__('Not Sent','post-submitter').'</span>';
			echo '<tr>';
			echo '<td>'.date('d/m/Y h:i:s',$log['time']).'</td>';
			echo '<td>'.$log['channel_id'].'</td>';
			echo '<td>'.$status.'</td>';
			echo '<td>'.$log['text'].'</td>';
			echo '</tr>';
		}
	}
}

function telegram_get_logs(){
	$options = get_option('wl_ps_logs_opts');
	if($options){
		$i = 0;
		foreach($options as $option){
			$result[$i]['body'] = json_decode($option['body']);
			$result[$i]['time'] = $option['time'];
			$result[$i]['channel_id'] = $option['channel_id'];
			$result[$i]['text'] = $option['text'];
			$i++;
		}
	}
	return $result;
}

function telegram_clear_logs(){
	update_option('wl_ps_logs_opts','');
	echo '<div class="notice notice-success is-dismissibale">
				<p>'.sprintf(esc_html__('Successfully Deleted.','post-submitter'),$val['channel_id']).'</p>
			</div>';
}
?>