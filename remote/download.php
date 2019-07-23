<?php

	require_once('config.php');
	
	require_once('http.class.php');
	$av_http = new av_httpdownload;

	function av_remote_auth_check( $data ){
		global $av_config;
		$request = '';
		$request .= 'action=av_user_auth';
		$request .= '&user_name='.$data[0];
		$request .= '&user_password='.$data[1];
		$request .= '&confirm_key='.$av_config['key'];
		$response = '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $av_config['site_url'] );
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$response = curl_exec($ch);
		curl_close($ch);
		if( $response == 'true' )
			return true;
		else
			return false;
	}
	
	if( empty( $_SERVER['PATH_INFO'] ) )
		return;
	
	
	
	$fileData = explode( '/' , ltrim( $_SERVER['PATH_INFO'] , '/' ) );
	
	$filePath = $av_config['files_path'] . $fileData[0];
	
	$isFree = ( isset( $fileData[1] ) && $fileData[1] == 'free' ) ? true : false;
	
	if( file_exists( $filePath ) ) {
		if( ! $isFree  ){
			$LoginSuccessful = false;
			if ( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) ){
				$Username = $_SERVER['PHP_AUTH_USER'];
				$Password = $_SERVER['PHP_AUTH_PW'];
				if ( av_remote_auth_check( array($Username,$Password) ) ){
					$LoginSuccessful = true;
				}
			}
			if ( ! $LoginSuccessful ){
				header('WWW-Authenticate: Basic realm="Enter User name and Password for VIP Download."');
				header('HTTP/1.0 401 Unauthorized');
				print "Login failed!\n";
			}
			else {
				$av_http->set_byfile( $filePath ); 
				$av_http->download( $fileData[0] );
			}
		} else{
			$av_http->set_byfile( $filePath ); 
			$av_http->use_resume = false;
			$av_http->speed = is_numeric($av_config['free_dl_speed']) ? intval($av_config['free_dl_speed']) : 100; 
			$av_http->download( $fileData[0] );
		}
	}
	
	die();