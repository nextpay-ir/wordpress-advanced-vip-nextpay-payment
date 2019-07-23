<?php 
	
	if( ! defined('ABSPATH') ) die();
	
	add_action('init', 'av_vip_download');
	function av_vip_download(){
		global $av_settings, $avdb, $wpdb, $av_vip_dir, $av_current_user_vip, $av_httpDL;
		if( isset($_GET['action']) && $_GET['action'] == 'vip_download' && isset($_GET['file_id']) && is_numeric($_GET['file_id']) ){
			
			if( $av_current_user_vip ){
			
				$file_data = av_file_by_id( $_GET['file_id'] );
				$av_httpDL->set_byfile( $av_vip_dir . $file_data['encypted_name'] . '.' . $file_data['file_type'] ); 
				$av_httpDL->download( $file_data['file_name'] . '.' . $file_data['file_type'] );
				$wpdb->query( "UPDATE `".$avdb->files."` SET `downloads_time` = 1 + `downloads_time` WHERE ID = '".intval( $_GET['file_id'] )."'" );
				
			} else{
			
				$LoginSuccessful = false;
				if ( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) ){
					$Username = $_SERVER['PHP_AUTH_USER'];
					$Password = $_SERVER['PHP_AUTH_PW'];
					if ( av_user_vip_check_by_username_password( $Username, $Password ) === true ){
						$LoginSuccessful = true;
					}
				}
				if ( ! $LoginSuccessful ){
					header('WWW-Authenticate: Basic realm="Enter User name and Password for VIP Download."');
					header('HTTP/1.0 401 Unauthorized');
					print "Login failed!\n";
				}
				else {
					$file_data = av_file_by_id( $_GET['file_id'] );
					$av_httpDL->set_byfile( $av_vip_dir . $file_data['encypted_name'] . '.' . $file_data['file_type'] ); 
					$av_httpDL->download( $file_data['file_name'] . '.' . $file_data['file_type'] );
					$wpdb->query( "UPDATE `".$avdb->files."` SET `downloads_time` = 1 + `downloads_time` WHERE ID = '".intval( $_GET['file_id'] )."'" );
				}
				
			}
			die();
		}
	}