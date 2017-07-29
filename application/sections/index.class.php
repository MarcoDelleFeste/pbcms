<?php
defined('INDEX_UNLOCK') or die();
class index extends directive
{
	protected function indexListener()
	{
		/*reports::set_report('patate', 0);
		reports::set_report('cicoria', 1);
		reports::set_report('carote');*/
		$this->tools->load_tool('user-account');
	}
	
	private function auth_user()
	{
		if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SESSION['PHP_AUTH_USER_OK'])) {
			header('WWW-Authenticate: Basic realm="'.$this->prm->site_url.'"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Access deneid - 1';
			exit;
		} else if(!isset($_SESSION['PHP_AUTH_USER_OK']))
		{
			if($_SERVER['PHP_AUTH_USER'] == 'Admin' && $_SERVER['PHP_AUTH_PW'] == 'admin$admin')
			{
				$_SESSION['PHP_AUTH_USER_OK'] = true;
			} else {
				header('WWW-Authenticate: Basic realm="'.$this->prm->site_url.'"');
				header('HTTP/1.0 401 Unauthorized');
				echo 'Access deneid - 1';
				exit;
			}
		}
	}
}