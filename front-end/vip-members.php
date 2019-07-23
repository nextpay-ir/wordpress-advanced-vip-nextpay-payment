<?php
	if( ! defined('ABSPATH') ) die();
	global $wpdb;

	if( ! class_exists( 'WP_List_Table' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		
	if( ! function_exists('get_userdata') )
		require_once( ABSPATH . 'wp-includes/pluggable.php' );
	
	if( isset($_GET['page']) && $_GET['page'] == 'av_vip_members' && isset($_GET['action']) && $_GET['action'] == 'delete' ){
		$member_delete = $wpdb->query( $wpdb->prepare("DELETE FROM $avdb->users WHERE ID = %d",$_GET['member']));
		if( $member_delete !== false )
			wp_redirect( admin_url().'admin.php?page=av_vip_members&av_message=1' );
		else
			wp_redirect( admin_url().'admin.php?page=av_vip_members&av_message=2&av_error_message=1' );
		exit;
	}
	
	if( isset($_GET['page']) && $_GET['page'] == 'av_vip_members' && isset($_POST['action2']) && $_POST['action2'] == 'delete' ){
		$html = implode(",",$_POST['member']);
		$html = "'" . str_replace(",", "','", $html) . "'";
		$members_delete = $wpdb->query( "DELETE FROM $avdb->users WHERE ID IN ($html);");
		if( $members_delete !== false )
			wp_redirect( admin_url().'admin.php?page=av_vip_members&av_message=3' );
		else
			wp_redirect( admin_url().'admin.php?page=av_vip_members&av_message=4&av_error_message=1' );
		exit;
	}
	
	$orderby_m = isset($_GET['orderby']) ? $_GET['orderby'] : false;
	$order_m = isset($_GET['order']) ? $_GET['order'] : false;
	$order_query = '';
		
	if( $orderby_m !== false )
		$order_query .= 'ORDER BY `'.$orderby_m.'`';
	
	if( $order_m !== false )
		$order_query .= ' '.$_GET['order'].'';
	
	$search_query = '';
	if( isset($_POST['s']) ){
		$search_user_id = $wpdb->get_var("SELECT `ID` FROM ".$wpdb->users." WHERE display_name LIKE '%".$_POST['s']."%'");
		if( $search_user_id === null )
			$search_query = "WHERE user_ID='0'";
		else
			$search_query = "WHERE user_ID='$search_user_id'";
	}
		
	$ad_vip_members_array = array();
	if( $wpdb->get_var('SELECT * FROM `'.$avdb->users.'`') === null ){
		$ad_vip_members_array = array();
	} else{
		foreach( $avdb->get("SELECT * FROM `$avdb->users` $search_query $order_query",'all',ARRAY_A) as $key => $val ){
			$ad_vip_members_array_user_info = get_userdata($val['user_ID']);
			$ad_vip_members_array_data['ID'] = $val['ID'];
			$ad_vip_members_array_data['user_ID'] = $val['user_ID'];
			$ad_vip_members_array_data['user_name'] = '<img class="av_vip_member_avatar" src="http://www.gravatar.com/avatar/'.md5(strtolower($ad_vip_members_array_user_info->user_email)).'?s=30"/>' .'<span class="av_vip_member_name">'.$ad_vip_members_array_user_info->display_name.'</span>';
			$ad_vip_members_array_data['start_date'] = advanced_vip::jalali_nice_format(advanced_vip::av_gr_to_ja($val['start_date'])) . ' (' . str_replace(array('days','hours','mins','day','min','hour'),array('روز','ساعت','دقیقه','روز','دقیقه','ساعت'),human_time_diff( strtotime($val['start_date']) , time() )).' قبل)';
			$ad_vip_members_array_data['expire_date'] = advanced_vip::jalali_nice_format(advanced_vip::av_gr_to_ja( date('Y-m-d H:i:s',$val['expire_date']) )) . ' (' . str_replace(array('days','hours','mins','day','min','hour'),array('روز','ساعت','دقیقه','روز','دقیقه','ساعت'),human_time_diff( time(), $val['expire_date'] )).' مانده)';
			$ad_vip_members_array[] = $ad_vip_members_array_data;
		}
	}
 
class av_vip_members_table extends WP_List_Table {
	
	var $members_data , $found_data;
		
    function __construct(){
		global $status, $page, $ad_vip_members_array;
			parent::__construct( array(
					'singular'  => 'کاربر ویژه',
					'plural'    => 'کاربران ویژه',
					'ajax'      => false
				)
			);
		add_action( 'admin_head', array( &$this, 'admin_header' ) );    
		//$this->members_data = $avdb->get('SELECT * FROM `wp1_av_members`','all',ARRAY_A);
		$this->members_data = $ad_vip_members_array;
    }
 
	function admin_header() {
		global $avdb,$order_query;
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'av_vip_members' != $page )
			return;
		echo '<style type="text/css">';
		echo '.wp-list-table .column-id { width: 5%; }';
		echo '.wp-list-table .column-user_ID { width: 13%; }';
		echo '.wp-list-table .column-user_name { width: 20%; }';
		echo '.wp-list-table .column-start_date { width: 35%; }';
		echo '.wp-list-table .column-expire_date { width: 35%;}';
		echo '.wp-list-table .column-expire_date,.wp-list-table .column-start_date,.wp-list-table .column-user_ID {vertical-align: middle;}';
		echo '</style>';
		echo '<test>';
		//echo 'SELECT * FROM `'.$avdb->users.'` '.$order_query;
		echo '</test>';
	}
 
	function no_items() {
		echo 'کاربری یافت نشد.';
	}
 
	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'user_ID':
			case 'user_name':
			case 'start_date':
			case 'expire_date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
 
	function get_sortable_columns() {
		$sortable_columns = array(
			'user_ID'  => array('user_ID',false),
			'start_date' => array('start_date',false),
			'expire_date'   => array('expire_date',false)
		);
		return $sortable_columns;
	}
 
	function get_columns(){
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'user_ID' => 'آی دی کاربر',
			'user_name' => 'نام کاربر',
			'start_date'    => 'تاریخ شروع اکانت',
			'expire_date'      => 'تاریخ پایان اکانت',
		);
		return $columns;
	}
 
	function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'user_ID';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strcmp( $a[$orderby], $b[$orderby] );
		// Send final sort direction to usort
		//return ( $order === 'asc' ) ? $result : -$result;
	}
 
	function column_user_ID($item){
		$actions = array(
			'delete'    => sprintf('<a href="?page=%s&action=%s&member=%s">حذف</a>',$_REQUEST['page'],'delete',$item['ID'])
		);
		return sprintf('%1$s %2$s', $item['user_ID'], $this->row_actions($actions) );
	}
 
	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'حذف این حساب ها'
		);
		return $actions;
	}
 
	function column_cb($item) {
		return sprintf('<input type="checkbox" name="member[]" value="%s" />', $item['ID']);    
    }
 
	function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		usort( $this->members_data, array( &$this, 'usort_reorder' ) );
		
		$per_page = $this->get_items_per_page('members_per_page', 10);
		$current_page = $this->get_pagenum();
		
		$total_items = count( $this->members_data );
 
		// only ncessary because we have sample data
		$this->found_data = array_slice( $this->members_data,( ( $current_page-1 )* $per_page ), $per_page );
 
		$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page                     //WE have to determine how many items to show on a page
			)
		);
		$this->items = $this->found_data;
	}
 
} //class
 
 
 
function av_add_menu_items(){

	add_menu_page(
		'پیشخوان',
		'اشتراک ویژه',
		'manage_options',
		'av_dashbourd',
		'av_dashbourd_func',
		av_url.'assets/images/menu-icon.png'
	);
	
	$hook = add_submenu_page( 
		'av_dashbourd',
		'کاربران ویژه',
		'کاربران ویژه',
		'activate_plugins',
		'av_vip_members',
		'av_vip_members_func'
	);

	add_submenu_page(
		'av_dashbourd',
		'فایل های محافظت شده',
		'فایل های محافظت شده',
		'manage_options',
		'av_files',
		'av_files_func'
	);

	add_submenu_page(
		'av_dashbourd',
		'لیست پرداخت ها',
		'لیست پرداخت ها',
		'manage_options',
		'av_payments',
		'av_payments_func'
	);

	/*add_submenu_page(
		'av_dashbourd',
		'کوپن ها',
		'کوپن ها',
		'manage_options',
		'av_coupons',
		'av_coupons_func'
	);*/

	add_submenu_page(
		'av_dashbourd',
		'تنظیمات',
		'تنظیمات',
		'manage_options',
		'av_settings',
		'av_settings_func'
	);

	add_submenu_page(
		'av_dashbourd',
		'راهنما',
		'راهنما',
		'read',
		'av_help',
		'av_help_func'
	);

	add_submenu_page(
		null,
		'اضافه کردن دستی کاربر',
		'اضافه کردن دستی کاربر',
		'manage_options',
		'av_add_vip_member',
		'av_add_vip_member_func'
	);

	add_submenu_page(
		null,
		'افزایش اعتبار گروهی',
		'افزایش اعتبار گروهی',
		'manage_options',
		'av_group_increasing',
		'av_group_increasing_func'
	);

	add_submenu_page(
		null,
		'کاهش دادن گروهی اعتبار',
		'کاهش دادن گروهی اعتبار',
		'manage_options',
		'av_group_lessen',
		'av_group_lessen_func'
	);

	/*add_submenu_page(
		null,
		'اضافه کردن کوپن',
		'اضافه کردن کوپن',
		'manage_options',
		'av_add_coupon',
		'av_add_coupon_func'
	);*/
	
  add_action( "load-$hook", 'av_add_options' );
  
}
 
function av_add_options() {
	global $av_vip_member_table;
	$option = 'per_page';
	$args = array(
		'label' => 'کاربر ویژه',
		'default' => 10,
		'option' => 'members_per_page'
	);
	add_screen_option( $option, $args );
	$av_vip_member_table = new av_vip_members_table();
}
add_action( 'admin_menu', 'av_add_menu_items' );
 
 
 
function av_vip_members_func(){
	global $av_vip_member_table;
	echo '</pre><div class="wrap"><h2>کاربران ویژه</h2>'; 
	$av_vip_member_table->prepare_items(); 
?>
	<form method="post">
		<input type="hidden" name="page" value="av_vip_members">
		<?php
		$av_vip_member_table->search_box( 'جست و جوی نام کاربران', 'search_id' );
		$av_vip_member_table->display(); 
	echo '</form></div><div id="av-actions-list-btn"><a class="av-button">عملیات ویژه</a><ul class="hdn" id="av-actions-list">
		<li><a href="'.admin_url('admin.php?page=av_add_vip_member').'">اضافه کردن دستی کاربر</a></li>
		<li><a href="'.admin_url('admin.php?page=av_group_increasing').'">افزایش گروهی اعتبار</a></li>
		<li><a href="'.admin_url('admin.php?page=av_group_lessen').'">کاهش گروهی اعتبار</a></li>
	</ul></div>'; 
}