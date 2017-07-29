<?php
abstract class singleton
{
	private static $instance = null;
	
	public static function get_instance($class_name)
	{
		if(!isset(self::$instance[$class_name]))
		{
			self::$instance[$class_name] = new $class_name();
		}
		return self::$instance[$class_name];
	}
	
	public function __set($key, $value)
	{
		$this->_set($key, $value);
	}
	
	public function __get($name)
	{
		if($name == 'getAllInstance')
		{
			return self::$instance;
		}
		if($name == 'prm')
		{
			return params::get_instance();
		}
		return (isset(self::$instance[$name])) ? self::$instance[$name] : $this->_get($name);
	}
	abstract protected function _set($key, $value);
	abstract protected function _get($key);
	
}