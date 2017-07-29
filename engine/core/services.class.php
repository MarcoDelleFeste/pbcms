<?php
defined('INDEX_UNLOCK') or die();
abstract class services extends core
{ 
	//Entry point del core
	protected function servicesListener()
	{
		$this->serviceListener();
		$this->tools->autoload_tools();
		$this->load_contents();
		$this->call_directive();
	}
	
	//Definisce i contenuti
	private function load_contents()
	{
		if(USE_DB)
		{
			
		} else {
			
		}
	}
	//Avvia la dirctive
	private function call_directive()
	{
		$this->directivesListener();
	}
	
	abstract protected function serviceListener();

}