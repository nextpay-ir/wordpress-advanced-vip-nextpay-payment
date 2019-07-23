<?php
	if( ! defined('ABSPATH') ) die();
	function av_settings_func(){
	global $av_settings,$wp_roles;
	?>
		<div class="wrap av-option-panel">
	
			<h2 class="nav-tab-wrapper">
				<a go="av-general" class="nav-tab nav-tab-active">عمومی</a>
				<a go="av-payment" class="nav-tab">درگاه پرداخت</a>
				<a go="av-sms-panel" class="nav-tab">پنل پیامک</a>
				<a go="av-acconts" class="nav-tab">بازه های زمانی</a>
				<a go="av-templates" class="nav-tab">قالب ها</a>
				<a go="av-mail-and-sms" class="nav-tab">پیامک ها و ایمیل ها</a>
			</h2>
		<form id="av-settings-form">
		
			<div class="av-tab-panel" id="av-general">
				<h2>تنظیمات عمومی</h2>
				
				<div class="section">
					<div class="setting-label"><label for="enble_free_dl">فعال بودن دانلود رایگان</label></div>
					<label for="enable_dl" class="radio-label">فعال کردن</label>
					<input type="radio" name="enble_free_dl[]" id="enable_dl" value="on" <?php echo @$av_settings['enble_free_dl'][0] == 'on' ? 'checked' : ''; ?>/>
					<label for="disable_dl" class="radio-label" style="margin-right: 25px;">غیر فعال کردن</label>
					<input type="radio" name="enble_free_dl[]" id="disable_dl" value="off" <?php echo @$av_settings['enble_free_dl'][0] == 'off' ? 'checked' : ''; ?>/>
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="free_dl_speed">حداکثر سرعت دانلود رایگان</label></div>
					<input type="text" name="free_dl_speed" id="free_dl_speed" value="<?php echo @$av_settings['free_dl_speed']; ?>" /><br/>
					<span style="font-size:11px;">سرعت را به کیلوبایت بر ثانیه وارد کنید. مثلا <code>100</code></span>
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="remote_veify_key">کلید تایید اعتبار کاربر</label></div>
					<input type="text" name="remote_veify_key" id="remote_veify_key" value="<?php echo @$av_settings['remote_veify_key']; ?>" /><br/>
					<span>برای مثال عبارت <code><?php echo substr(str_shuffle('wertyuiopasdfghjklzxcvbnm!@#$%^&*'),0,10); ?></code> را وارد کنید. برای اطلاعات بیشتر فایل راهنما را مطالعه کنید.</span>
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="protected_files_dir">مسیر فایل های محافظت شده</label></div>
					<input type="text" name="protected_files_dir" id="protected_files_dir" value="<?php echo @$av_settings['protected_files_dir']; ?>" /><br/>
					<span>مسیر فایل ها از ریشه سایت تعیین می گردد. برای مثال اگر مقدار <code>files</code> را وارد کنید، در اصل پوشه فایل ها با آدرس <code><?php echo site_url();?>/files</code> تعریف می گردد.</span>
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="vip_categories">دسته بندی های وِیژه</label></div>
					<select name="vip_categories" id="vip_categories" multiple="multiple">
						<?php foreach( get_categories( array('hide_empty'=>0) ) as $ca ){ ?>
							<?php 
								if( in_array($ca->term_id, (array) @$av_settings['vip_categories']) )
									$selected = 'selected="selected"';
								else
									$selected = '';
							?>	
							<option value="<?php echo $ca->term_id; ?>" <?php echo $selected; ?>><?php echo $ca->name; ?></option>
						<?php } ?>
					</select><br/>
					<span>برای انتخاب چند دسته بندی از دکمه CTRL استفاده کنید.</span>
				</div>
				
				
				
				<div class="section">
					<div class="setting-label"><label for="default_vip_roles">چه نقش های کاربری به طور پیشفرض وی آی پی هستند؟</label></div>
					<select name="default_vip_roles" id="default_vip_roles" multiple="multiple">
						<?php foreach( $wp_roles->roles as $roleID => $roleData ){ ?>
							<?php 
								if( in_array( $roleID, (array) @$av_settings['default_vip_roles']) )
									$selected2 = 'selected="selected"';
								else
									$selected2 = '';
							?>	
							<option value="<?php echo $roleID; ?>" <?php echo $selected2; ?>><?php echo $roleData['name']; ?></option>
						<?php } ?>
					</select><br/>
					<span>برای انتخاب چند دسته بندی از دکمه CTRL استفاده کنید.</span>
				</div>
				
				
				
				<div class="section">
					<div class="setting-label"><label for="unlogged_user_message">متن پیشفرض برای کاربران وارد نشده.</label></div>
					<textarea name="unlogged_user_message" id="unlogged_user_message"><?php echo @$av_settings['unlogged_user_message']; ?></textarea><br/>
					<span>می توانید از تگ های اچ تی ام ال استفاده کنید.</span>
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="vip_error_message">متن پیشفرض برای کاربرانی که دسترسی وی آی پی ندارند.</label></div>
					<textarea name="vip_error_message" id="vip_error_message"><?php echo @$av_settings['vip_error_message']; ?></textarea><br/>
					<span>می توانید از تگ های اچ تی ام ال استفاده کنید.</span>
				</div>

			</div>

			<div class="av-tab-panel" id="av-sms-panel">
			
				<h2>تنظیمات پیامک</h2>
				
				<div class="section">
					<div class="setting-label"><label for="sms_agancy">پنل پیامک</label></div>
					<select id="sms_agancy" name="sms_agancy">
						<option value="parandsms" <?php selected( @$av_settings['sms_agancy'] , 'parandsms' ); ?>>پرند اس ام اس</option>
						<option value="smsdehi" <?php selected( @$av_settings['sms_agancy'] , 'smsdehi' ); ?>>اس ام اس دهی</option>
					</select>
				</div>

			<div style="background: #FDFBEF;padding: 30px;margin-bottom: 30px;" id="parandsms">	
				<h2>پرند اس ام اس</h2>
				<div class="section">
					<div class="setting-label"><label for="parandsms_username">نام کاربری پرند اس ام اس</label></div>
					<input type="text" name="parandsms_username" id="parandsms_username" value="<?php echo @$av_settings['parandsms_username']; ?>" />
				</div>

				<div class="section">
					<div class="setting-label"><label for="parandsms_password">رمز عبور پرند اس ام اس</label></div>
					<input type="text" name="parandsms_password" id="parandsms_password" value="<?php echo @$av_settings['parandsms_password']; ?>" />
				</div>

				
				<div class="section">
					<div class="setting-label"><label for="parandsms_from">شماره ارسالی پرند اس ام اس</label></div>
					<select id="parandsms_from" name="parandsms_from">
						<option value="100004126" <?php selected( @$av_settings['parandsms_from'] , '100004126' ); ?>>100004126</option>
					</select>
				</div>
			</div>
				
			<div style="background: #FDFBEF;padding: 30px;" id="smsdehi">	
				<h2>اس ام اس دهی</h2>
				<div class="section">
					<div class="setting-label"><label for="smsdehi_username">نام کاربری اس ام اس دهی</label></div>
					<input type="text" name="smsdehi_username" id="smsdehi_username" value="<?php echo @$av_settings['smsdehi_username']; ?>" />
				</div>

				<div class="section">
					<div class="setting-label"><label for="smsdehi_password">رمز عبور اس ام اس دهی</label></div>
					<input type="text" name="smsdehi_password" id="smsdehi_password" value="<?php echo @$av_settings['smsdehi_password']; ?>" />
				</div>
				
				<div class="section">
					<div class="setting-label"><label for="smsdehi_from">شماره ارسالی اس ام اس دهی</label></div>
					<select id="smsdehi_from" name="smsdehi_from">
						<option value="3000890009" <?php selected( @$av_settings['smsdehi_from'] , '3000890009' ); ?>>3000890009</option>
						<option value="30002592" <?php selected( @$av_settings['smsdehi_from'] , '30002592' ); ?>>30002592</option>
						<option value="custom_number" <?php selected( @$av_settings['smsdehi_from'] , 'custom_number' ); ?>>شماره اختصاصی</option>
					</select>
				</div>
				
				<div class="section" id="smsdehi_custom_number" style="display:none;">
					<div class="setting-label"><label for="smsdehi_customNumber">شماره اختصاصی اس ام اس دهی</label></div>
					<input type="text" name="smsdehi_customNumber" id="smsdehi_customNumber" value="<?php echo @$av_settings['smsdehi_customNumber']; ?>" />
				</div>
			</div>
			
			
				
			</div>	
				
			<div class="av-tab-panel" id="av-payment">
			
				<h2>تنظیمات درگاه پرداخت</h2>
				
				<div class="section">
					<div class="setting-label"><label for="payment_agancy">درگاه پرداخت</label></div>
					نکست پی
					<input type="hidden" name="payment_agancy" id="payment_agancy" value="nextpay" />					
				</div>

				<div id="faragate_agancy" style="background: #FDFBEF;padding: 30px;margin-bottom: 30px;">
					<h2>تنظیمات نکست پی</h2>
					<div class="section">
						<div class="setting-label"><label for="nextpay_apikey">کلید مجوزدهی ( مرچنت ) نکست پی</label></div>
						<input type="text" name="nextpay_apikey" id="nextpay_apikey" value="<?php echo @$av_settings['nextpay_apikey']; ?>" />
					</div>
				</div>


			</div>

			<div class="av-tab-panel" id="av-acconts">
				<h2>بازه های زمانی و هزینه ها</h2>
				<div class="section">
					<p>در بخش فیلد های عضویت شما بازه های زمانی عضویت ویژه و هزینه این زمان را تعیین می کنید. مثلا عضویت ویژه یک ماهه با هزینه 10 هزار تومان، عضویت ویژه 3 ماه با هزینه 30 هزار تومان و ...</p>
				</div>
				<div class="section" id="vip_times">
					<?php
						$i = 0;
						if( isset($av_settings['vip_time_id']) ){
							foreach( $av_settings['vip_time_id'] as $item) {
								echo '<div class="av_draggable_item">
										<div><label>آی دی بازه (هر بازه زمانی باید دارای یک آی دی منحصر به فرد باشد)</label></div>
										<div><input type="text" value="'.$av_settings['vip_time_id'][$i].'" name="vip_time_id[]"></div>
										<div><label>نام نمایشی بازه (نامی که به عنوان عنوان بازه زمانی در فرم خرید نمایش داده می شود)</label></div>
										<div><input type="text" value="'.$av_settings['vip_time_name'][$i].'" name="vip_time_name[]"></div>
										<div><label>هزینه این بازه زمانی (تمام هزینه ها به تومان هستند)</label></div>
										<div><input type="text" value="'.$av_settings['vip_time_price'][$i].'" name="vip_time_price[]"></div>
										<div><label>مدت زمان این آیتم (مقدار وارد شده به روز باید باشد مثلا برای یک ماه باید مقدار 30 وارد شود.</label></div>
										<div><input type="text" value="'.$av_settings['vip_time'][$i].'" name="vip_time[]"></div>
										<button class="av_remove-draggble_item">حذف این آیتم</button><div class="clr"></div>
									</div>';
								$i++;
							}
						}
					?>
				</div>
				<button class="button button-small" id="new-time-field">اضافه کردن فیلد جدید</button>
			</div>


			
			<div class="av-tab-panel" id="av-templates">
				<h2>قالب ها</h2>
				<div class="section av-code-section">
					<div class="setting-label"><label for="download_page_template">قالب صفحه دانلود</label></div>
					<textarea name="download_page_template" id="download_page_template" class="av-code" style="width:450px;height:400px;direction:ltr;text-align:left;"><?php echo @$av_settings['download_page_template']; ?></textarea>
					<br/>
					<span>مهم ترین تگ هایی که باید در این کد استفاده کنید:<br/> <code>{vip-link}</code>: این تگ با آدرس پیوند لینک دانلود تعویض خواهد شد.<br/><code>{free-link}</code>: این تگ با آدرس پیوند لینک دانلود رایگان تعویض خواهد شد. دقت کنید، برای لینک های دانلود رایگان باید از طریق همین پنل تنظیمات فعال سازی انجام شوند. <br/>هم چنین می توانید از تگ های زیر استفاده کنید: <br/><code>{file-size}</code>: نمایش حجم فایل<br/><code>{file-name}</code>: نام اصلی فایل<br/><code>{downloads-count}</code>: تعداد دفعات دانلود فایل</span>
				</div>
				
				
				
			</div>

			
			
			<div class="av-tab-panel" id="av-mail-and-sms">
				<h2>پیامک ها و ایمیل ها</h2>
				
				<div style="background: #FAF6EC;padding: 20px;margin: 20px -20px;border-top: 1px solid #EBE5D6;border-bottom: 1px solid #EBE5D6;">
					<h3>شروع حساب کاربری</h3>
					<p>
						توضیح:
						هنگامی که کاربری اکانت خود را شارژ می کند تابعی اجرا میشود که می توانید تعریف کنید که به کاربر ایمیل داده شود و یا به وی اس ام اس ارسال شود.
					</p>
					
			
					<div class="section">
						<div class="setting-label"><label for="email_on_vip_start">ارسال ایمیل</label></div>
						<label><input type="checkbox" name="email_on_vip_start" id="email_on_vip_start" <?php echo ( isset( $av_settings['email_on_vip_start'] ) && $av_settings['email_on_vip_start'] == 'on' ) ? 'checked="checked"' : ''; ?> /> بله</label>
					</div>
						
						
					<div class="section">
						<div class="setting-label"><label for="vip_register_mail_subject">عنوان ایمیل</label></div>
						<input type="text" name="vip_register_mail_subject" id="vip_register_mail_subject" value="<?php echo @$av_settings['vip_register_mail_subject']; ?>" />
					</div>
				
					
					<div class="section av-code-section">
						<div class="setting-label"><label for="vip_register_mail_template">قالب ایمیل</label></div>
						<textarea name="vip_register_mail_template" id="vip_register_mail_template" class="av-code" style="width:600px;height:400px;direction:ltr;text-align:left;"><?php echo @$av_settings['vip_register_mail_template']; ?></textarea>
						<br/>
						<span>می توانید از متغیر های زیر استفاده کنید: <br/></span>
						<span><code>{member-name}</code>: نمایش نام کاربر</span><br/>
						<span><code>{expire-jdate}</code>: نمایش تاریخ پایان حساب به تاریخ شمسی</span><br/>
						<span><code>{expire-human-date}</code>: نمایش تاریخ در قالب مانده (مثلا 6 ساعت به انقضا مانده)</span><br/>
						<span><code>{start-jdate}</code>: نمایش تاریخ شروع حساب به شمسی</span><br/>
						<span><code>{site-title}</code>: عنوان سایت شما</span><br/>
						<span><code>{site-url}</code>: آدرس سایت شما</span><br/>
						<span><code>{refNumber}</code>: شماره پیگیری پرداخت آنلاین</span><br/>
						<span><code>{payment-cost}</code>: هزینه پرداخت شده</span><br/>
						<span><code>{user-email}</code>: آدرس ایمیل کاربر</span><br/>
					</div>
						
						
						
					<div class="section">
						<div class="setting-label"><label for="sms_on_vip_start">ارسال پیامک</label></div>
						<label><input type="checkbox" name="sms_on_vip_start" id="sms_on_vip_start" <?php echo ( isset( $av_settings['sms_on_vip_start'] ) && $av_settings['sms_on_vip_start'] == 'on' ) ? 'checked="checked"' : ''; ?> /> بله</label>
					</div>

					<div class="section">
						<div class="setting-label"><label for="sms_on_vip_start_template">قالب پیامک</label></div>
						<textarea name="sms_on_vip_start_template" id="sms_on_vip_start_template"><?php echo $av_settings['sms_on_vip_start_template']; ?></textarea>
						<br/>
						<span>می توانید از متغیر های زیر استفاده کنید: <br/></span>
						<span><code>{member-name}</code>: نمایش نام کاربر</span><br/>
						<span><code>{expire-jdate}</code>: نمایش تاریخ پایان حساب به تاریخ شمسی</span><br/>
						<span><code>{expire-human-date}</code>: نمایش تاریخ در قالب مانده (مثلا 6 ساعت به انقضا مانده)</span><br/>
						<span><code>{start-jdate}</code>: نمایش تاریخ شروع حساب به شمسی</span><br/>
						<span><code>{site-title}</code>: عنوان سایت شما</span><br/>
						<span><code>{site-url}</code>: آدرس سایت شما</span><br/>
						<span><code>{refNumber}</code>: شماره پیگیری پرداخت آنلاین</span><br/>
						<span><code>{payment-cost}</code>: هزینه پرداخت شده</span><br/>
						<span><code>{user-email}</code>: آدرس ایمیل کاربر</span><br/>
					</div>

					
				</div>
				
				
				
				<div style="background: #FAF6EC;padding: 20px;margin: 20px -20px;border-top: 1px solid #EBE5D6;border-bottom: 1px solid #EBE5D6;">
					<h3>نزدیک شدن پایان حساب کاربری</h3>
					<p>
						توضیح:
						سیستم هر ساعت به طور خودکار بررسی می کند و لیستی از کاربرانی که اکانت هایشان کمتر از دو روز اعتبار دارد تهیه می کند.
						حال در این دو روز دو بار تابعی اجرا می شود که می تواند ارسال پیامک به کاربر و یا ارسال ایمیل به وی را وصل کرد.
					</p>
					
				
					<div class="section">
						<div class="setting-label"><label for="email_on_close_expire">ارسال ایمیل</label></div>
						<label><input type="checkbox" name="email_on_close_expire" id="email_on_close_expire" <?php echo ( isset( $av_settings['email_on_close_expire'] ) && $av_settings['email_on_close_expire'] == 'on' ) ? 'checked="checked"' : ''; ?> /> بله</label>
					</div>

					
					<div class="section">
						<div class="setting-label"><label for="close_expire_mail_subject">عنوان ایمیل</label></div>
						<input type="text" name="close_expire_mail_subject" id="close_expire_mail_subject" value="<?php echo @$av_settings['close_expire_mail_subject']; ?>" />
					</div>
				
					
					<div class="section av-code-section">
						<div class="setting-label"><label for="close_expire_mail_template">قالب ایمیل</label></div>
						<textarea name="close_expire_mail_template" id="close_expire_mail_template" class="av-code" style="width:450px;height:400px;direction:ltr;text-align:left;"><?php echo @$av_settings['close_expire_mail_template']; ?></textarea>
						<br/>
						<span>می توانید از متغیر های زیر استفاده کنید: <br/></span>
						<span><code>{member-name}</code>: نمایش نام کاربر</span><br/>
						<span><code>{expire-jdate}</code>: نمایش تاریخ پایان حساب به تاریخ شمسی</span><br/>
						<span><code>{expire-hdate}</code>: نمایش تاریخ در قالب مانده (مثلا 6 ساعت به انقضا مانده)</span><br/>
						<span><code>{member-mail}</code>: نمایش ایمیل کاربر</span><br/>
						<span><code>{start-jdate}</code>: نمایش تاریخ شروع حساب به شمسی</span><br/>
						<span><code>{start-hdate}</code>: نمایش تاریخ شروع حساب به مقدار قبل (مانند شش ماه قبل)</span><br/>
						<span><code>{site-title}</code>: عنوان سایت شما</span><br/>
						<span><code>{site-url}</code>: آدرس سایت شما</span>
					</div>


				
					<div class="section">
						<div class="setting-label"><label for="sms_on_close_expire">ارسال پیامک</label></div>
						<label><input type="checkbox" name="sms_on_close_expire" id="sms_on_close_expire" <?php echo ( isset( $av_settings['sms_on_close_expire'] ) && $av_settings['sms_on_close_expire'] == 'on' ) ? 'checked="checked"' : ''; ?> /> بله</label>
					</div>

					<div class="section">
						<div class="setting-label"><label for="sms_on_close_expire_template">قالب پیامک</label></div>
						<textarea name="sms_on_close_expire_template" id="sms_on_close_expire_template"><?php echo @$av_settings['sms_on_close_expire_template']; ?></textarea>
						<br/>
						<span>می توانید از متغیر های زیر استفاده کنید: <br/></span>
						<span><code>{member-name}</code>: نمایش نام کاربر</span><br/>
						<span><code>{expire-jdate}</code>: نمایش تاریخ پایان حساب به تاریخ شمسی</span><br/>
						<span><code>{expire-hdate}</code>: نمایش تاریخ در قالب مانده (مثلا 6 ساعت به انقضا مانده)</span><br/>
						<span><code>{member-mail}</code>: نمایش ایمیل کاربر</span><br/>
						<span><code>{start-jdate}</code>: نمایش تاریخ شروع حساب به شمسی</span><br/>
						<span><code>{start-hdate}</code>: نمایش تاریخ شروع حساب به مقدار قبل (مانند شش ماه قبل)</span><br/>
						<span><code>{site-title}</code>: عنوان سایت شما</span><br/>
						<span><code>{site-url}</code>: آدرس سایت شما</span>
					</div>
				
					
				
				</div>
				
				
				
				
			</div>
			
			
			<input type="submit" value="ذخیره تنظیمات" class="button-primary button-large" id="av-save-settings" ajax_url='<?php echo admin_url('admin-ajax.php'); ?>' />
			<img src="<?php echo includes_url().'images/wpspin.gif'; ?>" id="av_settings_page_preloader"/>
			<span id="ad_settings_saved">تنظیمات با موفقیت ذخیره شدند.</span>
			<span id="ad_settings_save_error">خطایی هنگام ذخیره تنظیمات رخ داد. اگر فکر می کنید این یک باگ است، آن را به نویسنده پلاگین گزارش دهید.</span>
		</form>
		</div>
	<?php } 
