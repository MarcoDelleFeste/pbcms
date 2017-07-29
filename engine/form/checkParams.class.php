<?php
require_once('checkFields.class.php');
class checkParams
{
	function call($method, $value, $param=array())
	{
		global $strings;
		$method = (!is_null($method)) ? 'ck_'.$method : 'ck_clear';
		if(method_exists('CheckParams', $method))
		{
			return self::$method($value, $param);
		}
		Reports::set_report('Il metodo <b>$1</b> non &egrave; stato trovato', 0, array('$1'=>$method,'$2'=>'CheckParams'));
		return false;
	}
	
	private function ck_password($value)
	{
		return $value;
	}
	private function ck_clear($value)
	{
		return htmlentities(strip_tags($value));
	}
	private function ck_reg_exp($value, $param)
	{
		if(preg_match($param['exp'], $value)) { return $value; }
		return false;
	}
	private function ck_url($value)
	{
		if(preg_match("/^([0-9a-zA-Z_\-:\.\/]*)$/", $value)) { return $value; }
		return false;
	}
	/*private function ck_bool($value)
	{
		$return = false;
		if($value == '0' || $value == '1') { $return = $value; }
		if($value === 'on') { $return = '1'; }
		return $return;
	}*/
	private function ck_bool($value)
	{
		$return = false;
		$return = ($value !== 'on') ? $value : '1';
		return $return;
	}
	private function ck_itaiva($value)
	{
		if(preg_match("/^([0-9]{11,11})$/", $value)) { return $value; }
		return false;
	}
	private function ck_itacf($value)
	{
		if(preg_match("/^([A-Z0-9]{16,16})$/", $value)) { return $value; }
		return false;
	}
	private function ck_itacap($value)
	{
		if(preg_match("/^([0-9]{5,5})$/", $value)) { return $value; }
		return false;
	}
	private function ck_word($value)
	{
		if(preg_match("/^([0-9a-zA-Z_\-\.]*)$/", $value)) { return $value; }
		return false;
	}
	private function ck_url_name($value)
	{
		if(preg_match("/^([0-9a-zA-Z\-]*)$/", $value)) { return $value; }
		return false;
	}
	private function ck_system_name($value)
	{
		if(preg_match("/^([0-9a-zA-Z_]*)$/", $value)) { return $value; }
		return false;
	}
	private function ck_db_field_name($value)
	{
		if(preg_match("/^([0-9a-zA-Z_]{2,64})$/", $value)) { return $value; }
		return false;
	}
	private function ck_string($value)
	{
		if(preg_match("/^([:\.\/!?',àèìòùé0-9a-zA-Z _-]*)$/", $value)) { return strip_tags($value); }
		return $value;
	}
	private function ck_text($value)
	{
		return strip_tags($value);
	}
	private function ck_date($value)
	{
		if(preg_match("/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])$/", $value)) { return $value; }
		return false;
	}
	private function ck_datetime($value)
	{
		if(preg_match("/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))$/", $value)) { return $value; }
		return false;
	}
	private function ck_mysqldate($value)
	{
		if(preg_match("/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])$/", $value)) { return $value; }
		return false;
	}
	private function ck_editor($value)
	{
		$value = $value;
		return $value;
	}
	private function ck_mail($value)
	{
		if(preg_match("/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i", $value)) { return strtolower($value); }
		return false;
	}
	private function ck_phone($value)
	{
		$value = str_replace(' ', '', $value);
		if(preg_match('/^([0-9]+)|(\+[0-9]+)$/', $value)) { return $value; }
		return false;
	}
	private function ck_num($value)
	{
		if(is_numeric($value)) { return $value; }
		return false;
	}
	private function ck_decimal($value)
	{
		if(preg_match('/^([0-9]*)$/', $value)) { return $value.'.00'; }
		if(preg_match('/^([0-9]*)(,)([0-9]*)$/', $value)) { return str_replace(',', '.', $value); }
		if(preg_match('/^([0-9]*)([\.])([0-9]*)$/', $value)) { return $value; }
		return false;
	}
	private function ck_adult($value)
	{
		return self::checkData($value, 18);
	}
	private function ck_data($value,$arr)
	{
		$ck_adult = (isset($arr['adult']) && $arr['adult']) ? $arr['adult'] : false;
		if(preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})$/", $value, $dataMember))
		{
			$arrayMesi = array('02'=>28, '04'=>30, '06'=>30, '09'=>30, '11'=>30);
			$giorno = $dataMember[2];
			$mese = $dataMember[1];
			$anno = $dataMember[3];
			$annoCorrente = date('Y', time());
			$strgiorno = ($giorno < 10) ? '0'.$giorno : $giorno;
			$value = $anno.'-'.$mese.'-'.$strgiorno;
			if($ck_adult && !utils::is_adult($giorno, $mese, $anno, $ck_adult)) { $value = false; }
			if(Utils::is_leap($anno)) { $arrayMesi['02'] = 29; }
			if($giorno > 31 || (isset($arrayMesi[$mese]) && $arrayMesi[$mese] < $giorno)) { $value = 2; }
			if($mese > 12) { $value = false; }
			//Il controllo sull'anno è da rivedere dato che impedisce l'inserimento di date future...
			if($anno > $annoCorrente) { $value = false; }
		} else {
			$value = false;
		}
		return $value;
	}
}