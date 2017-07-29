<?php
class elements extends singleton
{
	protected $current_elements = null;
	
	function __construct()
	{
		if(USE_DB)
		{
			$this->set_elements_from_db();
		} else {
			$this->set_elements_from_file();
		}
	}
	
	private function set_elements_from_db()
	{
		
	}
	
	private function set_elements_from_file()
	{
		require_once($this->prm->document_root.'elements.php');
		//$a = $this->page->template;
		if(isset($pages[$this->page->current_page]))
		{
			$elements = array_merge($default, $pages[$this->page->current_page]);
		} else if(isset($pages[$this->page->current_page.SP.'*'])){
			$elements = array_merge($default, $pages[$this->page->current_page.SP.'*']);
		}else {
			$elements = $default;
		}
		$this->current_elements = $this->parse_elements($elements);
	}
	
	//Associa i contenuti agli elementi
	private function parse_elements($elements)
	{
		$e = $elements;
		foreach($e as $name=>$attrs)
		{
			if(!file_exists($this->prm->folders['structure'].SP.'elements'.SP.$attrs['file']))
			{
				unset($elements[$name]);
				continue;
			}
			if(!utils::check_access($attrs['access_level'], $this->user->profile->access_level))
			{
				unset($elements[$name]);
			}
		}
		//utils::trace($elements);
		return $elements;
	}
	
	protected function _set($element, $properties)
	{
		$this->current_elements[$element] = $properties;
	}
	
	protected function _get($ename)
	{
		if($ename == 'getAll')
			return $this->current_elements;
		return(isset($this->current_elements[$ename]))	? $this->current_elements[$ename] : null;
	}
	
	public function __call($ename, $a)
	{
		if(isset($this->current_elements[$ename]))
		{
			$this->current_elements[$ename][$a[0]] = $a[1];
		}
	}
}