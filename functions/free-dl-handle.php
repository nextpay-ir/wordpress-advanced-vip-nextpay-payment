<?php 
	if( ! defined('ABSPATH') ) die();
	add_action('init', 'av_free_download');
	function av_free_download(){
		global $av_settings, $avdb, $wpdb, $av_vip_dir, $av_current_user_vip, $av_httpDL;
		if( isset($_GET['action']) && $_GET['action'] == 'free_download' && isset($_GET['file_id']) && is_numeric($_GET['file_id']) ){
			$file_data = av_file_by_id( $_GET['file_id'] );
			$av_httpDL->speed = is_numeric($av_settings['free_dl_speed']) ? intval($av_settings['free_dl_speed']) : 100 ; 
			$av_httpDL->use_resume = false;
			$av_httpDL->set_byfile( $av_vip_dir . $file_data['encypted_name'] . '.' . $file_data['file_type'] ); 
			$av_httpDL->download( $file_data['file_name'] . '.' . $file_data['file_type'] );
			$wpdb->query( "UPDATE `".$avdb->files."` SET `downloads_time` = 1 + `downloads_time` WHERE ID = '".intval( $_GET['file_id'] )."'" );
			die();
		}
	}