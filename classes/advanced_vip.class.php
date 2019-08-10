<?php
	if( ! defined('ABSPATH') ) die();
	class advanced_vip{
		
		public function __construct() {
		
			add_action( 'av_hourly_event', array( $this, 'do_av_hourly_event' ) );

			add_action( 'parse_request' , array($this, 'remote_vip_check') );
			add_action( 'admin_init' , array($this, 'vip_files_upload') );
			add_action( 'admin_init' , array($this, 'vip_group_account_charge') );
			add_action( 'admin_init' , array($this, 'vip_group_account_decharge') );
			add_action( 'admin_init' , array($this, 'DeletePaymentItem') );
		
			register_deactivation_hook( av_plugin_file , array($this, 'deactivation') );
			
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
			add_action( 'wp_ajax_av_ajax_settings_save', array($this, 'av_ajax_settings_save') );
			add_action( 'wp_ajax_av_ajax_delete_vip_user', array($this, 'av_ajax_delete_vip_user') );
			add_action( 'wp_ajax_av_ajax_delete_file', array($this, 'av_ajax_delete_file') );
			add_action( 'wp_ajax_av_ajax_add_vip_user', array($this, 'av_ajax_add_vip_user') );
			
			add_action( 'av_before_payment', array($this, 'av_before_payment') , 10 , 2 );
			
			add_action( 'av_after_payment', array($this, 'av_after_payment') , 10 , 2 );
			
			add_action( 'av_complete_charge' , array( $this , 'removeTempRow' ) );
			
			add_action( 'av_complete_charge' , array( $this , 'sendStartVIPsms' ) );
			
			add_action( 'av_complete_charge' , array( $this , 'sendStartVIPemail' ) );
			
			add_filter( 'cron_schedules', array( $this , 'customCronSchedule' ) );
			
		}
		
		
		
		public function do_av_hourly_event() {
			self::doHourlyCloseExpireUsers();
			self::DeleteExpiredUsers();
		}
		
		public function customCronSchedule($schedules){
			$schedules['hourly'] = array(
				'interval' => 60 * 60,
				'display'  => __( 'Once Hour' ),
			);
			return $schedules;
		}
	
		
		public function deactivation(){
			wp_clear_scheduled_hook( 'av_hourly_event' );
		}
		
		public function enqueue_scripts(){

			$url = av_url . 'assets/';
		
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-color' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-effects-slide' );
			wp_enqueue_script( 'av-admin-js' , $url.'js/admin.js' , 'jquery' );
			wp_enqueue_style( 'av-admin-css' , $url.'css/admin.css' );
			
			wp_enqueue_script('av-datapick-cc', $url . 'jalali-date-picker/scripts/jquery.ui.datepicker-cc.js');
			wp_enqueue_script('av-datapick-calendar', $url . 'jalali-date-picker/scripts/calendar.js');
			wp_enqueue_script('av-datapick-cc-ar', $url . 'jalali-date-picker/scripts/jquery.ui.datepicker-cc-ar.js');
			wp_enqueue_script('av-datapick-cc-fa', $url . 'jalali-date-picker/scripts/jquery.ui.datepicker-cc-fa.js');
			wp_enqueue_style('av-datapick-styles', $url . 'jalali-date-picker/styles/jquery-ui-1.8.14.css');
		
			wp_enqueue_script('av-select2-js', $url . 'select2/select2.min.js');
			wp_enqueue_style('av-select2-styles', $url . 'select2/select2.css');
			
			
			wp_enqueue_style('av-codemirror-styles', $url . 'codemirror/codemirror-pack.css');
			wp_enqueue_script('av-codemirror-pack-js', $url . 'codemirror/codemirror-pack.js', array(), false, true);
			
			
		}
	
	
	
		public function removeTempRow( $data ){
			global $wpdb,$avdb;
			return $wpdb->query("DELETE FROM ".$avdb->temp." WHERE ID='".$data[0]['ID']."'");
		}
	
	
		public function sendStartVIPemail( $data ){
			global $wpdb,$avdb,$av_settings,$current_user;
			
			if( isset( $av_settings['email_on_vip_start'] ) ){
			
				$userData = get_userdata( $data[0]['user_id'] );
			
				$site_url = parse_url(site_url());
				$site_url = $site_url['host'];
			
				
				$text = str_replace(
					array(
						'{member-name}',
						'{start-jdate}',
						'{expire-jdate}',
						'{expire-human-date}',
						'{site-title}',
						'{site-url}',
						'{refNumber}',
						'{payment-cost}',
						'{user-email}'
					),
					array(
						$userData->data->display_name,
						$this->nice_jdate_from_time_stamp( time() ),
						$this->nice_jdate_from_time_stamp( $data['time_data']['expire_unix'] ),
						human_time_diff( time() , $data['time_data']['expire_unix'] ),
						get_bloginfo('name'),
						site_url(),
						$data['refNumber'],
						$data[0]['payment_price'],
						$userData->data->user_email
					),
					$av_settings['vip_register_mail_template']
				);
				
			
				$headers  = 'From: no-reply@'.$site_url. "\r\n" .
					'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=utf-8' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
										
				@mail($userData->data->user_email, @$av_settings['vip_register_mail_subject'], $text, $headers);
				
			
			}
			
		}
	
		public function sendStartVIPsms( $data ){
			global $wpdb,$avdb,$av_settings,$current_user;
			
			if( ! is_numeric( $data[0]['user_phone'] ) )
				return;
			if( function_exists('avSendSMS') && isset( $av_settings['sms_on_vip_start'] ) ){
						
				$userData = get_userdata( $data[0]['user_id'] );
				
				$text = str_replace( 
					array(
						'{member-name}',
						'{start-jdate}',
						'{expire-jdate}',
						'{expire-human-date}',
						'{site-title}',
						'{site-url}',
						'{refNumber}',
						'{payment-cost}',
						'{user-email}'
					),
					array(
						$userData->data->display_name,
						$this->nice_jdate_from_time_stamp( time() ),
						$this->nice_jdate_from_time_stamp( $data['time_data']['expire_unix'] ),
						human_time_diff( time() , $data['time_data']['expire_unix'] ),
						get_bloginfo('name'),
						site_url(),
						$data['refNumber'],
						$data[0]['payment_price'],
						$userData->data->user_email
					),
					$av_settings['sms_on_vip_start_template'] 
				);
				
				avSendSMS( $text , $data[0]['user_phone'] );
				
			}
			
		}
		
		
	
		public function av_gr_to_ja($date,$mod=''){
			$g_y = date('Y',strtotime($date));
			$g_m = date('n',strtotime($date));
			$g_d = date('j',strtotime($date));
			$d_4=$g_y%4;
			$g_a=array(0,0,31,59,90,120,151,181,212,243,273,304,334);
			$doy_g=$g_a[(int)$g_m]+$g_d;
			if($d_4==0 and $g_m>2)$doy_g++;
			$d_33=(int)((($g_y-16)%132)*.0305);
			$a=($d_33==3 or $d_33<($d_4-1) or $d_4==0)?286:287;
			$b=(($d_33==1 or $d_33==2) and ($d_33==$d_4 or $d_4==1))?78:(($d_33==3 and $d_4==0)?80:79);
			if((int)(($g_y-10)/63)==30){$a--;$b++;}
			if($doy_g>$b){
				$jy=$g_y-621; $doy_j=$doy_g-$b;
			}else{
				$jy=$g_y-622; $doy_j=$doy_g+$a;
			}
			if($doy_j<187){
				$jm= (int)(($doy_j-1)/31); $jd=$doy_j-(31*$jm++);
			}else{
				$jm=(int)(($doy_j-187)/30); $jd=$doy_j-186-($jm*30); $jm+=7;
			}
			return($mod=='') ? array($jy,$jm,$jd) : $jy.$mod.$jm.$mod.$jd;
		}
		
		public function av_ja_to_gr($date,$mod=''){
			$j_y = $date[0];
			$j_m = $date[1];
			$j_d = $date[2];
			$d_4=($j_y+1)%4;
			$doy_j=($j_m<7)?(($j_m-1)*31)+$j_d:(($j_m-7)*30)+$j_d+186;
			$d_33=(int)((($j_y-55)%132)*.0305);
			$a=($d_33!=3 and $d_4<=$d_33)?287:286;
			$b=(($d_33==1 or $d_33==2) and ($d_33==$d_4 or $d_4==1))?78:(($d_33==3 and $d_4==0)?80:79);
			if((int)(($j_y-19)/63)==20){$a--;$b++;}
			if($doy_j<=$a){
				$gy=$j_y+621; $gd=$doy_j+$b;
			}else{
				$gy=$j_y+622; $gd=$doy_j-$a;
			}
			foreach(array(0,31,($gy%4==0)?29:28,31,30,31,30,31,31,30,31,30,31) as $gm=>$v){
				if($gd<=$v)break;
					$gd-=$v;
			}
			return($mod=='')?array($gy,$gm,$gd):$gy.$mod.$gm.$mod.$gd;
		}		
		
		public function jalali_nice_format($date=null){
			if($date === null) return;
			$year = $date[0];
			$month = $date[1];
			$day = $date[2];
			switch($day){case (1):$day = 'یکم';break;case (2);$day = 'سوم';break;case (3):$day = 'سوم';break;case (4):$day = 'چهارم';break;case (5):$day = 'پنجم';break;case (6):$day = 'ششم';break;case (7):$day = 'هفتم';break;case (8):$day = 'هشتم';break;case (9):$day = 'نهم';break;case (10):$day = 'دهم';break;case (11):$day = 'یازدهم';break;case (12):$day = 'دوازدهم';break;case (13):$day = 'سیزدهم';break;case (14):$day = 'چهاردهم';break;case (15):$day = 'پانزدهم';break;case (16):$day = 'شانزدهم';break;case (17):$day = 'هفدهم';break;case (18):$day = 'هجدهم';break;case (19):$day = 'نوزدهم';break;case (20):$day = 'بیستم';break;case (21):$day = 'بیست یکم';break;case (22):$day = 'بیست دوم';break;case (23):$day = 'بیست سوم';break;case (24):$day = 'بیست چهارم';break;case (25):$day = 'بیست پنجم';break;case (26):$day = 'بیست ششم';break;case (27):$day = 'بیست هفتم';break;case (28):$day = 'بیست هشتم';break;case (29):$day = 'بیست نهم';break;case (30):$day = 'سی ام';break;case (31):$day = 'سی یکم';break;}		
			switch($month){case (1):$month = 'فروردین';break;case (2):$month = 'اردیبهشت';break;case (3):$month = 'خرداد';break;case (4):$month = 'تیر';break;case (5):$month = 'مرداد';break;case (6):$month = 'شهریور';break;case (7):$month = 'مهر';break;case (8):$month = 'آبان';break;case (9):$month = 'آذر';break;case (10):$month = 'دی';break;case (11):$month = 'بهمن';break;case (12):$month = 'اسفند';break;}
			return $day . '، ' . $month . '، ' . $year;
		}
		
		public function nice_jdate_from_time_stamp( $timestamp = 0 ){
			$date = date( "Y-m-d H:i:s", $timestamp );
			return self::jalali_nice_format( self::av_gr_to_ja( $date ) );
		}
		
		public function bytesToSize($bytes, $precision = 1){	
			$kilobyte = 1024;
			$megabyte = $kilobyte * 1024;
			$gigabyte = $megabyte * 1024;
			$terabyte = $gigabyte * 1024;
			
			if (($bytes >= 0) && ($bytes < $kilobyte)) {
				return $bytes . ' B';

			} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
				return round($bytes / $kilobyte, $precision) . ' KB';

			} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
				return round($bytes / $megabyte, $precision) . ' MB';

			} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
				return round($bytes / $gigabyte, $precision) . ' GB';

			} elseif ($bytes >= $terabyte) {
				return round($bytes / $terabyte, $precision) . ' TB';
			} else {
				return $bytes . ' B';
			}
		}
		
		public function secondsToTime($inputSeconds) {

			$secondsInAMinute = 60;
			$secondsInAnHour  = 60 * $secondsInAMinute;
			$secondsInADay    = 24 * $secondsInAnHour;

			// extract days
			$days = floor($inputSeconds / $secondsInADay);

			// extract hours
			$hourSeconds = $inputSeconds % $secondsInADay;
			$hours = floor($hourSeconds / $secondsInAnHour);

			// extract minutes
			$minuteSeconds = $hourSeconds % $secondsInAnHour;
			$minutes = floor($minuteSeconds / $secondsInAMinute);

			// extract the remaining seconds
			$remainingSeconds = $minuteSeconds % $secondsInAMinute;
			$seconds = ceil($remainingSeconds);

			// return the final array
			$obj = array(
				'd' => (int) $days,
				'h' => (int) $hours,
				'm' => (int) $minutes,
				's' => (int) $seconds,
			);
			return $obj;
		}
		
		public function user_authenticate($user=null,$pass=null){
			if( $user === null || $pass === null ) return;
			$data = wp_authenticate($user,$pass);
			if( get_class($data) === 'WP_User' )
				return $data->data->ID;
			return false;	
		}

		public function remote_vip_check($user=null,$pass=null){
			if( isset($_POST['action']) && $_POST['action'] == 'vip_check' && isset($_POST['user']) && isset($_POST['password'])){
				die($this->user_authenticate($_POST['user'],$_POST['password']));
			}	
		}

		
		public function av_ajax_delete_vip_user(){
		
		}

		public function av_ajax_delete_file(){
			global $wpdb,$avdb,$av_vip_dir;
			$output = array();
			if( isset($_POST['file_id']) ){
				$file_name = $wpdb->get_var("SELECT encypted_name FROM ".$avdb->files." WHERE ID='".$_POST['file_id']."'");
				$file_ext = $wpdb->get_var("SELECT file_type FROM ".$avdb->files." WHERE ID='".$_POST['file_id']."'");
				$delete_file = unlink($av_vip_dir.$file_name.'.'.$file_ext);
				$query = $wpdb->query("DELETE FROM ".$avdb->files." WHERE ID='".$_POST['file_id']."'");
				if( $delete_file !== false && $query !== false )
					$output['status'] = 'success';
				else
					$output['status'] = 'error';
			}
			echo json_encode( $output );
			die();
		}
		
		public function av_ajax_add_vip_user(){
			global $avdb,$wpdb;
			$id = $_POST['userID'];
			$credit = $_POST['cre'];
			$credit_type = strpos($credit, '/') !== false ? 'date' : 'day';
			
			if( $credit_type == 'day' ){
				$credit_date = self::dayToSecounds($_POST['cre']);
			}
			if( $credit_type == 'date' ){
				$credit_f = explode('/',$_POST['cre']);
				$credit_f = self::av_ja_to_gr(array($credit_f[2],$credit_f[1],$credit_f[0]));
				$credit_date = $credit_f[0].'-'.$credit_f[1].'-'.$credit_f[2].' 00:00:00';
			}
			
			$output = array();
			if( current_user_can('administrator') && !empty($_POST['userID']) && !empty($_POST['cre']) ){
			
				$custom_user_vip_data = self::av_vip_stat_by_ID($id);
				
				if( $credit_type == 'date' ){
						if( $custom_user_vip_data === null ){
							$update_ = $wpdb->insert(
								$avdb->users,
								array(
									'start_date' => current_time('mysql'),
									'expire_date' => strtotime($credit_date),
									'user_ID' => $id
								)
							);
							$output['t'] = strtotime($credit_date);
						} else{
							$update_ = $wpdb->update(
								$avdb->users,
								array(
									'expire_date' => strtotime($credit_date)
								),
								array(
									'user_ID' => $id
								)
							);
						}
					} else{
						if( $custom_user_vip_data === null ){
							$update_ = $wpdb->insert(
								$avdb->users,
								array(
									'start_date' => current_time('mysql'),
									'expire_date' => time()+$credit_date,
									'user_ID' => $id
								)
							);						
						} else{
							$user_current_expire_date_in_timestamp = $custom_user_vip_data['expire_date'];
							if( $user_current_expire_date_in_timestamp >= time() ){
								$update_ = $wpdb->update(
									$avdb->users,
									array(
										'expire_date' => $user_current_expire_date_in_timestamp+$credit_date
									),
									array(
										'user_ID' => $id
									)
								);
							} else{
								$update_ = $wpdb->update(
									$avdb->users,
									array(
										'expire_date' => time()+$credit_date
									),
									array(
										'user_ID' => $id
									)
								);
							}
						}
					}
				$output['status'] = $update_ === 0 ?   'error': 'success';
			} else{
				$output['status'] = 'error';
			}
			
			echo json_encode( $output );
		
			die();
		}
		
		public function av_ajax_settings_save(){
		
			if( current_user_can('manage_options') && is_admin() ){
				$params = array();
				parse_str($_POST['data'], $params);
				$params['vip_categories'] = $_POST['vip_cats'];
				$params['default_vip_roles'] = $_POST['vip_roles'];
				if( get_option('av_settings' === false ) ){
					$add_option = add_option('av_settings',serialize($params));
					echo $add_option === false ? 'error' : 'ok';
				} else{
					delete_option('av_settings');
					$update_option = update_option('av_settings',serialize($params));
					echo $update_option === false ? 'error' : 'ok';
				}
			} else {
				echo 'error';
			}
			@mkdir( ABSPATH . $params['protected_files_dir'], 0777, true);
			@file_put_contents(ABSPATH.$params['protected_files_dir'].'/index.html', 'HI!');
			@file_put_contents(ABSPATH.$params['protected_files_dir'].'/.htaccess', "Order Deny,Allow\nDeny from all");
			
			die();
		}


		public function av_current_user_vip_stat(){
			global $wpdb,$avdb;
			$id = get_current_user_id();
			if( $id === 0 ) return false;
			$query = $wpdb->get_row("SELECT * FROM `".$avdb->users."` WHERE user_ID='".$id."'",ARRAY_A);
			return $query;
		}

		public function av_vip_stat_by_ID($id){
			global $wpdb,$avdb;
			if( $id === 0 ) return false;
			$query = $wpdb->get_row("SELECT * FROM `".$avdb->users."` WHERE user_ID='".$id."'",ARRAY_A);
			return $query;
		}

		public function vip_start_by_userID( $id = 0 ){
			global $wpdb,$avdb;
			return $wpdb->get_var("SELECT start_date FROM `".$avdb->users."` WHERE user_ID='".$id."'");
		}

		public function vip_expire_by_userID( $id = 0 ){
			global $wpdb,$avdb;
			return $wpdb->get_var("SELECT expire_date FROM `".$avdb->users."` WHERE user_ID='".$id."'");
		}



	
		
		
		
		public function vip_files_upload(){
			global $av_vip_dir,$avEcrypt,$wpdb,$avdb;
			if( isset($_POST['av_files_upload']) && 'true' == $_POST['av_files_upload'] ){
				foreach( $_FILES['vip_file']['tmp_name'] as $key => $tmp_name ){
					$name[] = $_FILES['vip_file']['name'][$key];
					$tmp_name1[] = $_FILES['vip_file']['tmp_name'][$key];
					$size[] = $_FILES['vip_file']['size'][$key];
					$type[] = $_FILES['vip_file']['type'][$key];
					$error[] = $_FILES['vip_file']['error'][$key];
				}
				foreach( $tmp_name1 as $of => $val ){
					if ( $error[$of] <= 0 ){
						$fileinfo = pathinfo($name[$of]);
						move_uploaded_file( $val , $av_vip_dir.$avEcrypt->en($fileinfo['filename']).'.'.$fileinfo['extension'] );
						$wpdb->insert( $avdb->files, array(
							'file_name' => $fileinfo['filename'],
							'encypted_name' => $avEcrypt->en($fileinfo['filename']),
							'file_size' => $size[$of],
							'file_type' => $size[$of],
							'file_type' => $fileinfo['extension'],
							'upload_date' => current_time('mysql')
						));
					}	
				}
				foreach( $error as $key => $item ){
					switch (intval($item)){
						case (0):
							$output[] = 'فایل ' . $name[$key] . ' با موفقیت آپلود شد.';
						break;
						case (1):
							$output[] = 'حجم فایل ' . $name[$key] . ' بیشتر از مقدار تعریف شده در php.ini است.';
						break;
						case (2):
							$output[] = 'حجم فایل ' . $name[$key] . ' مجاز نیست.';
						break;
						case (3):
							$output[] = 'فایل ' . $name[$key] . ' به صورت کامل آپلود نشد.';
						break;
						case (4):
							$output[] = 'آپلود فایل ' . $name[$key] . ' موفقیت آمیز نبود.';
						break;
						case (6):
							$output[] = 'به خاطر خطا در مسیر موقت آپلود فایل ' . $name[$key] . ' آپلود نشد.';
						break;
						case (7):
							$output[] = 'فایل ' . $name[$key] . ' قادر به آپلود شدن نبود.';
						break;
						case (8):
							$output[] = 'فایل ' . $name[$key] . ' به دلیلی ناشناخته آپلود نشد.';
						break;
						default:
							$output[] = 'سیستم قادر به تعیین وضعیت آپلود فایل نیست.';
						break;
					}
				}
				header( 'Location: '.admin_url().'admin.php?page=av_files&upload_status='.$avEcrypt->en(serialize($output)) );
				exit;
			}		
		}
			
		public static function nextpay_payment_verify($post) {
			
			global $av_settings;
			
			ini_set("soap.wsdl_cache_enabled", 0);	
			header('Content-Type: text/html; charset=utf-8');
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
			header('Pragma: no-cache');
	
			$nextpay_apikey = $av_settings['nextpay_apikey'];
	
			$order_id = isset($post['order_id']) ? $post['order_id'] : '';
			$Transaction_ID = $trans_id = isset($post['trans_id']) ? $post['trans_id'] : '';
			$amount = isset($post['amount']) ? $post['amount'] : '';
	
			if ( extension_loaded('soap') ) {
				if( $trans_id && $order_id ) {
					try {
				
						$client = new SoapClient('https://api.nextpay.org/gateway/verify.wsdl', array('encoding' => 'UTF-8') );
						$Request = $client->PaymentVerification( array(
							'apikey'     => $nextpay_apikey,
							'trans_id'   => $Token,
							'order_id'   => $order_id,
							'amount'     => $amount
							)
						);
						
						$Request = $Request->PaymentVerificationResult;
				
						if( isset($Request->code) && $Request->code == 0 ){
							$Status = 'completed';
							$Fault = '';
						}
						else {
							$Status  = 'failed';
							$Fault   = isset($Request->code) ? $Request->code : '';
						}
					}
					catch(Exception $ex){
						$Message = $ex->getMessage();
					}
				}
				else {
					$Status = 'failed';
					$Fault = '-21';
				}
			}
			else {
				$Status = 'failed';
				$Fault = '-100';
			}	
	
	
			if ( $Status == 'completed' ) {
				$response = 'کد تراکنش : ' . $Transaction_ID;
				$response .= 'شماره فاکتور : ' . $order_id;
				return array( 'status' => true , 'msg' => $response );
			}
			else {
				$response = 'پرداخت ناموفق بود . علت خطا : ' . self::Nextpay_Request_Results( $Fault );;
				$response .= '<br/>کد تراکنش : ' . $Transaction_ID;
				$response .= '<br/>شماره فاکتور : ' . $InvoiceNumber;
				return array( 'status' => false , 'msg' => $response );
			}
		}
		
		public function dayToSecounds($day=0){
			return $day * 60 * 60 * 24;
		}
		
		public function ToSecounds($type,$val){
			switch($type){
				case('min'):
					return intval($val) * 60;
				break;
				case('hour'):
					return intval($val) * 60 * 60;
				break;
				case('day'):
					return intval($val) * 60 * 60 * 24;
				break;
				case('week'):
					return intval($val) * 60 * 60 * 24 * 7;
				break;
				case('mon'):
					return intval($val) * 60 * 60 * 24 * 30;
				break;
				case('year'):
					return intval($val) * 60 * 60 * 24 * 30 * 12;
				break;
			}
		}
		
		public function vip_group_account_charge(){
			global $wpdb,$avdb;
			if( isset($_POST['vip_charge_unit']) && isset($_POST['vip_charge_value']) ){
				if( current_user_can('administrator') ) {
					$in_secount = self::ToSecounds($_POST['vip_charge_unit'],$_POST['vip_charge_value']);
					$add = $wpdb->query("UPDATE ".$avdb->users." SET expire_date = expire_date + ".$in_secount);
					if( $add !== false )
						wp_redirect(admin_url('admin.php?page=av_group_increasing&message=success&added='.$in_secount));
					else
						wp_redirect(admin_url('admin.php?page=av_group_increasing&message=error'));
					exit;
				} else{
					wp_redirect(admin_url('admin.php?page=av_group_increasing&message=error'));
					exit;
				}
			}
		}
		
		public function vip_group_account_decharge(){
			global $wpdb,$avdb;
			if( isset($_POST['vip_charge_unit']) && isset($_POST['vip_decharge_value']) ){
				if( current_user_can('administrator') ) {
					$in_secount = self::ToSecounds($_POST['vip_charge_unit'],$_POST['vip_decharge_value']);
					$add = $wpdb->query("UPDATE ".$avdb->users." SET expire_date = expire_date - ".$in_secount." WHERE `expire_date` < NOW()");
					if( $add !== false )
						wp_redirect(admin_url('admin.php?page=av_group_lessen&message=success&added='.$in_secount));
					else
						wp_redirect(admin_url('admin.php?page=av_group_lessen&message=error'));
					exit;
				} else{
					wp_redirect(admin_url('admin.php?page=av_group_lessen&message=error'));
					exit;
				}
			} 
		}
		
		public function getRealIp(){
			$ip = '';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}
		
		
		public function DeletePaymentItem(){
			global $wpdb,$avdb;
			if( isset( $_GET['action'] ) && $_GET['action'] == 'delete_payment_item' && isset( $_GET['id'] ) ){
				$query = $wpdb->query("DELETE FROM ".$avdb->payments." WHERE ID='".$_GET['id']."'");
				if( $query !== false )
					wp_redirect(admin_url('admin.php?page=av_payments&av_message=5'));
				else
					wp_redirect(admin_url('admin.php?page=av_payments&av_message=6&av_error_message'));
				exit;	
			}
		}
		
		public function user_email_by_id( $id ){
			global $wpdb;
			return $wpdb->get_var( "SELECT `user_email` FROM `".$wpdb->users."` WHERE ID = '".intval($id)."'" );
		}
		
		public function doHourlyCloseExpireUsers(){
			global $wpdb, $avdb, $av_settings;
			$seconds = self::dayToSecounds(2);
			$times = time() + $seconds;
			$TIME = time();
			$users = $wpdb->get_results( "SELECT user_ID FROM ".$avdb->users." WHERE expire_date <" . $times . " AND ".$TIME." < expire_date", ARRAY_A );
			$CloseUsers = array();
			foreach( $users as $key => $val ){
				$CloseUsers[ $val['user_ID'] ] = self::user_email_by_id( $val['user_ID'] );
			}
			foreach( $CloseUsers as $ID => $mail ){
				$user_data = get_userdata( $ID );
				$vipData = self::av_vip_stat_by_ID($ID);
				if( get_transient( "avHourlyEvenetFor_" . $ID ) === false ){
					do_action(
						'avHourlyEvenetCloseExpireUser' , 
						array( 
							"member-name" => $user_data->display_name,
							"member-mail" => $mail,
							"site-title" => get_bloginfo('name'),
							"site-url" => site_url(),
							"expire-jdate" => self::nice_jdate_from_time_stamp( self::vip_expire_by_userID($ID) ),
							"expire-hdate" => human_time_diff( self::vip_expire_by_userID($ID) , time() ),
							"start-jdate" => self::nice_jdate_from_time_stamp( strtotime(self::vip_start_by_userID($ID)) ),
							"start-hdate" => human_time_diff( strtotime(self::vip_start_by_userID($ID)) , time() ),
							"mail" => $mail,
							"phone" => $vipData['phone']
						)
					);
					//file_put_contents( 'ss.txt' , 'hi!' );
					set_transient( "avHourlyEvenetFor_" . $ID , time() , 60 * 60 * 24 );
				}
			}
		}
		
		public function DeleteExpiredUsers(){
			global $wpdb, $avdb;
			$now = time();
			$wpdb->query( "DELETE FROM ".$avdb->users." WHERE expire_date < " . $now );
		}
		
		public function av_after_payment( $ag , $post ){
			global $wpdb,$av_settings,$avdb;
		
			if( $ag == 'nextpay' ) {
			
				if ( !empty($post)  ) {
						
					$data = array( $post['card_holder'] , $post['trans_id'] , $post['order_id'] );
					
					$paymentData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $avdb->temp WHERE payment_id = %s" , $data[2] ) , ARRAY_A );
					
					$nextpay_verify = self::nextpay_payment_verify( $post );
					
					if( $nextpay_verify['status'] ){
						
						
						$charge = avChargeAccount( get_current_user_id() , $paymentData['charge_days'] , $paymentData['user_phone'] );
						
						do_action( 'av_complete_charge' , array( $paymentData , $charge , 'time_data' => $charge, 'refNumber' => $data[1] ) );
						
						avInsertPayment( array( 'price' => $paymentData['payment_price'] , 'ref' => $data[1], 'ag' => 'نکست پی' ) );
						
						$html = '';
						$html .= $charge['status'] === true ? 'پرداخت موفقیت آمیز بود و اکانت شما شارژ شد.' : 'پرداخت موفقیت آمیز بود، اما به نظر می رسد حساب کاربری شما شارژ نشده است. لطفا با مدیریت سایت تماس بگیرید.';
						$html .= '<br/>شماره پیگیری پرداخت شما: <strong>'.$data[1].'</strong> می باشد.';
						$html .= '<br/> <a href="'.site_url().'">بازگشت به سایت</a>';
						
						wp_die( $html , 'پرداخت موفقیت آمیز' );
						
					
				
					} else {
				
						wp_die( $nextpay_verify['msg'] );
				
					}
				
				
				} else {
					
					wp_die( 'اطلاعات دریافت شده از فراگیت نا مشخص هستند.'  );
					
				}

			
			}
		
		}
		
		
		public function av_before_payment( $ag , $data ){
			global $wpdb,$av_settings;
			
			$user_id = get_current_user_id();
			$current_user = wp_get_current_user();
			
			if( $ag == 'nextpay' ) {
				
				if( ! empty( $av_settings['nextpay_apikey'] ) ) {
					
					$insert = $wpdb->insert(
						$wpdb->prefix.'av_temporary',
						array(
							'payment_price' => $data[0],
							'user_id' => $user_id,
							'charge_days' => $data[1],
							'payment_id' => str_replace( '.' , '' , microtime(true) ),
							'agency' => 'nextpay',
							'user_phone' => $data[2]
						)
					);
				
					if( $insert === false ) {
					
						wp_die('خطا در ثبت سفارش');
						
						
					} else {
					
						$inserted = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."av_temporary"."` WHERE ID='".$wpdb->insert_id."'",ARRAY_A);
						
										
						ini_set("soap.wsdl_cache_enabled", 0);	
						header('Content-Type: text/html; charset=utf-8');
						header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
						header('Pragma: no-cache');
	
						$SandBox 			 = false;
						$nextpay_apikey      = $av_settings['nextpay_apikey'];
						$PriceValue 		 = intval($inserted['payment_price']);
						$ReturnUrl 			 = site_url().'/?av_after_payment=true&agency=nextpay';
						$InvoiceNumber 		 = $inserted['payment_id'];
						$custom              = json_encode(array('amount'=>$PriceValue));
	
						
						$PaymenterEmail = !filter_var($PaymenterEmail, FILTER_VALIDATE_EMAIL) === false ? $PaymenterEmail : '';
						$PaymenterMobile = preg_match('/^09[0-9]{9}/i', $PaymenterMobile) ? $PaymenterMobile : '';
			
						$Parameters = array(
							'api_key'  	  => $nextpay_apikey,
							'amount'   		  => $PriceValue,
							'order_id'    		  => $InvoiceNumber,
							'callback_uri'		  => $ReturnUrl,
							'custom'   	  => $custom
						);
			
						if ( extension_loaded('soap') ) {
							try {
				
								$client  = new SoapClient('https://api.nextpay.org/gateway/token.wsdl', array('encoding' => 'UTF-8') );	
								$Request = $client->TokenGenerator( $Parameters );
								$Request = $Request->TokenGeneratorResult;
			
								if ( isset($Request->code) && $Request->code == -1 ){
						
									$trans_id = isset($Request->trans_id) ? $Request->trans_id : '';
									$Payment_URL = "https://api.nextpay.org/gateway/payment/$trans_id";
				
									if ( ! headers_sent() ) { 
										header('Location: ' . $Payment_URL ); 
										exit;
									}
									
									$html = '<script language="javascript" type="text/javascript">window.onload = document.body.onload = function(){window.location="' .$Payment_URL. '";}</script>';
									wp_die($html,'اتصال به درگاه');
									
								}
								else {
									$Fault  = isset($Request->code) ? $Request->code : '';
								}
							}
							catch(Exception $ex){
								$Message = $ex->getMessage();
							}
						}
						else {
							$Fault = '-100';
						}
						
						if ( !empty($Fault) && $Fault ) {
							wp_die( 'خطایی رخ داده است . علت خطا : ' . self::Nextpay_Request_Results( $Fault ) );
						}
	
					}
			
				} else {
					
					wp_die( 'اطلاعات درگاه فراگیت صحیح نیست. لطفا به تنظیمات افزونه مراجعه کنید.' );
				
				}
			
			
			}
		
		}
		
		public static function Nextpay_Request_Results( $Fault, $lan=1 ) {
            $lan = interval($lan);
            $lan = ($lan > 1 || $lan < 0) ? 1 : $lan;
            $error_des = array("error code","شماره خطا");
            $error_code = intval($Fault);
            $error_array = array(
            0 => ["Complete Transaction", 'پرداخت تکمیل و با موفقیت انجام شده است'],
            -1 => ["Default State", 'منتظر ارسال تراکنش و ادامه پرداخت'],
            -2 => ["Bank Failed or Canceled", 'پرداخت رد شده توسط کاربر یا بانک'],
            -3 => ["Bank Payment Pending", 'پرداخت در حال انتظار جواب بانک'],
            -4 => ["Bank Canceled", 'پرداخت لغو شده است'],
            -20 => ["api key is not send", 'کد api_key ارسال نشده است'],
            -21 => ["empty trans_id param send", 'کد trans_id ارسال نشده است'],
            -22 => ["amount not send", 'مبلغ ارسال نشده'],
            -23 => ["callback not send", 'لینک ارسال نشده'],
            -24 => ["amount incorrect", 'مبلغ صحیح نیست'],
            -25 => ["trans_id resend and not allow to payment", 'تراکنش قبلا انجام و قابل ارسال نیست'],
            -26 => ["Token not send", 'مقدار توکن ارسال نشده است'],
            -27 => ["order_id incorrect", 'شماره سفارش صحیح نیست'],
            -28 => ["custom field incorrect [must be json]", 'مقدار فیلد سفارشی [custom] از نوع json نیست'],
            -29 => ["refund key incorrect", 'کد بازگشت مبلغ صحیح نیست'],
            -30 => ["amount less of limit payment", 'مبلغ کمتر از حداقل پرداختی است'],
            -31 => ["fund not found", 'صندوق کاربری موجود نیست'],
            -32 => ["callback error [incorrect]", 'مسیر بازگشت صحیح نیست'],
            -33 => ["api_key incorrect", 'کلید مجوز دهی صحیح نیست'],
            -34 => ["trans_id incorrect", 'کد تراکنش صحیح نیست'],
            -35 => ["type of api_key incorrect", 'ساختار کلید مجوز دهی صحیح نیست'],
            -36 => ["order_id not send", 'شماره سفارش ارسال نشد است'],
            -37 => ["transaction not found", 'شماره تراکنش یافت نشد'],
            -38 => ["token not found", 'توکن ارسالی موجود نیست'],
            -39 => ["api_key not found", 'کلید مجوز دهی موجود نیست'],
            -40 => ["api_key is blocked", 'کلید مجوزدهی مسدود شده است'],
            -41 => ["params from bank invalid", 'خطا در دریافت پارامتر، شماره شناسایی صحت اعتبار که از بانک ارسال شده موجود نیست'],
            -42 => ["payment system problem", 'سیستم پرداخت دچار مشکل شده است'],
            -43 => ["payment gateway not found", 'درگاه پرداختی برای انجام درخواست یافت نشد'],
            -44 => ["response bank invalid", 'پاسخ دریاف شده از بانک نامعتبر است'],
            -45 => ["payment system deactivated", 'سیستم پرداخت غیر فعال است'],
            -46 => ["request incorrect", 'درخواست نامعتبر'],
            -47 => ["api has been deleted", 'کلید مجوز دهی یافت نشد [حذف شده]'],
            -48 => ["commission rate not detect", 'نرخ کمیسیون تعیین نشده است'],
            -49 => ["transaction repeated", 'تراکنش مورد نظر تکراریست'],
            -50 => ["account not found", 'حساب کاربری برای صندوق مالی یافت نشد'],
            -51 => ["user not found", 'شناسه کاربری یافت نشد'],
            -52 => ["user not verify", 'حساب کاربری تایید نشده است'],
            -60 => ["email incorrect", 'ایمیل صحیح نیست'],
            -61 => ["national code incorrect", 'کد ملی صحیح نیست'],
            -62 => ["postal code incorrect", 'کد پستی صحیح نیست'],
            -63 => ["postal address incorrect", 'آدرس پستی صحیح نیست و یا بیش از ۱۵۰ کارکتر است'],
            -64 => ["desc incorrect", 'توضیحات صحیح نیست و یا بیش از ۱۵۰ کارکتر است'],
            -65 => ["name and family incorrect", 'نام و نام خانوادگی صحیح نیست و یا بیش از ۳۵ کاکتر است'],
            -66 => ["tel incorrect", 'تلفن صحیح نیست'],
            -67 => ["account name incorrect", 'نام کاربری صحیح نیست یا بیش از ۳۰ کارکتر است'],
            -68 => ["product name incorrect", 'نام محصول صحیح نیست و یا بیش از ۳۰ کارکتر است'],
            -69 => ["callback success incorrect", 'آدرس ارسالی برای بازگشت موفق صحیح نیست و یا بیش از ۱۰۰ کارکتر است'],
            -70 => ["callback failed incorrect", 'آدرس ارسالی برای بازگشت ناموفق صحیح نیست و یا بیش از ۱۰۰ کارکتر است'],
            -71 => ["phone incorrect", 'موبایل صحیح نیست'],
            -72 => ["bank not response", 'بانک پاسخگو نبوده است لطفا با نکست پی تماس بگیرید'],
            -73 => ["callback_uri incorrect [with api's address website]", 'مسیر بازگشت دارای خطا میباشد یا بسیار طولانیست'],
            -80 => ["Comming Soon [None]","تنظیم نشده"],
            -81 => ["Comming Soon [None]","تنظیم نشده"],
            -82 => ["ppm incorrect token code", 'احراز هویت موبایل برای پرداخت شخصی صحیح نمیباشد.'],
            -83 => ["Comming Soon [None]","تنظیم نشده"],
            -90 => ["refund success", 'بازگشت مبلغ بدرستی انجام شد'],
            -91 => ["refund failed", 'عملیات ناموفق در بازگشت مبلغ'],
            -92 => ["refund stoped by error", 'در عملیات بازگشت مبلغ خطا رخ داده است'],
            -93 => ["amount be less in fund for refund", 'موجودی صندوق کاربری برای بازگشت مبلغ کافی نیست'],
            -94 => ["refund's key not found", 'کلید بازگشت مبلغ یافت نشد'],
            -100 => ["soap service not installed", 'سیستم سرویس soap نصب یا فعال نیست']
            );
            
            if (array_key_exists($error_code, $error_array)) {
                return $error_array[$error_code][$lan];
            } else {
                return "{$error_des[$lan]} : $error_code";
            }
            
		}
}
