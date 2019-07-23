<?php

	if( ! defined('ABSPATH') ) die();

	function av_admin_messages($message=0, $errormsg = false){
		if( $message === 0 ) return;
		$output = '';
		if( $errormsg )
			$output .= '<div class="error"><p>';
		else 
			$output .= '<div class="updated"><p>';
		if( $message === 1 )
			$output .= 'کاربر مورد نظر با موفقیت حذف شد.';
		if( $message === 2 )
			$output .= 'خطایی هنگام حذف کاربر پیش آمد. اگر فکر می کنید این یک باگ است آن را به نویسنده افزونه گزارش دهید.';
		else if( $message === 3 )
			$output .= 'کاربران مورد نظر با موفقیت حذف شدند.';
		else if( $message === 4 )
			$output .= 'حذف کاربران انتخاب شده موفقیت آمیز نبود. اگر فکر می کنید این یک باگ است، آن را به نویسنده پلاگین گزارش دهید.';
		else if( $message === 5 )
			$output .= 'آیتم مورد نظر با موفقیت حذف شد.';
		else if( $message === 6 )
			$output .= 'حذف آیتم مورد نظر موفقیت آمیز نبود. اگر فکر می کنید این یک باگ است، آن را به نویسنده پلاگین گزارش دهید.';
		$output .= '</p></div>';
		echo $output;
	}

	function av_showAdminMessages(){
		$error = isset($_GET['av_error_message']) ? true : false;
		if( isset($_GET['av_message']) ){
			av_admin_messages(intval($_GET['av_message']), $error);
		}	
	}

	add_action('admin_notices', 'av_showAdminMessages'); 
