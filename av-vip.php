<?php
	/*
		Plugin Name: اکانت وی آی پی ویژه
		Plugin URI: http://Vahidd.com
		Description: افزونه برای ساخت اشتراک های ماهانه و اکانت پریمیوم همراه با درگاه نکست پی
		Version: 1.4.2
		Author: وحید محمدی
		Author URI: http://Vahidd.com
		Text Domain: av
		Domain Path: /langs/
	*/
	
	if( ! defined('ABSPATH') ) die();
	
	define( 'av_dir' , dirname(__FILE__) . '/' );
	define( 'av_url' , plugin_dir_url(__FILE__) );
	define( 'av_version' , '1.4.2' );
	define( 'av_plugin_file' , __FILE__ );
	define( 'av_classes_dir' , av_dir.'classes/' );
	define( 'av_assets_url' , av_url.'assets/' );
	define( 'av_func_dir' , av_dir.'functions/' );
	
	
	global $wpdb;
	
	add_action('init', 'avStartSession', 1);
	add_action('wp_logout', 'avEndSession');
	add_action('wp_login', 'avEndSession');
	function avStartSession() {
		if(!session_id()) {
			session_start();
		}
	}
	function avEndSession() {
	   session_destroy();
	}
	
	require_once av_classes_dir . 'db.class.php';
	require_once av_classes_dir . 'http.class.php';
	require_once av_classes_dir . 'advanced_vip.class.php';
	require_once av_classes_dir . 'beforePayment.class.php';
	
	if( ! function_exists('get_userdata') )
		require_once ABSPATH . 'wp-includes/pluggable.php';
		
	require_once av_classes_dir . 'ecrypt.class.php';
	require_once av_dir . 'front-end/dashboard.php';
	require_once av_dir . 'front-end/vip-files.php';
	require_once av_dir . 'front-end/add-vip-member.php';
	require_once av_dir . 'front-end/av-group-increasing.php';
	require_once av_dir . 'front-end/av-group-lessen.php';
	require_once av_dir . 'front-end/payments-list.php';
	require_once av_dir . 'front-end/coupons.php';
	require_once av_dir . 'front-end/new-coupon.php';
	require_once av_dir . 'front-end/settings.php';
	require_once av_dir . 'front-end/help.php';
	require_once av_func_dir . 'custom-functions.php';
	require_once av_func_dir . 'sms.php';
	require_once av_func_dir . 'charge-account.php';
	require_once av_func_dir . 'insert-payment-to-db.php';
	require_once av_func_dir . 'shortcodes.php';
	require_once av_func_dir . 'bulk-delte-vip-users.php';
	require_once av_func_dir . 'av-admin-messages.php';
	require_once av_func_dir . 'account-charge-before-payment.php';
	require_once av_func_dir . 'account-charge-after-payment.php';
	require_once av_func_dir . 'metaboxes.php';
	require_once av_func_dir . 'free-dl-handle.php';
	require_once av_func_dir . 'vip-dl-handle.php';
	require_once av_func_dir . 'vip-filters.php';
	require_once av_func_dir . 'download-page.php';
	require_once av_func_dir . 'send-email-to-expire-user.php';
	require_once av_func_dir . 'send-sms-to-expire-user.php';
	require_once av_dir . 'front-end/vip-members.php';
	
	add_action( 'av_remote_user_auth', 'av_remote_user_auth_function');
	function av_remote_user_auth_function( $data ){
		$check = av_user_vip_check_by_username_password( $data[0] , $data[1] );
		if( $check )
			die('true');
		else
			die('false');
	}
	
	if( 
		! empty( $_POST['action'] ) &&
		$_POST['action'] == 'av_user_auth' &&
		! empty( $_POST['user_name'] ) &&
		! empty( $_POST['user_password'] ) &&
		! empty( $_POST['confirm_key'] ) &&
		$_POST['confirm_key'] == $av_settings['remote_veify_key']
	) {
		do_action( 'av_remote_user_auth' , array( $_POST['user_name'] , $_POST['user_password'] ) );
	}
	
	
	require_once 'update/plugin-update-checker.php';
	$avUpdateChecker = new PluginUpdateChecker(
		'http://workshop.vahidd.com/advanced-vip/metadata.json',
		__FILE__,
		'advanced-vip'
	);
