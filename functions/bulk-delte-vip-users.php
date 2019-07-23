<?php
	if( ! defined('ABSPATH') ) die();
	if( isset($_GET['page']) && $_GET['page'] == 'av_vip_members' ){
		add_action('admin_head','bulk_delte_vip_users');
		function bulk_delte_vip_users(){
			echo '<script type="text/javascript">';
				echo 'jQuery(document).ready( function($){
					$("input#doaction,input#doaction2").click( function(){
						return confirm("از انجام این عمیلات مطمئن هستید؟");
					});
					$("span.delete").find("a").click( function(){
						return confirm("از انجام این عمیلات مطمئن هستید؟");
					});
				});';
			echo '</script>';
		}
	}
