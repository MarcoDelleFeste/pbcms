<?php
defined('INDEX_UNLOCK') or die();
abstract class directive extends directives
{
	protected function directiveListener()
	{
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