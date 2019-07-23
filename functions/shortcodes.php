<?php
	if( ! defined('ABSPATH') ) die;
	
	add_shortcode( 'vip-file' , 'vip_file_shortcode_func' );
	
	function vip_file_shortcode_func( $atts = null ){
		global $av_settings,$advanced_vip;
		extract( 
			shortcode_atts( 
				array(
					'id' => 0
					),
				$atts 
			)
		);
		return site_url() . '/?action=av_download&id='.$id;
	}
	
	
	add_shortcode( 'vip-payment' , 'vip_payment_shortcode_func' );
	function vip_payment_shortcode_func( $atts = null ){
		global $av_settings,$advanced_vip;
		extract( 
			shortcode_atts( 
				array(
					'login_url' => wp_login_url()
					),
				$atts 
			)
		);
		if( is_user_logged_in() ){
			$user_current_vip_info = $advanced_vip->av_current_user_vip_stat();
			if( isset($av_settings['vip_time_id']) ){
				$expire_timestamp = $user_current_vip_info['expire_date'];
				$left_in_timestamp = $expire_timestamp - time();
				$human_date_left = human_time_diff( time(), $left_in_timestamp+time());
				$jalali_left = $advanced_vip->av_gr_to_ja( date("Y-m-d H:i:s",$user_current_vip_info['expire_date']) );
				$html = '';
				$html .= '<form method="post" action="'.site_url().'/?action=vip_charging">';
				$html .= '<label>دوره بازاریابی خود را انتخاب کنید <select name="accout_type">';
				$i = 0;
				foreach( $av_settings['vip_time_id'] as $item) {
					$html .= '<option value="'.$item.'">'.$av_settings['vip_time_name'][$i].' '.$av_settings['vip_time_price'][$i].' تومان</option>';
					$i++;
				}
				$html .= '</select></label><br/><br/>';
				$html .= '<label>شماره تلفن همراه <input type="text" name="phone_num" placeholder="اختیاری"/></label><br/><br/>';
				$html .= '<input type="submit" value="ثبت و پرداخت هزینه دوره تجاری"/>';
				$html .= '</form>';
				return $html;
			}
		} else {
			return '<a href="'.$login_url.'">ابتدا باید وارد حساب کاربری خود شوید.</a>';
		}
	}

		
	add_shortcode( 'vip-members' , 'av_vip_member_func' );
	
	function av_vip_member_func( $atts = null, $content ){
		global $av_current_user_vip, $av_settings;
		
		extract( 
			shortcode_atts( 
				array(
					'login_message' => $av_settings['unlogged_user_message'],
					'vip_error_message' => $av_settings['vip_error_message']
					),
				$atts 
			)
		);
		
		if (is_feed()) return "";
		
		
		if( is_user_logged_in() ){
		
			if( $av_current_user_vip ){
			
				return $content;
				
			} else{
				return $vip_error_message;
			}
		
		} else{
		
			return $login_message;
			
		}
		
	}	
	
	
	
	
	add_shortcode( 'current-user-vip-start-human-date' , 'av_current_user_start_human_date_shortcode' );	
	function av_current_user_start_human_date_shortcode( $content = null , $atts = null ){
		$data = av_get_user_vip_data_by_id( get_current_user_id() );
		return human_time_diff( time() , strtotime($data['start_date']) );
	}
	
	
	
	
	add_shortcode( 'current-user-vip-start-jalali-date' , 'av_current_user_start_jalali_date_shortcode' );	
	function av_current_user_start_jalali_date_shortcode( $content = null , $atts = null ){
		$data = av_get_user_vip_data_by_id( get_current_user_id() );
		return advanced_vip::nice_jdate_from_time_stamp( strtotime($data['start_date']) );
	}
	
	
	
	add_shortcode( 'current-user-vip-expire-human-date' , 'av_current_user_expire_human_date_shortcode' );	
	function av_current_user_expire_human_date_shortcode( $content = null , $atts = null ){
		$data = av_get_user_vip_data_by_id( get_current_user_id() );
		return human_time_diff(  time() , intval($data['expire_date']) );
	}
	
	add_shortcode( 'current-user-vip-expire-jalali-date' , 'av_current_user_expire_jalali_date_shortcode' );	
	function av_current_user_expire_jalali_date_shortcode( $content = null , $atts = null ){
		$data = av_get_user_vip_data_by_id( get_current_user_id() );
		return advanced_vip::nice_jdate_from_time_stamp( $data['expire_date'] );
	}
	
	add_shortcode( 'all-vip-members-count' , 'av_all_vip_members_count_shortcode' );	
	function av_all_vip_members_count_shortcode( $content = null , $atts = null ){
		global $wpdb, $avdb;
		return $wpdb->get_var( "SELECT COUNT(*) FROM " . $avdb->users );
	}
	
	add_shortcode( 'most-vip-credit-member' , 'av_most_credit_vip_member_shortcode' );	
	function av_most_credit_vip_member_shortcode( $atts ){
		global $wpdb, $avdb;
		extract( 
			shortcode_atts( 
				array(
					'field' => 'member-name'
				),
				$atts 
			)
		);
		
		$query = $wpdb->get_row( "SELECT * FROM " . $avdb->users . " ORDER BY `expire_date` DESC LIMIT 0 , 1" , ARRAY_A );
		
		$user_data = get_userdata( $query['user_ID'] );
		$vip_data = av_get_user_vip_data_by_id( $query['user_ID'] );
		$start_hdata = human_time_diff( time() , strtotime( $vip_data['start_date'] ) );
		$start_jdata = advanced_vip::nice_jdate_from_time_stamp( strtotime( $vip_data['start_date'] ) );
		$expire_hdata = human_time_diff(  time() , intval( $vip_data['expire_date'] ) );
		$expire_jdata = advanced_vip::nice_jdate_from_time_stamp( $vip_data['expire_date'] );
		
		switch( $field ){
		
			case('member-name'):
				$output = $user_data->data->display_name;
			break;
			
			case('member-id'):
				$output = $user_data->data->ID;
			break;
			
			case('member-nicename'):
				$output = $user_data->data->user_nicename;
			break;
			
			case('member-email'):
				$output = $user_data->data->user_email;
			break;
			
			case('member-url'):
				$output = $user_data->data->user_url;
			break;
			
			case('start-hdate'):
				$output = $start_hdata;
			break;
			
			case('start-jdate'):
				$output = $start_jdata;
			break;
			
			case('expire-hdate'):
				$output = $expire_hdata;
			break;
			
			case('expire-jdate'):
				$output = $expire_jdata;
			break;
			
		}
		
		return $output;
		
	}
	
	
	add_filter('mce_external_plugins', "avVIPMembersShortcode_register");
add_filter('mce_buttons', 'avVIPMembersShortcode_add_button', 0);

function avVIPMembersShortcode_add_button($buttons)
{
    array_push($buttons, "separator", "avVIPMembersShortcode");
    return $buttons;
}

function avVIPMembersShortcode_register($plugin_array)
{
    $plugin_array['avVIPMembersShortcode'] = av_url . "tinymce/editor_plugin.js";
    return $plugin_array;
}
