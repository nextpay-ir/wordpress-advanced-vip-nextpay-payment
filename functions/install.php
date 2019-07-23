<?php 
	if( ! defined('ABSPATH') ) die();
	global $wpdb,$avdb;

	$av_default_options = array();
	$av_default_options['enble_free_dl'][] = 'on';
	$av_default_options['free_dl_speed'] = 100;
	$av_default_options['remote_veify_key'] = substr(str_shuffle('wertyuiopasdfghjklzxcvbnm!@#$%^*'),0,10);
	$av_default_options['protected_files_dir'] = 'wp-content/uploads/av-uploaded-files';
	$av_default_options['vip_error_message'] = 'کاربر گرامی اعتبار حساب شما برای مشاهده محتویات کافی نیست.';
	$av_default_options['unlogged_user_message'] = 'کاربر گرامی برای مشاهده محتوا باید وارد سایت شوید.';
	$av_default_options['default_vip_roles'] = array( 'editor' , 'administrator' , 'author' );
	$av_default_options['sms_agancy'] = 'parandsms';
	$av_default_options['sms_on_vip_start'] = 'on';
	$av_default_options['email_on_close_expire'] = 'on';
	$av_default_options['sms_on_vip_start_template'] = 'کاربر گرامی {member-name}
حساب کاربری ویژه شما در سایت {site-title} با موفقیت ایجاد شد.
حساب شما دارای {expire-human-date} اعتبار می باشد.';

	$av_default_options['sms_on_close_expire'] = 'on';
	$av_default_options['sms_on_close_expire_template'] = 'کاربر گرامی {member-name}
حساب ویژه شما در سایت {site-title} در حال به پایان رسیدن است.
حساب شما تنها دارای {expire-hdate} اعتبار است.
لطفا اقدام به شارژ حساب خود کنید.
مدیریت {site-title}';
	
	
	
	$av_default_options['download_page_template'] = '<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="fa-IR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>دانلود</title>
<style>
	html, body, div, span, applet, object, iframe,
	h1, h2, h3, h4, h5, h6, p, blockquote, pre,
	a, abbr, acronym, address, big, cite, code,
	del, dfn, em, font, img, ins, kbd, q, s, samp,
	small, strike, strong, sub, sup, tt, var,
	b, u, i, center,
	dl, dt, dd, ol, ul, li,
	fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td {
		background: transparent;
		border: 0;
		margin: 0;
		padding: 0;
		vertical-align: baseline;
		direction:rtl;
		font-family:tahoma
	}
	html{
		height: 100%;
	}
	body {
		line-height: 1;
		background: #f5f5f5;
		background: -moz-radial-gradient(center, ellipse cover,  #f5f5f5 0%, #f3f3f9 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,#f5f5f5), color-stop(100%,#f3f3f9));
		background: -webkit-radial-gradient(center, ellipse cover,  #f5f5f5 0%,#f3f3f9 100%);
		background: -o-radial-gradient(center, ellipse cover,  #f5f5f5 0%,#f3f3f9 100%);
		background: -ms-radial-gradient(center, ellipse cover,  #f5f5f5 0%,#f3f3f9 100%);
		background: radial-gradient(ellipse at center,  #f5f5f5 0%,#f3f3f9 100%);
	}
	#dl-box{
		width: 500px;
		height: 142px;
		position: absolute;
		top: 50%;
		right: 50%;
		margin-top: -81px;
		margin-right: -250px;
		text-align: center;
	}
	a {
		display: inline-block;
		text-decoration: none;
		padding: 8px 16px;
		margin-right: 20px;
		box-shadow: 0px 1px 0px 0px rgba(0, 0, 0, 0.13);
		background: rgb(247, 247, 247);
		color: rgb(97, 97, 97);
		border: 1px solid rgb(213, 213, 213);
		border-radius: 2px;
		margin-top: 25px;
		margin-bottom: 25px;
		-webkit-transition: all 100ms ease-in-out;
		-moz-transition: all 100ms ease-in-out;
		-ms-transition: all 100ms ease-in-out;
		-o-transition: all 100ms ease-in-out;
		transition: all 100ms ease-in-out;
		position:relative;
		top:0px;
	}
	a:hover{
		background: rgb(238, 238, 238);
		-webkit-transition: all 100ms ease-in-out;
		-moz-transition: all 100ms ease-in-out;
		-ms-transition: all 100ms ease-in-out;
		-o-transition: all 100ms ease-in-out;
		transition: all 100ms ease-in-out;
		position:relative;
		top:-2px;
	}
	a:active{
		box-shadow: inset 0px 1px 0px 0px rgba(0, 0, 0, 0.02);
	}
</style>
</head>
<body id="error-page">
	<div id="dl-box">
<h2>دانلود فایل {file-name}</h2>
		<a href="{vip-link}">دانلود برای اعضای ویژه</a>
		<a href="{free-link}">دانلود رایگان با سرعت محدود</a>
<p>حجم فایل: {file-size}<br/>تعداد دفعات دانلود: {downloads-count}</p>
	</div>
</body>
</html>';
	
	
	$av_default_options['close_expire_mail_subject'] = 'پایان اعتبار وی آی پی';
	
	$av_default_options['close_expire_mail_template'] = '<div align="center" style="text-align:center;">
<div style="width: 500px;margin: 20px auto;background: rgb(250, 250, 250);padding: 10px;font-family: tahoma;direction: rtl;text-align: right;border: 1px solid rgb(209, 209, 209);box-shadow: 0 0 4px 0px rgba(0, 0, 0, 0.09);border-radius: 3px;">
	<div>کاربر گرامی {member-name}</div>
	<div>
		<p>حساب کاربری ویژه شما در سایت <strong>{site-title}</strong> در حال به پایان رسیدن است.</p>
		<p>حساب شما حدود <strong>{start-human-date}</strong> قبل آغاز شده و دارای حدود <strong>{expire-human-date}</strong> اعتبار می باشد.</p>
		<p>لطفا به شارژ حساب خود اقدام کنید.</p>
		<p style="text-align:left;">مدیریت سایت <a href="{site-url}" style="color: rgb(134, 134, 134);text-decoration: none;">{site-title}</a></p>
	</div>
</div>
</div>';

	$av_default_options['vip_register_mail_subject'] = 'شروع حساب کاربری ویژه';
	
	$av_default_options['vip_register_mail_template'] = '<div align="center" style="text-align:center;">
<div style="width: 500px;margin: 20px auto;background: rgb(250, 250, 250);padding: 10px;font-family: tahoma;direction: rtl;text-align: right;border: 1px solid rgb(209, 209, 209);box-shadow: 0 0 4px 0px rgba(0, 0, 0, 0.09);border-radius: 3px;">
	<div>کاربر گرامی {member-name}</div>
	<div>
		<p>حساب کاربری ویژه شما در سایت <strong>{site-title}</strong> با موفقیت ثبت شد.</p>
		<p>تاریخ شارژ: {start-jdate}</p>
		<p>تاریخ پایان حساب: {expire-jdate}</p>
		<p style="text-align:left;">مدیریت سایت <a href="{site-url}" style="color: rgb(134, 134, 134);text-decoration: none;">{site-title}</a></p>
	</div>
</div>
</div>';

	
	
	if( get_option( 'av_EncyptKey' ) === false || !is_string(get_option( 'av_EncyptKey' )) ){
		add_option( 'av_EncyptKey' , substr( str_shuffle('wertyuiopasdfghjklzxcvbnm!@#$%^&*'), 0, 10 ) );
	}
	
	if( get_option( 'av_settings' ) === false || @unserialize( get_option( 'av_settings' ) ) === false ){
		delete_option('av_settings');
		add_option( 'av_settings' , serialize($av_default_options) );
		@mkdir( ABSPATH . 'wp-content/uploads/av-uploaded-files' , 0777, true);
		@file_put_contents( ABSPATH . 'wp-content/uploads/av-uploaded-files' . '/index.html', 'HI!');
		@file_put_contents( ABSPATH . 'wp-content/uploads/av-uploaded-files' . '/.htaccess' , "Order Deny,Allow\nDeny from all");
	}

	wp_schedule_event( time(), 'hourly', 'av_hourly_event');
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$members_table_name = $wpdb->prefix.'av_members';
	
	$members_table = (
		"CREATE TABLE IF NOT EXISTS $members_table_name(
			ID INT(10) NOT NULL auto_increment,
			user_ID INT(10) NOT NULL,
			start_date DATETIME DEFAULT NULL,
			expire_date INT(11) NOT NULL,
			PRIMARY KEY(ID)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;"
	);
	dbDelta( $members_table );
	
	$payments_table_name = $wpdb->prefix.'av_payments';
	
	$payments_table = (
		"CREATE TABLE IF NOT EXISTS ".$payments_table_name."(
			ID INT(10) NOT NULL auto_increment,
			paymenter_ip VARCHAR(50) NOT NULL,
			payment_date INT(11) NOT NULL,
			payment_cost INT(15) NOT NULL,
			refNumber INT(15) NOT NULL,
			payment_agancy VARCHAR(20) DEFAULT NULL,
			PRIMARY KEY(ID)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;"
	);
	dbDelta( $payments_table );
			
	$temporary_table_name = $wpdb->prefix.'av_temporary';
	$temporary_table = (
		"CREATE TABLE IF NOT EXISTS ".$temporary_table_name."(
			ID INT(11) NOT NULL auto_increment,
			payment_price INT(10) NOT NULL,
			user_id INT(15) NOT NULL,
			charge_days INT(15) NOT NULL,
			PRIMARY KEY(ID)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;"
	);
	dbDelta( $temporary_table );
			
	$files_table_name = $wpdb->prefix.'av_files';
	$files_table = (
		"CREATE TABLE IF NOT EXISTS ".$files_table_name."(
			ID INT(11) NOT NULL auto_increment,
			file_name TEXT NOT NULL,
			encypted_name TEXT NOT NULL,
			file_size INT(20) NOT NULL,
			downloads_time INT(8) NOT NULL DEFAULT '1',
			file_type VARCHAR(6) NOT NULL,
			upload_date DATETIME NOT NULL,
			PRIMARY KEY(ID)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;"
	);
	dbDelta( $files_table );
	
	
	if( $wpdb->query( 'SELECT EXISTS(SELECT * FROM `'.$wpdb->prefix.'av_temporary` WHERE user_phone)' ) === false ){
		$wpdb->query( 'ALTER TABLE  `'.$wpdb->prefix.'av_temporary` ADD  `user_phone` VARCHAR( 15 ) NULL DEFAULT NULL ;' );
	}
	
	
	if( $wpdb->query( 'SELECT EXISTS(SELECT * FROM `'.$wpdb->prefix.'av_temporary` WHERE agency)' ) === false ){
		$wpdb->query( 'ALTER TABLE  `'.$wpdb->prefix.'av_temporary` ADD  `agency` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER  `ID` ;' );
	}
	
	if( $wpdb->query( 'SELECT EXISTS(SELECT * FROM `'.$wpdb->prefix.'av_temporary` WHERE payment_id)' ) === false ){
		$wpdb->query( 'ALTER TABLE  `'.$wpdb->prefix.'av_temporary` ADD  `payment_id` VARCHAR( 36 ) NULL DEFAULT NULL AFTER `agency` ;' );
	}
	
	if( $wpdb->query( 'SELECT EXISTS(SELECT * FROM `'.$members_table_name.'` WHERE phone)' ) === false ){
		$wpdb->query( 'ALTER TABLE  `'.$members_table_name.'` ADD  `phone` VARCHAR( 16 ) NULL DEFAULT NULL AFTER `expire_date` ;' );
	}
	
	
	
	
	