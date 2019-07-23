<?php

	if( ! defined('ABSPATH') ) die();
	
	$avdb = new advanced_vip_db;
	
	$avEcrypt = new av_ecrypt;
	
	$av_settings = stripslashes_deep( @unserialize( get_option('av_settings') ) );
	
	$av_vip_dir = ABSPATH . @$av_settings['protected_files_dir'] . '/';
	
	
	function av_custom_auth( $user, $password ){
		$check = wp_authenticate_username_password( NULL, $user, $password );
		return is_wp_error( $check ) ? false : $check->data->ID;
	}
	
	function av_user_vip_check_by_id( $id ){
		global $avdb,$wpdb;
		if( 
			$wpdb->get_var("SELECT user_ID FROM $avdb->users WHERE user_ID='".$id."'") === null ||
			$wpdb->get_var("SELECT expire_date FROM $avdb->users WHERE user_ID='".$id."'") < time()
		){
			return false;
		} else{
			return true;
		}
	}
	
	function av_get_user_vip_data_by_id( $id ){
		global $avdb,$wpdb;
		return $wpdb->get_row( "SELECT * FROM ".$avdb->users." WHERE user_ID = '".$id."'" , ARRAY_A );
	}
	
	function av_user_vip_check_by_username_password( $user, $password ){
		global $av_settings;
		
		
		$check = wp_authenticate_username_password( NULL, $user, $password );
	
		if( is_wp_error( $check ) )
			return false;
		
		if( count( array_intersect( (array) $check->roles , (array) @ $av_settings['default_vip_roles'] ) ) > 0 )
			return true;
		
		return av_user_vip_check_by_id( $check->data->ID );
		
	}
	
	
	function av_file_path_by_id( $id ){
		global $av_vip_dir, $wpdb, $avdb;
		$query = $wpdb->get_row( $wpdb->prepare("SELECT encypted_name,file_type FROM ".$avdb->files." WHERE ID = '%d'" , $id) , ARRAY_A );
		return $av_vip_dir . $query['encypted_name'] . '.' . $query['file_type'];
	}
	
	function av_file_by_id( $id ){
		global $av_vip_dir, $wpdb, $avdb;
		$file = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$avdb->files." WHERE ID = '%d'", $id) , ARRAY_A );
		return $file;
	}
	
	$av_httpDL = new av_httpdownload;
	$advanced_vip = new advanced_vip;
	
	if( 
		! $wpdb->get_var("SELECT user_ID FROM $avdb->users WHERE user_ID='".get_current_user_id()."'") === null ||
		! $wpdb->get_var("SELECT expire_date FROM $avdb->users WHERE user_ID='".get_current_user_id()."'") < time() ||
		count( array_intersect( (array) $current_user->roles , (array) @ $av_settings['default_vip_roles'] ) ) > 0
	){
	
		$av_current_user_vip = true;		
		
	} else{
	
		$av_current_user_vip = false;
		
	}
	

	
	register_activation_hook( av_plugin_file , 'av_install' );
	function av_install(){
		require_once( av_func_dir . 'install.php' );
	}