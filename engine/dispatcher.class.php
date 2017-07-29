<?php
class dispatcher
{
	private $prm = array();
	private $path = array();
	private $instance_exist = false;
	private $tool_exist = false;
	
	function __construct($params=array())
	{
		$this->init($params);
	}
	/* Dispatcher::operation_method */
	private function init($params)
	{
		if(empty($params))
		die('global $params not defined');
		$this->prm = $params;
		$this->set_useragent();
		$this->set_http_data();
		$this->parse_path();
	}
	private function parse_path($callback=false)
	{
		if(!$callback)
		{
			$this->set_url();
			$this->set_page_num();
			$this->set_lang();
			$this->set_service(); 
			$this->set_directive();
			$this->set_tool();
			$this->set_action();
			if($this->tool_exist) { return false; }
		}
		$path_length = count($this->path);
		switch($path_length)
		{
			case 0:
				$this->set_instance();
				//$this->print_prm();	
				return false;
			break;
			default:
				$this->set_sections();
				$this->parse_path(true);
			break;
		}
	}
	private function prevent_aggression($url)
	{
		return str_replace(array('<', '>', '*', "'", '"'), '', $url);
	}
	/* Dispatcher::set_method */
	private function set_url()
	{
		isset($_GET['url']) or $_GET['url'] = '';
		//if($_GET['url'] == '' || $_GET['url'] == 'home') { $this->path[] = 'home'; return false; }
		if($_GET['url'] == 'logout') { $this->path[] = 'logout'; return false; }
		$this->path = explode(SP, $this->prevent_aggression($_GET['url']));
		if(end($this->path) == '') { array_pop($this->path); }
		reset($this->path);
		$this->set_global_params('current_url', $this->prm['site_url'].implode(SP, $this->path));
	}
	private function set_useragent()
	{
		/*
			to do: implemetare i controlli necessari per definire le informazioni sullo usergent utili al sistema 
		*/
		$this->set_global_params('useragent', array('complete_string'=>$_SERVER['HTTP_USER_AGENT']));
	}
	private function set_http_data()
	{
		$get = array();
		$post = array();
		$files = array();
		$referer = (isset($_SERVER['HTTP_REFERER']) && preg_match('/('.$_SERVER['HTTP_HOST'].')/',$_SERVER['HTTP_REFERER'])) 
		? $_SERVER['HTTP_REFERER'] : $this->prm['site_url'];
		if(!empty($_GET)) { foreach($_GET as $key=>$value) { $get[$key] = (is_array($value)) ? utils::trim_values($value) : strip_tags(trim($value)); } }
		if(!empty($_POST)) { foreach($_POST as $key=>$value) { $post[$key] = (is_array($value)) ? utils::trim_values($value) : trim($value); } }
		if(!empty($_FILES)) { foreach($_FILES as $key=>$value) { if(is_array($value)) { $files[$key] = utils::trim_values($value); } } }
		$this->set_global_params('http_data', array('_REFERER'=>$referer, '_GET'=>$get, '_POST'=>$post, '_FILES'=>$files));
	}
	private function set_page_num()
	{
		foreach($this->path as $key=>$values)
		{
			if(preg_match('/^(page-)([0-9]+)$/', $values, $arr))
			{
				$this->prm['pagination'] = $arr[2];
				unset($this->path[$key]);
			}
		}
		reset($this->path);
		$this->set_global_params('complete_slug', implode(SP, $this->path));
	}
	private function set_lang()
	{
		if(in_array(current($this->path), $this->prm['languages']))
		{
			$this->prm['lang'] = array_shift($this->path);
			$this->prm['site_url'] = $this->prm['site_url'].$this->prm['lang'].SP;
		} else {
			$this->prm['lang'] = $this->prm['languages']['default'];
		}
	}
	private function set_service()
	{
		$file = $this->prm['folders']['engine'].SP.'core'.SP.'services.class.php';
		file_exists($file) or die('Fatal error: <b>'.$file.'</b> not found');
		$this->prm['require_files'][] = $file;
		$this->prm['service'] = (in_array(current($this->path), $this->prm['services'])) ? array_shift($this->path) : 'service';
		$file = $this->prm['folders']['application'].SP.'services'.SP.$this->prm['service'].'.class.php';
		file_exists($file) or die('Fatal error: <b>'.$file.'</b> not found');
		$this->prm['require_files'][] = $file;
	}
	private function set_directive()
	{
		$file = $this->prm['folders']['engine'].SP.'core'.SP.'directives.class.php';
		file_exists($file) or die('Fatal error: <b>'.$file.'</b> not found');
		$this->prm['require_files'][] = $file;
		$this->prm['directive'] = (in_array(current($this->path), $this->prm['directives'])) ? array_shift($this->path) : 'directive';
		$file = $this->prm['folders']['application'].SP.'directives'.SP.$this->prm['directive'].'.class.php';
		file_exists($file) or die('Directive Class: <b>'.$this->prm['directive'].'</b> not found');
		$this->prm['require_files'][] = $file;
	}
	private function set_tool()
	{
		$this->prm['tool'] = array();
		$current = current($this->path);
		$current = (empty($current)) ? 'home' : $current;
		$folder = $this->prm['folders']['application'].SP.'tools'.SP.str_replace('-', '_', $current);
		if(is_dir($folder))
		{
			$file = $folder.SP.'ini.php';
			if(file_exists($file))
			{
				$this->prm['tool']['root'] = $folder;
				require($file);
				array_shift($this->path);
				$this->prm['sections'][] = $current;
			} else {
				die('The file <b>ini.php</b> not found in <b>'.$folder.'</b> tool folder.');
			}
		}
	}
	private function set_action()
	{
		$this->prm['action'] = 'read';
		$this->prm['action_params'] = array();
		$action = current($this->path);
		if($action != '' && isset($this->prm['tool'][$action]))
		{
			$this->prm['action'] = array_shift($this->path);
		}
		if(isset($this->prm['tool'][$this->prm['action']]))
		{
			$file = $this->prm['tool'][$this->prm['action']];
			file_exists($file) or die('<b>Fatal error</b>: Action class <b>'.$this->prm['tool']['name'].'_'.$this->prm['action'].'</b> ('.$file.') for tool <b>'.$this->prm['tool']['name'].'</b> must be exist!');
			$this->prm['require_files'][] = $file;
			$this->prm['main_class'] = $this->prm['tool']['name'].'_'.$this->prm['action'];
			$this->prm['action_params']['_get'] = $this->path;
			$this->path = array();
			$this->tool_exist = true;
		}
		if(isset($this->prm['tool']['name'], $this->prm['tool'][$this->prm['tool']['name']][$this->prm['action']]))
		{
			$file = $this->prm['tool'][$this->prm['tool']['name']][$this->prm['action']];
			if(file_exists($file))
			{
				$this->prm['require_files'][] = $file;
			}
		}
		//echo '<pre>'.print_r($this->prm, true).'</pre>';die();
	}
	private function set_sections()
	{
		$current = array_shift($this->path);
		$file = $this->prm['folders']['application'].SP.'sections'.SP.$current.'.class.php';
		if(file_exists($file))
		{
			$this->prm['require_files'][] = $file;
			$this->prm['main_class'] = $current;
			$this->instance_exist = true;
		}
		$this->prm['sections'][] = $current;
	}
	private function set_instance()
	{
		if(!isset($this->prm['sections'])) { $this->prm['sections'] = array(); }
		if(!$this->instance_exist)
		{
			$file = $this->prm['folders']['application'].SP.'sections'.SP.'index.class.php';
			if(file_exists($file))
			{
				$this->prm['require_files'][] = $file;
				$this->prm['main_class'] = 'index';
			} else {
				 die('Fatal error: <b>'.$file.'</b> not found.'); 
			}
		}
	}
	private function set_global_params($name, $value)
	{
		$this->prm[$name] = $value;
	}
	/* Dispatcher::set_method */
	public function get_params()
	{
		$args = func_get_args();
		if(isset($args[0]))
		{
			return (isset($this->prm[$args[0]])) ? $this->prm[$args[0]] : false;
		}
		return $this->prm;
	}
	public function get_classes()
	{
		if(!isset($this->prm['require_files'])) return false;
		foreach($this->prm['require_files'] as $file)
		{
			//echo $file.'<br>';
			require_once($file);
		}
		$this->prm['require_files'] = array();
	}
}