<?php
class reports
{
	private static $msgs = array('notice'=>array(),'errors'=>array(),'success'=>array());
	
	static function set_report($str, $type='notice', $replacement=false, $index=null)
	{
		if(is_array($replacement))
		{
			foreach($replacement as $key=>$value)
			{
				$str = str_replace($key, $value, $str);
			}
		}
		$type = self::case_convert($type);
		if(is_array($str) && is_null($index))
		{
			self::$msgs[$type] = $str;
		} else {
			if(is_null($index))
			{
				self::$msgs[$type][] = $str;
			} else {
				self::$msgs[$type][$index] = $str;
			}
		}
	}
	/**
	*
	* Per ragioni di retrocompatibilità viene implementato questo metodo che converte indici numerici in stringhe
	*
	*/
	static private function case_convert($key)
	{
		if(is_numeric($key))
		{
			switch($key)
			{			
				case 0:
					$key = 'errors';
				break;
				case 1:
					$key = 'success';
				break;
				default:
					$key = 'notice';
				break;
			}
		}
		return $key;
	}
	/**
	*
	*
	*
	*/
	static function get_report($case=null)
	{
		if(!is_null($case) && isset(self::$msgs[$case]))
		{
			return self::$msgs[$case];
		} else {
			return self::$msgs;
		}
	}
	/**
	*
	*
	*
	*/
	static function get_notice($key)
	{
		return (isset(self::$msgs['notice'][$key])) ? self::$msgs['notice'][$key] : '';
	}
	/**
	*
	*
	*
	*/
	static function get_error($key)
	{
		return (isset(self::$msgs['errors'][$key])) ? self::$msgs['errors'][$key] : '';
	}
	/**
	*
	*
	*
	*/
	static function get_success($key)
	{
		return (isset(self::$msgs['success'][$key])) ? self::$msgs['success'][$key] : '';
	}
	/**
	*
	*
	*
	*/
	static function get_css_error($index, $css_class)
	{
		return (isset(self::$msgs['errors'][$index])) ? $css_class : '';
	}
	/**
	*
	*
	*
	*/
	static function count_report($case=null)
	{
		if(!is_null($case) && isset(self::$msgs[$case]))
		{
			return count(self::$msgs[$case]);
		} else {
			return count(self::$msgs['notice'])+count(self::$msgs['errors'])+count(self::$msgs['success']);
		}
	}
	/**
	*
	*
	*
	*/
	static function unset_msg($case, $key)
	{
		$case = 'msg_'.$case;
		$ref = $$case;
		unset(self::$ref[$key]);
	}
}