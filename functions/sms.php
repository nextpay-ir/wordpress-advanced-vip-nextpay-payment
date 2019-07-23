<?php 
	if( ! defined('ABSPATH') ) die();
	

	function avSendSMS( $text = '' , $number = 0 ){
		global $av_settings;
		
		if( $av_settings['sms_agancy'] == 'parandsms' )
			return avParandSMS( $text , $number );
		else if( $av_settings['sms_agancy'] == 'smsdehi' )
			return avSMSdehi( $text , $number );
	}
	
	
	
	function avParandSMS( $content = '' , $number = 0 ){
		global $av_settings;
		
		if( empty( $av_settings['parandsms_username'] ) || empty( $av_settings['parandsms_password'] ) ) return;
		
		$response = wp_remote_post(
			'http://parandsms.ir/post/sendSMS.ashx', 
			array(
				'body' => array( 
					'from' => $av_settings['parandsms_from'], 
					'to' => $number,
					'text' => urldecode($content),
					'username' => $av_settings['parandsms_username'],
					'password' => $av_settings['parandsms_password']
				)
			)
		);
		
		if( ! is_wp_error( $response ) )
			return preg_match( '/1/' , (int) trim($response['body']) ) === 1 ? true : false;
		
	}
	
	
	
	function avSMSdehi( $content = '' , $number = 0 ){
		global $av_settings;
		
		if( empty( $av_settings['smsdehi_username'] ) || empty( $av_settings['smsdehi_password'] ) ) return;
		
		$from = ( @ $av_settings['smsdehi_from'] == 'custom_number' && is_numeric( $av_settings['smsdehi_customNumber'] ) ) ? $av_settings['smsdehi_customNumber'] : $av_settings['smsdehi_from'];
		
		$response = wp_remote_post(
			'http://smsdehi.ir/API/SendSms.ashx', 
			array(
				'body' => array( 
					'from' => $av_settings['smsdehi_from'], 
					'To' => $number,
					'text' => $content,
					'username' => urldecode($av_settings['smsdehi_username']),
					'password' => urldecode($av_settings['smsdehi_password']),
					'flash' => '0',
				)
			)
		);
		
		if( ! is_wp_error( $response ) )
			return strlen( trim($response['body']) ) > 4 ? true : false;
			
	}