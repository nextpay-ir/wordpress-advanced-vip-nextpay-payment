<?php
	if( ! defined('ABSPATH') ) die();
	
	add_action( 'parse_request' , 'av_charge_account' );

	function av_charge_account(){
		global $wpdb,$av_settings;
		
		if( is_user_logged_in() ) {
		
			if( isset($_GET['action']) && $_GET['action'] == 'vip_charging' && isset($_POST['accout_type']) && isset($av_settings['vip_time_id']) ) {
			
				$accout_offset = intval(array_search($_POST['accout_type'],$av_settings['vip_time_id']));
				$accout_price = intval($av_settings['vip_time_price'][$accout_offset]);
				$accout_time = intval($av_settings['vip_time'][$accout_offset]);
			
				if( $av_settings['payment_agancy'] == 'nextpay' ) {
					
					do_action( 'av_before_payment' , 'nextpay' , array( $accout_price , $accout_time , (isset($_POST['phone_num']) ? $_POST['phone_num'] : '') ) );
					
				}
				else {
					
					wp_die('مشکل در درگاه پرداخت');
					
				}
			
			} 
		
		}
		
	}
