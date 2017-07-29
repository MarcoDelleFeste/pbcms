<?php
defined('INDEX_UNLOCK') or die();
define('ADMIN_ACCESS_LEVEL', 30);
abstract class directive extends directives
{
	protected function directiveListener()
	{
		if(!utils::check_access(ADMIN_ACCESS_LEVEL, $this->user->access_level))
		{
			utils::redirect();
		}
		$this->call_page_listener();
	}
	

	private function call_page_listener()
	{
		$method = $this->prm->main_class.'Listener';
		if(method_exists($this, $method))
		{
			$this->$method();
		}
	}
}