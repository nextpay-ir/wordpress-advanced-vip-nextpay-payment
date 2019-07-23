<?php
if( ! defined('ABSPATH') ) die();
function av_add_vip_member_func(){
global $av_settings,$avEcrypt,$wpdb,$avdb;
$Users = $wpdb->get_results("SELECT ID,display_name FROM $wpdb->users",ARRAY_A);
?>

<div class="wrap av-add-vip-member">

	<div id="add-custom-user">
		<form id="av-custom-user-form">
		<table>
		<tr>
			<td class="first-side"><label for="users_list">ابتدا یک کاربر را انتخاب کنید.</label></td>
			<td>
				<select id="users_list">
					<?php foreach( $Users as $key => $val ){ ?>
						<option value="<?php echo $val['ID']; ?>"><?php echo $val['display_name']; ?></option>
					<?php } ?>
				</select>
			</td>	
		</tr>
		
		<tr>
			<td class="first-side"><label for="charge_type">روش شارژ حساب را وارد کنید.</label></td>
			<td>
				<select id="charge_type">
						<option value="day">وارد کردن مقدار به روز</option>
						<option value="date">انتخاب یک تاریخ ثابت</option>
				</select>
			</td>	
		</tr>
		
		<tr id="charge-day">
			<td class="first-side"><label for="charge_days">مقدار شارژ را وارد کنید (مقدار را به روز وارد کنید.)</label></td>
			<td><input type="text" id="charge_days"/></td>
		</tr>
		
		<tr id="charge-date" class="hdn">
			<td class="first-side"><label for="charge_date">برای انتخاب تاریخ روی فیلد کلیک کنید.</label></td>
			<td><input type="text" id="charge_date"/></td>
		</tr>
		<tr>
			<td class="first-side">
				<a id="send-add-user-data" class="av-button" ajax="<?php echo admin_url('admin-ajax.php'); ?>" href="#">ارسال اطلاعات</a>
				<img src="<?php echo includes_url().'images/wpspin.gif'; ?>" class="av-preloader hdn"/>
				<span class="hdn save_ok">اطلاعات با موفقیت ذخیره شدند.</span>
				<span class="hdn save_error">خطایی رخ داده است. اگر فکر می کنید این یک باگ است، آن را به نویسنده پلاگین گزارش دهید.</span>
			</td>
			<td>
			
			</td>
		</tr>
		</table>
		</form>
		<br/><br/><br/><br/>
		<p>
			نکته 1: اگر کاربر دارای حساب کاربری ویژه از قبل بوده باشد، مقدار شارژی که وارد می کنید به حساب وی اضافه می شود.
		</p>
		<p>
			نکته 2: اگر کاربر دارای حساب کاربری ویژه نباشد، برای وی یک حساب کاربری ایجاد می شود.
		</p>
		<p>
			نکته 3: اگر برای انقضای اکانت کاربر یک تاریخ ثابت وارد کنید، اگر کاربر انتخاب شده از قبل وجود داشته باشد، سیستم بدون توجه به اعتبار حساب وی برای اکانت وی یک تاریخ انقضای ثابت تعریف می کنید.
		</p>
	</div>

	
</div>


<?php } 