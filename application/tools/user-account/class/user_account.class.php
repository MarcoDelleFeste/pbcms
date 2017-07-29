<?php
class user_account
{
	private static $instance = null;
	private $prm = array();
	private $params = array();
	protected $response = null;
	protected $tools = null;
	
	public static function get_instance($params)
	{
		if(self::$instance == null)
		{
			$c = ($params['action'] != '')  ?  __CLASS__.'_'.$params['action'] : __CLASS__;
			self::$instance = new $c($params);
		}
		return self::$instance;
	}
	
	private function __construct($params)
	{
		$this->params = $params;
		$this->prm = params::get_instance();
	}
	
	function exec_tool(&$tools)
	{
		$this->tools = $tools;
		$action = $this->params['action'].'Listener';
		$this->$action();
	}
	
	function Listener()
	{
		//echo 'Listener';
	}
	
	protected function get_user_by_uid($uid)
	{
		return $this->get_user('uid', $uid);
	}
	
	protected function get_user_by_email($email)
	{
		return $this->get_user('uid', $uid);
	}
	
	protected function get_user_by_username($username)
	{
		return $this->get_user('uid', $uid);
	}
	
	private function get_user($field, $value, $match_type = '=')
	{
		$res = dbInt::exec_select("SELECT * FROM ".TBL_USERS." INNER JOIN ".TBL_USERS_PROFILE." ON (".TBL_USERS.".uid = ".TBL_USERS_PROFILE.".user_id) WHERE ".$field." ".$match_type." '".$v."");
		trace($res);
	}
}