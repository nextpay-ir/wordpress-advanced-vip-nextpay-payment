<?php
	if( ! defined('ABSPATH') ) die;
	
	global $av_settings;
	
	if( isset( $av_settings['sms_on_close_expire'] ) && $av_settings['sms_on_close_expire'] == 'on' ) {
		add_action( "avHourlyEvenetCloseExpireUser" , "avSendSMSToCloseExpireUser" );
		
		function avSendSMSToCloseExpireUser( $data ){
			global $av_settings;
		
			
			$text = str_replace(
				array(
					'{member-name}',
					'{expire-jdate}',
					'{expire-hdate}',
					'{member-mail}',
					'{start-jdate}',
					'{start-hdate}',
					'{site-title}',
					'{site-url}'
				),
				array(
					$data['member-name'],
					$data['expire-jdate'],
					$data['expire-hdate'],
					$data['mail'],
					$data['start-jdate'],
					$data['start-hdate'],
					$data['site-title'],
					$data['site-url']
				),
				@ $av_settings['sms_on_close_expire_template']
			);
								
			avSendSMS( $text , $data['phone'] );
		
			file_put_contents( 'ss.txt' , 'hi!' );
		
		}
	}	