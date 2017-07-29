<?php
defined('INDEX_UNLOCK') or die();
class user extends singleton
{
	var $profile = null;
	
	function __construct()
	{  
		if(is_null($this->session))
		{
			session::get_instance('session');
		}
		if(!$this->session_user())
		{
			$this->set_default_user();
		}
	}
	
	private function session_user()
	{
		if(isset($_SESSION['user'], $_SESSION['user']->social_profile))
		{
			$this->profile = $_SESSION['user'];
			return true;
		}
		return false;
	}
	
	private function set_default_user()
	{
		$user = new StdClass;
		$user->is_logged = false;
		$user->is_registered = false;
		$user->uid = 0;
		$user->username = '';
		$user->email = '';
		$user->access_level = 1;
		$this->profile = $user;
	}
	
	public function logout()
	{
		$this->session->destroy();
		utils::redirect($this->prm->site_url.'home');
	}
	
	protected function _set($key, $value)
	{
		if($key == 'replaceAll')
		{
			$this->profile = $value;
		} else {
			$this->profile->$key = $value;
		}
	}
	
	protected function _get($name)
	{
		if($name == 'getAll')
			return $this->profile;
		return(isset($this->profile->$name)) ? $this->profile->$name : null;
	}
}