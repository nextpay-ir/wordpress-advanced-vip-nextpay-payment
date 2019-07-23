<?php 
	
	if( ! defined('ABSPATH') ) die();
	
	function avInsertPayment( $data = null ){
		global $wpdb,$avdb;
		if($data === null)return;
		return $wpdb->insert(
			$avdb->payments,
			array(
				"paymenter_ip" => advanced_vip::getRealIp(),
				"payment_date" => time(),
				"payment_cost" => $data['price'],
				"refNumber" => $data['ref'],
				"payment_agancy" => $data['ag'],
			)
		);
	}
