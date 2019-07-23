<?php
	class av_ecrypt{
		private static function key(){
			$key = get_option('av_EncyptKey');
			if( isset($key) && $key !== '' && is_string($key) ){
				return $key;
			} else {
				return 0;
			}
		}
		
		private function encode_base64($sData){
			$sBase64 = base64_encode($sData);
			return str_replace('=', '', strtr($sBase64, '+/', '-_'));
		}
		private function decode_base64($sData){
			$sBase64 = strtr($sData, '-_', '+/');
			return base64_decode($sBase64.'==');
		}
		public function en($sData='NULL'){
			$sResult = '';
			$secretKey = self::key();
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
				$sChar    = chr(ord($sChar) + ord($sKeyChar));
				$sResult .= $sChar;
			}
			return self::encode_base64($sResult);
		}
		public function de($sData='NULL'){
			$sResult = '';
			$secretKey = self::key();
			$sData   = self::decode_base64($sData);
			for($i=0;$i<strlen($sData);$i++){
				$sChar    = substr($sData, $i, 1);
				$sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
				$sChar    = chr(ord($sChar) - ord($sKeyChar));
				$sResult .= $sChar;
			}
			return $sResult;
		}
	}