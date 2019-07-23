<?php
	class advanced_vip_db{
		
		public $orders;
		public $users;
		public $payments;
		public $files;
		public $temp;
		
		public function __construct() {
			$this->users = $this->TableName('members');
			$this->payments = $this->TableName('payments');
			$this->files = $this->TableName('files');
			$this->temp = $this->TableName('temporary');
		}
		
		public function get($query, $get = 'all', $outputType = ARRAY_A) {
			global $wpdb;
			$get = strtolower($get);
			$res = null;
			switch($get) {
				case 'one':
					$res = $wpdb->get_var($query);
					break;
				case 'row':
					$res = $wpdb->get_row($query, $outputType);
					break;
				case 'col':
					$res = $wpdb->get_col($query);
					break;
				case 'all':
				default:
					$res = $wpdb->get_results($query, $outputType);
					break;
			}
			return $res;
		}
		
		public function insertID() {
			global $wpdb;
			return $wpdb->insert_id;
		}
		
		public function getError() {
			global $wpdb;
			return $wpdb->show_errors();
		}
		
		public function lastID() {
			global $wpdb;        
			return $wpdb->insert_id;
		}
		
		function TableName($name){
			global $wpdb;
			return $wpdb->prefix.'av_'.$name;
		}

	}