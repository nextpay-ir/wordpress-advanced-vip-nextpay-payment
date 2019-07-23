<?php
	if( ! defined('ABSPATH') ) die;
	
	global $av_settings;
	
	if( isset( $av_settings['email_on_close_expire'] ) && $av_settings['email_on_close_expire'] == 'on' ) {
		add_action( "avHourlyEvenetCloseExpireUser" , "avSendEmailToCloseExpireUser" );
		
		function avSendEmailToCloseExpireUser( $data ){
			global $av_settings;
		
			$site_url = parse_url(site_url());
			$site_url = $site_url['host'];
					
			$subject  = $av_settings['close_expire_mail_subject'];
			
			$headers  = 'From: no-reply@'.$site_url. "\r\n" .
				'MIME-Version: 1.0' . "\r\n" .
				'Content-type: text/html; charset=utf-8' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			
			
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
				@ $av_settings['close_expire_mail_template']		
			);
								
								
			@mail($data['member-mail'], $subject, $text, $headers);
		
		
		}
	}	