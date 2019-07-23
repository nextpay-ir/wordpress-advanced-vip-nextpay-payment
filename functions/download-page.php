<?php 
	if( ! defined('ABSPATH') ) die();
	add_action('init', 'av_download_page');
	function av_download_page(){
		global $av_settings, $avdb, $wpdb;
		if( isset($_GET['action']) && $_GET['action'] == 'av_download' && isset($_GET['id']) ){
			$query = $wpdb->get_row( $wpdb->prepare( "SELECT file_name,file_size,downloads_time FROM ".$avdb->files." WHERE ID = %d" , intval($_GET['id']) ) , ARRAY_A );
			$vip_link = site_url().'/?action=vip_download&file_id='.$_GET['id'];
			$free_link = site_url().'/?action=free_download&file_id='.$_GET['id'];
			$file_name = $query['file_name'];
			$file_size = advanced_vip::bytesToSize($query['file_size']);
			$download_count = $query['downloads_time'];
			$html = str_replace(array('{vip-link}','{free-link}','{file-name}','{file-size}','{downloads-count}'),array($vip_link,$free_link,$file_name,$file_size,$download_count),$av_settings['download_page_template']);
			die( $html );
		}
	}