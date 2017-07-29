<?php
class params
{
	private static $instance = null;
	private $pool;
	
	public static function get_instance($prm=array())
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c($prm);
		}
		return self::$instance;
	}
	
	private function __construct($prm)
	{
		$this->pool = $prm;
	}
	public function get($key)
	{
		if($this->has($key))
		{
			return $this->pool[$key];
		}
		if($key == 'getAll')
		{
			return $this->pool;
		}
		return null;
	}
	
	public function set($key, $value)
	{
		$this->pool[$key] = $value;
	}
	
	public function has($key)
	{
		return array_key_exists($key, $this->pool);
	}
	
	public function __get($key)
	{
		return $this->get($key);
	}
	
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}
	
	public function __unset($name)
	{
		unset($this->$name);
	}
}