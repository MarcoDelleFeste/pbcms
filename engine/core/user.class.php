<?php
defined('INDEX_UNLOCK') or die();
class user extends singleton
{
	var $profile = null;
	
	function __construct()
	{
		$this->check_user();
	}
	
	private function check_user()
	{
		if(!$this->submit_login_form($this->prm->http_data['_POST']))
		{
			//$this->session->remove('user');
			if(!is_null($this->session->user))
			{
				$this->profile = $this->session->user;
			} else {
				$this->profile = $this->set_default_user();
				$this->session->user = $this->profile;
			}
		}
	}
	
	private function submit_login_form($post)
	{
		if(isset($post['login-username'], $post['login-password']) && $post['login-username'] != '' && $post['login-password'] != '')
		{
			return $this->set_user_by_credencial($post['login-username'],$post['login-password']);
		}
		if(isset($post['login-username'], $post['login-password']) && ($post['login-username'] == '' || $post['login-password'] == ''))
		{
			reports::set_report('Username e password sono obbligatori');
		}
		return false;
	}
	
	
	private function set_user_by_credencial($uname, $password)
	{
		$blowfish = new Crittografia($uname);
		$encpass = $blowfish->cifratura($password);
		if(DB_LIBS)
		{
			$query = "SELECT ".TABELLA_UTENTI.".*, ".TABELLA_PROFILI_UTENTI.".* FROM ".TABELLA_UTENTI." INNER JOIN ".TABELLA_PROFILI_UTENTI." ON(".TABELLA_UTENTI.".uid = ".TABELLA_PROFILI_UTENTI.".user_id) WHERE ".TABELLA_UTENTI.".username = '".utils::escape($uname)."' AND ".TABELLA_UTENTI.".password='".$encpass."' AND status = 1";
			$res = dbInt::exec_select($query);
			//utils::trace($res);
			if($res)
			{
				$user = new StdClass;
				$user->is_logged = true;
				$user->uid = $res['row']['uid'];
				$user->username = $uname;
				$user->email = $res['row']['email'];
				$user->access_level = $res['row']['access_level'];
				unset($_SESSION['show-fb-alert']);
				$this->profile = $user;
				$this->session->user = $this->profile;
			} else {
				reports::set_report('Dati di accesso non riconosciuti. Hai dimenticato username e password? <a href="'.$this->prm->site_url.'recupera-password">clicca qui</a>');
				//utils::redirect($this->prm->site_url, false);
			}
			//login su db
		} else {
			//login su file
		}
		return false;
	}
	
	private function set_default_user()
	{
		$user = new StdClass;
		$user->is_logged = false;
		$user->uid = 0;
		$user->username = '';
		$user->email = '';
		$user->access_level = 1;
		return $user;
	}
		
	public function logout()
	{
		$this->session->destroy();
		utils::redirect('/home');
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