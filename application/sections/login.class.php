<?php
defined('INDEX_UNLOCK') or die();
class login extends directive
{
	protected function loginListener()
	{
		
	}
	
	protected function _set_page_tools(&$tools)
	{
		$tools['prototype']= array('action'=>'read','autoload'=>true);
		$tools['contents']= array('action'=>'read','autoload'=>false);
	}
	
	

}