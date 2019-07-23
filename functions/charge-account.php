<?php 
	
	if( ! defined('ABSPATH') ) die();
	
	function avChargeAccount( $ID , $days , $_phone ){
		
		global $wpdb,$avdb;
		
		
		
		$current_user_vip_data = advanced_vip::av_current_user_vip_stat();
		
		$_add = advanced_vip::dayToSecounds( $days );
		
		// If there is no vip user from before
		if ( $current_user_vip_data === null ) {
			
			$update_ = $wpdb->insert( $avdb->users, 
				array(
					'user_ID' => get_current_user_id(),
					'start_date' => current_time('mysql'),
					'expire_date' => $_add + time(),
					'phone' => $_phone
				)
			);
			
			$_startDate = advanced_vip::nice_jdate_from_time_stamp( time() );
			$_startUnix = time();
			$_expireDate = advanced_vip::nice_jdate_from_time_stamp( $_add + time() );
			$_expireUnix = $_add + time();
		
		}
		
		// If user has an account from before
		else {
			
			$_expire = $current_user_vip_data['expire_date'];
			
			if( $_expire >= time() ){
			
				$update_ = $wpdb->update(
					$avdb->users,
					array(
						'expire_date' => $_add + $_expire,
						'phone' => $_phone
					),
					array(
						'user_ID' => get_current_user_id()
					)
				);
				
				$_expireDate = advanced_vip::nice_jdate_from_time_stamp( $_add + $_expire );
				$_expireUnix = $_add + $_expire;
			
			} else {
			
			
				$update_ = $wpdb->update(
					$avdb->users, 
					array(
						'expire_date' => $_add + time(),
						'phone' => $_phone
					),
					array(
						'user_ID' => get_current_user_id()
					)
				);
			
				$_expireDate = advanced_vip::nice_jdate_from_time_stamp( $_add + time() );
				$_expireUnix = $_add + time();
			
			
			}
			
			$_startDate = advanced_vip::nice_jdate_from_time_stamp( strtotime($current_user_vip_data['start_date']) );
			$_startUnix = strtotime($current_user_vip_data['start_date']);
		
		}
		
		if( $update_ === false )
			return array( 'status' => false , 'msg' => 'کاربر گرامی پرداخت شما موفقیت آمیز بود اما هنگام شارژ حساب شما خطایی رخ داد. لطفا با مدیریت سایت تماس بگیرید.' );
		else
			return array( 
				'status' => true ,
				'msg' => 'شارژ حساب با موفقیت انجام شد.' ,
				'start' => $_startDate ,
				'expire' => $_expireDate,
				'start_unix' => $_startUnix,
				'expire_unix' => $_expireUnix
			);
			
	}
	
	
	