<?php
if( ! defined('ABSPATH') ) die();

function av_coupons_func(){
global $av_settings,$avEcrypt,$wpdb,$avdb;

?>
	<div class="wrap av-vip-coupons">
		<h2 style="font-family:yekan,tahoma;font-weight:normal;font-size:19px;">کوپن ها</h2>
		<a href="<?php echo admin_url() . 'admin.php?page=av_add_coupon'; ?>">اضافه کردن کوپن جدید</a>
	
		<table class="widefat fixed av-payments-table" cellspacing="0">
			
			<thead>
				<tr>
					<th scope="col">نام کوپن</th>
					<th scope="col">نوع کوپن</th>
					<th scope="col">تعداد دفعات استفاده از کوپن</th>
				</tr>
			</thead>
			
			<tfoot>
			
				<tr>
					<th scope="col">نام کوپن</th>
					<th scope="col">نوع کوپن</th>
					<th scope="col">تعداد دفعات استفاده از کوپن</th>
				</tr>
			</tfoot>
			
			<tbody id="the-list">
				<tr>
					<td class='column-name'></td>
					<td class='column-name'></td>
					<td class='column-name'></td>
				</tr>
			</tbody>
			
		</table>
		
		<div class="clr"></div>
	</div>



<?php } 