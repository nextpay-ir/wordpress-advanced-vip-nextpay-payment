<?php
if( ! defined('ABSPATH') ) die();
function av_payments_func(){
global $av_settings,$avEcrypt,$wpdb,$avdb;

if( $wpdb->get_var("SELECT ID FROM $avdb->payments") !== null ){
	$page_number = isset($_GET['av_page_number']) ? intval($_GET['av_page_number']) : 1 ;
	$limitStart = ($page_number-1) * 30;
	$custom_query = '';
	$payments_list = $wpdb->get_results("SELECT * FROM $avdb->payments LIMIT $limitStart,30",ARRAY_A);
	$all_payments_count = $wpdb->get_var("SELECT COUNT(*) FROM $avdb->payments");
	$pages_count = ceil((int) $all_payments_count / 30 );
}
?>
	<div class="wrap av-vip-payments">
	<h2 style="font-family:yekan,tahoma;font-weight:normal;font-size:19px;">لیست پرداخت ها</h2>
<table class="widefat fixed av-payments-table" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">آی دی</th>
			<th scope="col">آی پی پرداخت کننده</th>
			<th scope="col">تاریخ پرداخت</th>
			<th scope="col">مبلغ</th>
			<th scope="col">شماره پیگیری</th>
			<th scope="col">درگاه پرداخت</th>
			<th scope="col">حذف</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col">آی دی</th>
			<th scope="col">آی پی پرداخت کننده</th>
			<th scope="col">تاریخ پرداخت</th>
			<th scope="col">مبلغ</th>
			<th scope="col">شماره پیگیری</th>
			<th scope="col">درگاه پرداخت</th>
			<th scope="col">حذف</th>
		</tr>
	</tfoot>
	<tbody id="the-list">
		<?php if( $wpdb->get_var("SELECT ID FROM $avdb->payments") !== null ){ foreach( $payments_list as $key => $value) { 
		$mdate = date('Y-m-d H:i:s',$value['payment_date']);
		$jdate = advanced_vip::av_gr_to_ja($mdate);
		$jnicedate = advanced_vip::jalali_nice_format($jdate);
		?>
		<tr>
			<td class='column-name'><?php echo $value['ID']; ?></td>
			<td class='column-name'><code><?php echo $value['paymenter_ip']; ?></code></td>
			<td class='column-name'><?php echo $jnicedate; ?></td>
			<td class='column-name'><?php echo number_format($value['payment_cost']); ?> تومان</td>
			<td class='column-name'><code><?php echo $value['refNumber']; ?></code></td>
			<td class='column-name'><?php echo $value['payment_agancy']; ?></td>
			<td class='column-name'><a onclick="return confirm('از انجام این عملیات مطمئن هستید؟');" href="<?php echo admin_url('admin.php?action=delete_payment_item&id='.$value['ID']); ?>">حذف</a></td>
		</tr>
		<?php } } else { ?>
		<tr>
			<td class='column-name' colspan="7">هنوز پرداختی انجام نشده است.</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php 
	if( $wpdb->get_var("SELECT ID FROM $avdb->payments") !== null ){
		foreach( range(1,$pages_count) as $item){
			$current_class = intval($page_number) === intval($item) ? 'current' : '';
			echo '<a href="'.admin_url().'admin.php?page=av_payments&av_page_number='.$item.'" class="av-button av-page-number-btn '.$current_class.'">صفحه '.$item.'</a>';
		}	
	}	
?>
<div class="clr"></div></div>



<?php } 