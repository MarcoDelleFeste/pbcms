<?php
defined('INDEX_UNLOCK') or die();
abstract class core
{
	protected $prm	= array();
	protected $session = array();
	protected $user = null;
	protected $page = null;
	protected $tools = null;
	protected $template = null; 
	protected $elements = null;
	protected $objlist = array('session', 'user', 'page', 'tools', 'elements', 'template');
	
	final function __construct()
	{ 
		$this->prm = params::get_instance();
		foreach($this->objlist as $obj)
		{
			$this->set_($obj);
		}
		$this->call_service();
	}
	
	//instanzia l'oggetto passato come primo parametro
	private function set_($class_name)
	{
		$this->$class_name = call_user_func($class_name.'::get_instance', $class_name);
	}
	
	//Avvia il service
	private function call_service()
	{
		$this->servicesListener();
	}
	
	public function get_object($objname)
	{
		return (isset($this->$objname)) ? $this->$objname : null;
	}
	
	public function get_prm() 
	{
		$args = func_get_args();
		if(empty($args)) { return $this->prm; }
		$return = $this->prm;
		foreach($args as $key=>$value)
		{
			if(isset($return[$value]))
			{
				$return = $return[$value];
			} else {
				$return = false;
			}
		}
		return $return;
	}
}