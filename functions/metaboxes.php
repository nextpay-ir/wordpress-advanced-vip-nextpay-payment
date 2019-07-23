<?php 
	if( ! defined('ABSPATH') ) die();
	add_action( 'add_meta_boxes', 'is_add_product_info_metabox' );

	function is_add_product_info_metabox(){
	
		$post_types = get_post_types();
		
		unset( $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item'] );
		
		foreach( $post_types as $key => $value ) {
			add_meta_box( 
				'is-product-meta-box',
				'عضویت ویژه',
				'is_product_info_metabox',
				$key,
				'normal',
				'high'
			);
			
		}
		
	}

if ( false ) {
	add_action('admin_print_scripts', 'is_admin_file_upload1');
	add_action('admin_print_styles', 'is_admin_file_upload2');
	function is_admin_file_upload1() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', pg_url.'/pg_scripts.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}
	function is_admin_file_upload2() {
		wp_enqueue_style('thickbox');
	}
}

	
function is_product_info_metabox(){
	global $post,$is_admin_data;
	$values = get_post_custom( $post->ID );
	$av_show_post_only_for_vip = isset( $values['av_show_post_only_for_vip'] ) ? $values['av_show_post_only_for_vip'][0] : '';  
	$av_unlogged_users_message = isset( $values['av_unlogged_users_message'] ) ? $values['av_unlogged_users_message'][0] : '';  
	$av_vip_error_message = isset( $values['av_vip_error_message'] ) ? $values['av_vip_error_message'][0] : '';  
	wp_nonce_field( 'av_meta_box_nonce', 'meta_box_nonce' ); 
	echo '<table class="is_product_info">';

		echo '<tr>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo "<label for='av_show_post_only_for_vip'>نمایش محتویات فقط به کاربران وی آی پی</label><br/>";
			echo '</td>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo "<select name='av_show_post_only_for_vip' id='av_show_post_only_for_vip'>";
					echo "<option value='no' ".selected( $av_show_post_only_for_vip, 'no' ).">خیر</option>";
					echo "<option value='yes' ".selected( $av_show_post_only_for_vip, 'yes' ).">بله</option>";
				echo "</select>";
			echo '</td>';	
		echo '</tr>';

		echo '<tr>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo "<label for='av_unlogged_users_message'>متنی که به کاربران وارد نشده نمایش داده شود</label><br/>";
			echo '</td>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo '<textarea id="av_unlogged_users_message" name="av_unlogged_users_message" rows="3" cols="65" >'.$av_unlogged_users_message.'</textarea>';
				echo '<br/><span>اگر خالی رها شود، از پیام پیشفرض تعیین شده در تنظیمات افزونه استفاده می شود.</span>';
			echo '</td>';	
		echo '</tr>';

		echo '<tr>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo "<label for='av_vip_error_message'>متنی که به کاربرانی که اعتبار وی آی پی برای مشاهده نوشته ندارند</label><br/>";
			echo '</td>';
			echo '<td style="padding-top:15px;padding-bottom:15px;">';
				echo '<textarea id="av_vip_error_message" name="av_vip_error_message" rows="3" cols="65" >'.$av_vip_error_message.'</textarea>';
				echo '<br/><span>اگر خالی رها شود، از پیام پیشفرض تعیین شده در تنظیمات افزونه استفاده می شود.</span>';
			echo '</td>';	
		echo '</tr>';
		
	echo '</table>';
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id ){
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'av_meta_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	if( isset( $_POST['av_show_post_only_for_vip'] ) )
		update_post_meta( $post_id, 'av_show_post_only_for_vip', $_POST['av_show_post_only_for_vip'] );
	
	if( isset( $_POST['av_unlogged_users_message'] ) )
		update_post_meta( $post_id, 'av_unlogged_users_message', wp_kses($_POST['av_unlogged_users_message'],array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'span' => array(),
			'p' => array()
		)) );
	
	if( isset( $_POST['av_vip_error_message'] ) )
		update_post_meta( $post_id, 'av_vip_error_message', wp_kses($_POST['av_vip_error_message'],array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'span' => array(),
			'p' => array()
		)) );
	
}
