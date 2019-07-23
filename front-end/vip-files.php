<?php
if( ! defined('ABSPATH') ) die();

function av_files_func(){
global $av_settings,$avEcrypt,$wpdb,$avdb;

if( $wpdb->get_var("SELECT * FROM $avdb->files") !== null ){
	$page_number = isset($_GET['av_page_number']) ? intval($_GET['av_page_number']) : 1 ;
	$limitStart = ($page_number-1) * 15;
	$files_list = $wpdb->get_results("SELECT * FROM $avdb->files LIMIT $limitStart,15",ARRAY_A);
	$all_files_count = $wpdb->get_var("SELECT COUNT(*) FROM $avdb->files");
	$pages_count = ceil((int) $all_files_count / 15 );
}
?>
	<div class="wrap av-vip-files">
	
	<a href="#" class="av-button av-button-new-file">آپلود فایل جدید</a>
	
<table class="widefat fixed av-files-table" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">نام اصلی فایل</th>
			<th scope="col">نام تغییر یافته فایل</th>
			<th scope="col">حجم فایل</th>
			<th scope="col">نوع فایل</th>
			<th scope="col">تاریخ آپلود فایل</th>
			<th scope="col">ویرایش</th>
			<th scope="col" style="width:22%;">کد میانبر</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col">نام اصلی فایل</th>
			<th scope="col">نام تغییر یافته فایل</th>
			<th scope="col">حجم فایل</th>
			<th scope="col">نوع فایل</th>
			<th scope="col">تاریخ آپلود فایل</th>
			<th scope="col">ویرایش</th>
			<th scope="col" style="width:22%;">کد میانبر</th>
		</tr>
	</tfoot>
	<tbody id="the-list">
		<?php if( $wpdb->get_var("SELECT * FROM $avdb->files") === null ) {?>
		<tr>
			<td class='column-name' colspan="7">فایلی یافت نشد.</td>
		</tr>
		<?php } else { foreach( $files_list as $key => $value) { ?>
		<tr>
			<td class='column-name'><?php echo $value['file_name']; ?></td>
			<td class='column-name'><?php echo $value['encypted_name']; ?></td>
			<td class='column-name'><?php echo advanced_vip::bytesToSize($value['file_size']); ?></td>
			<td class='column-name'><?php echo $value['file_type']; ?></td>
			<td class='column-name'><?php echo advanced_vip::jalali_nice_format(advanced_vip::av_gr_to_ja($value['upload_date'])); ?></td>
			<td class='column-name'>
				<a href="#" file_id="<?php echo $value['ID']; ?>" ajax_url="<?php echo admin_url('admin-ajax.php'); ?>" class="av-delete-file">حذف </a>
			</td>
			<td class='column-name' style="width:22%;">
				<code style="direction:ltr;text-align:left;" class="av-file-sc">[vip-file id="<?php echo $value['ID']; ?>"]</code>
			</td>
		</tr>
		<?php } } ?>
	</tbody>
</table>
	<?php 
	if( $wpdb->get_var("SELECT * FROM $avdb->files") !== null ){
		foreach( range(1,$pages_count) as $item){
			$current_class = intval($page_number) === intval($item) ? 'current' : '';
			echo '<a href="'.admin_url().'admin.php?page=av_files&av_page_number='.$item.'" class="av-button av-page-number-btn '.$current_class.'">صفحه '.$item.'</a>';
		}	
	}	
	?>
	<div class="clr"></div>
</div>

<div class="wrap hdn av-upload-file">
	<a href="#" class="av-button av-button-files-list">لیست فایل ها</a>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="av-upload-files-appenable">
			<div class="av-vip-file-item"><input type="file" name="vip_file[]" /></div>
		</div>	
		<button id="more-files" class="button button-small">فایل های بیشتر</button>
		<br/><br/><br/>
		<input type="submit" class="button-primary" value="ارسال فایل(ها)" />
		<input type="hidden" name="av_files_upload" value="true" />
	</form>
	<?php 
		if( isset($_GET['upload_status']) )
		foreach( unserialize($avEcrypt->de($_GET['upload_status'])) as $item ){
			echo $item . '<br/>';
		}
	?>	
</div>


<?php } 