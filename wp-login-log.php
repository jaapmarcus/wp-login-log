<?php
/**
 * Plugin Name: WP Login log
 * Description: Logs any failed / success login attempts
 * Version: 1.0
 * Author: Jaap Marcus
 */
 
 // Abort the execution if the Wordpress URL is not set
 if (!defined('ABSPATH')) {
				 exit;
 }

 function get_ip_address(){
		 foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
				 if (array_key_exists($key, $_SERVER) === true){
						 foreach (explode(',', $_SERVER[$key]) as $ip){
								 $ip = trim($ip);
	
								 if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
										 return $ip;
								 }
						 }
				 }
		 }
 }
 
function log_user_login($user_login, $user){
	$ip = get_ip_address();
	$log_line = '['.date('Y-m-d H:i:s').'] '.$user -> data -> user_login .' [ '. $user -> data -> ID .' ] - '.$ip ."\r\n";	
	$upload_folder = wp_upload_dir();
	if(!file_exists($upload_folder['basedir'].'/logs/')){
		if(!mkdir($upload_folder['basedir'].'/logs/')){
			echo "Unable to create log dir";
			die();
		}
	}
	file_put_contents($upload_folder['basedir'].'/logs/login_history.log', $log_line, FILE_APPEND);
}

add_action('wp_login', 'log_user_login', 10, 2);