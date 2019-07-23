<?php
if( ! defined('ABSPATH') ) die();

function av_group_lessen_func(){
global $av_settings,$avEcrypt,$wpdb,$avdb;
?>


<div class="wrap av-group-incearsing">
<?php if( isset($_GET['message']) && $_GET['message'] == 'success' ) { ?>
<div class="av-admin-message success">مقدار <strong><?php echo human_time_diff($_GET['added']+time(),time()); ?></strong> از حساب کاربران کم شد.</div>
<?php } ?>
<?php if( isset($_GET['message']) && $_GET['message'] == 'error' ) { ?>
<div class="av-admin-message error">ظاهرا خطایی پیش آمده است. اگر فکر می کنید این یک باگ است، آن را به نویسنده پلاگین گزارش دهید.</div>
<?php } ?>
	<h3>کاهش گروهی اعتبار کاربران</h3>
	<form method="post" action="">
		<table style="width:100%;">
			<tr>
				<td>واحد کم شدن را انتخاب کنید.</td>
				<td>
					<select name="vip_charge_unit">
						<option value="min">دقیقه</option>
						<option value="hour">ساعت</option>
						<option value="day">روز</option>
						<option value="week">هفته</option>
						<option value="mon">ماه</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>مقدار را وارد کنید.</td>
				<td>
					<input type="text" name="vip_decharge_value" />
				</td>
			</tr>
			<tr>
				<td><input type="submit" class="button button-large" value="ذخیره" /></td>
				<td></td>
			</tr>
		</table>
	</form>	
</div>

<?php } 