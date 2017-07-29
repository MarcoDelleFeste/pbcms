<?php
class template extends singleton
{
	protected $tpl_file = null;
	protected $template_path = null;
	private $files = array();
	
	private $atest = array();
	function __construct()
	{

	}
	
	private function set_template_path($file)
	{
		$this->template_path = $file;
	}
	
	function check_template($file)
	{
		if(is_file($file))
		{
			$this->set_template_path($file);
		} else {
			$this->set_template_path(TPL_PATH.SP.$this->prm->template);
		}
	}
	
	function get_attrs($attrs)
	{
		$cattrs = '';
		foreach($attrs as $name=>$value)
		{
			$cattrs .= ' '.$name.'="'.$value.'"';
		}
		return $cattrs;
	}
	
	function get_template_path()
	{
		return $this->template_path;
	}
	
	function set_files($type, $fpath, $attrs=array())
	{
		$this->files[$type][] = array('f'=>$fpath, 'attrs'=>$attrs);
	}
	
	function get_files($type)
	{
		$htmlfiles = '';
		if(isset($this->files[$type]))
		{
			foreach($this->files[$type] as $key => $values)
			{
				switch($type)
				{
					case 'css':
						$htmlfiles .= '<link href="'.$values['f'].'"'.$this->get_attrs($values['attrs']).' />'.NL;
					break;
					case 'js':
						$htmlfiles .= '<script src="'.$values['f'].'"'.$this->get_attrs($values['attrs']).'></script>'.NL;
					break;
				}
			}
		}
		return $htmlfiles;
	}
	
	function get_element($ename, $attrs=array(), $return=false)
	{
		$html = '';
		$params = $this->prm;
		$page = $this->page;
		$tools = $this->tools;
		$elements = $this->elements;
		$user = $this->user;
		$e = $this->elements->$ename;
		if(!is_null($e))
		{
			if(!isset($e['html']))
			{
				ob_start();
				require($this->prm->folders['structure'].SP.'elements'.SP.$e['file']);
				$html = ob_get_clean();
				$a = array_merge($e, array('html'=>$html));
				$this->elements->$ename = $a;
			} else {
				$html = $e['html'];
			}
			if(isset($e['prefix']))
			{
				$html = $e['prefix'].$html;
			}
			if(isset($e['suffix']))
			{
				$html = $html.$e['suffix'];
			}
		}
		if($return)
		{
			return $html;
		} else {
			echo $html;
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