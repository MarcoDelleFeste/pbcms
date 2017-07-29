<?php
class tools extends singleton
{
	protected $tools = array();
	function __construct()
	{
		$this->set_tool();
	}
	//Definisce il tool indicato nel path
	private function set_tool()
	{
		if(USE_DB)
		{
			$this->set_tools_from_db();
		} else {
			$this->set_tools_from_file();
		}
	}
	
	private function set_tools_from_db()
	{
	}
	
	private function set_tools_from_file()
	{
		require_once($this->prm->document_root.'tools.php');
		if(isset($pages[$this->page->page_index]))
		{
			$this->tools = array_merge($default, $pages[$this->page->page_index]);
		} else {
			$this->tools = $default;
		}		
		foreach($this->tools as $name=>$param)
		{
			$inifile = TOOLS_PATH.SP.$name.SP.'ini.php';
			if(is_file($inifile))
			{
				require($inifile);
				
				foreach($files['require'] as $file)
				{
					require_once($file);
				}
				if($param['action'] != '')
				{
					$action = $param['action'];
					if(!is_null($this->page->fragments))
					{ 
						$frag_values = array_values($this->page->fragments);
						$action = @vsprintf($param['action'], $frag_values);
						
					}
					if(isset($files[$action]))
					{
						$this->tools[$name]['action'] = $action;
						require_once($files[$action]);
					} else {
						$this->tools[$name]['action'] = '';
					}
				}
				unset($files);
			} else {
				unset($this->tools[$name]);
			}
		}
	}
	
	//Inizializza l'evento (action)
	public function autoload_tools()
	{
		$r = $this->tools;
		foreach($r as $name=>$params)
		{
			if($params['autoload'])
			{
				$cname = str_replace('-', '_', $name);
				$objname = ($params['action'] != '')  ?  $cname.'_'.$params['action'] : $cname;
				$this->tools[$name] = call_user_func($objname.'::get_instance', $params);
				$this->tools[$name]->exec_tool($this->tools);
			}
		}
	}
	
	public function load_tool($name)
	{
		if(isset($this->tools[$name]) && !is_object($this->tools[$name]))
		{
			$params = $this->tools[$name];
			$cname = str_replace('-', '_', $name);
			$objname = ($params['action'] != '')  ?  $cname.'_'.$params['action'] : $cname;
			$this->tools[$name] = call_user_func($objname.'::get_instance', $params);
			$this->tools[$name]->exec_tool($this->tools);
		}
	}
	
	public function get_tool($tool_name=null)
	{
		if(is_null($tool_name))
		{
			return $this->tools;
		}
		if(isset($this->tools[$tool_name]))
		{
			return	$this->tools[$tool_name];
		}
	}
	
	protected function _set($key, $value)
	{
		$this->$key = $value;
	}
	
	protected function _get($key)
	{
		return (isset($this->$key)) ? $this->$key : null;
	}
}