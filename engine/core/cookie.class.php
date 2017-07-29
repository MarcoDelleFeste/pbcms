<?php
/*
* 
* Class cookie.
* 
* @author Marco Delle Feste <marco.dellefeste@gmail.com>
* @version 1.0
* @package engine.core
*
*/
class cookie
{
	function __construct()
	{
		//echo 'Nothing to do';
	}
	/**
	*
	* metodo set_cookie.
	* 
	* @return void
	*
	*/
	function set_cookie($name, $value, $time=null, $path='/')
	{
		$time = (is_null($time)) ? time()+3600 : $time;
		setcookie($name, $value, $time, $path);
	}
	/**
	*
	* metodo get_cookie.
	* 
	* @return mixedvalue
	*
	*/
	function get_cookie($name)
	{
		if(isset($_COOKIE[$name]))
		{
			return $_COOKIE[$name];
		}
		return false;
	}
	/**
	*
	* metodo remove_cookie.
	* 
	* @return mixedvalue
	*
	*/	
	function remove_cookie($name)
	{
		$time =  time()-46000;
		setcookie($name, '', $time);
	}
}