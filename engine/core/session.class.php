<?php

class session extends singleton
{

	protected function __construct()
	{
		session_start();
		utils::get_return_data();
	}
	
	protected function _get($key)
	{
		if($key == 'getAll')
		{
			return $_SESSION;
		}
		return (array_key_exists($key, $_SESSION)) ? $_SESSION[$key] : null;
	}
	
	protected function _set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	public function remove($key)
	{
		unset($_SESSION[$key]);
	}
	
	public function destroy()
	{
		$_SESSION = array();
		session_destroy();
	}
	
}