<?php 
	if( ! defined('ABSPATH') ) die();
	function av_filter_content_to_vip_users($content) {
		global $post, $av_current_user_vip, $av_settings;
		
		$post_categories = $post->post_category;
		
		$is_vip_cat = count( array_intersect( (array) $post_categories , (array) $av_settings['vip_categories'] ) ) > 0;
		
		$test = $is_vip_cat ? 'yes' : 'no';
		
		
		if( get_post_meta($post->ID, "av_show_post_only_for_vip", true) == 'yes' || $is_vip_cat )
			$only_for_vip = true;
		else
			$only_for_vip = false;
			
			
		if( get_post_meta($post->ID, "av_unlogged_users_message", true) == '' )
			$logged_in_message = @$av_settings['unlogged_user_message'];
		else
			$logged_in_message = get_post_meta($post->ID, "av_unlogged_users_message", true);
			
			
		if( get_post_meta($post->ID, "av_vip_error_message", true) == '' )
			$vip_error_message = @$av_settings['vip_error_message'];
			
		else
			$vip_error_message = get_post_meta($post->ID, "av_vip_error_message", true) ;
		
		if( $only_for_vip ){
			if( is_user_logged_in() ){
				if( $av_current_user_vip ){
					return $content;
				} else{
					return $vip_error_message ;
				}
			}else{
				return $logged_in_message;
			}		
		} else{
			return $content;
		}

	}
	add_filter('the_content', 'av_filter_content_to_vip_users');
	