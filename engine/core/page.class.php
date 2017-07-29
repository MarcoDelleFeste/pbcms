<?php
class page extends singleton
{ 
	protected $pages = array();
	protected $current_url = null;
	protected $current_page = null;
	
	function __construct()
	{
		if(USE_DB)
		{
			$this->set_page_from_db();
		} else {
			$this->set_page_from_file();
			$this->check_maintenance();
		}
		if(!utils::check_access($this->current_page['access_level'], $this->user->access_level))
		{
			$this->current_page = array('id'=>'error-403','template_name'=>'default', 'body'=>'http-errors/error-403.php', 'access_level'=>10, 'title'=>'403 - Forbidden','current_page'=>'error-page');
		}
	}
	
	private function set_page_from_db()
	{
		
	}
	
	private function set_page_from_file()
	{
		require_once($this->prm->document_root.'routes.php');
		$this->pages = $routes;
		$sections = $this->prm->sections;
		$csec = count($sections);
		$current_url = implode('/', $sections);
		$this->current_url = $current_url;
		if($current_url == '' || $current_url == 'index')
		{
			$current_url = 'home';
		}
		if($current_url == 'logout')
		{
			$this->user->logout();
		}
		if(isset($pages[$current_url]))
		{
			$this->current_page = $pages[$current_url];
			$cp_temp = $this->current_page;
			foreach($cp_temp as $key=>$value)
			{
				$this->current_page[$key] = @vsprintf($value, $sections);
			}
			$this->current_page['page_index'] = $current_url;
			$this->current_page['current_page'] = $current_url;
			
			$assign_to_fragmets = array();
			foreach($sections as $k=>$v)
			{
				if($k == 0)
				{
					$assign_to_fragmets['first'] = $v;
				} else if($k == count($sections)-1 && $k > 0)
				{
					$assign_to_fragmets['last'] = $v;
				} else {
					$assign_to_fragmets[$k] = $v;
				}
			}
			$this->current_page['fragments'] = $assign_to_fragmets;
		} else {
			$regexpuri = $this->get_regexp_uri($sections);
			$best_choice = false;
			$bc_temp = array('count'=>false, 'position'=>false);
			foreach($pages as $curi=>$array)
			{
				if(preg_match('/^'.$regexpuri.'$/', $curi, $arr))
				{
					$first = array_shift($arr);	
					$c = 0;
					$p = 1;
					$fragments = array('first'=>$arr[0]);
					
					foreach($arr as $k=>$frag)
					{	
						if($frag == '*')
						{
							
							$c = $c+1;
							$p = $p+$k;
							if($k == $csec-1)
							{
								$fragments['last'] = $sections[$k];
							} else if(isset($sections[$k])){
								$fragments[$k] = $sections[$k];
							}
							
						}
					}
					if($bc_temp['count'] === false || $bc_temp['count'] > $c)
					{
						$bc_temp['count'] = $c;
						$bc_temp['position'] = $p;
						$bc_temp['frags'] = $fragments;
						$best_choice = $curi;
					} else if($bc_temp['count'] === false || $bc_temp['count'] == $c)
					{
						if($bc_temp['position'] < $p)
						{
							$bc_temp['count'] = $c;
							$bc_temp['position'] = $p;
							$bc_temp['frags'] = $fragments;
							$best_choice = $curi;
						}
					}
				}
			}
			if($best_choice)
			{
				$frag_values = array_values($bc_temp['frags']);
				$this->current_page = $pages[$best_choice];
				$cp_temp = $this->current_page;
				foreach($cp_temp as $key=>$value)
				{
					$this->current_page[$key] = @vsprintf($value, $frag_values);
				}
				$this->current_page['page_index'] = $best_choice;
				$this->current_page['current_page'] = $current_url;
				$this->current_page['fragments'] = $bc_temp['frags'];
				
			} else {
				$this->current_page = array('id'=>'not-found','template_name'=>'default', 'body'=>'http-errors/error-404.php', 'access_level'=>10, 'title'=>'404 - Not found','current_page'=>'error-page');
			}
		}
	}
	
	private function check_maintenance()
	{
		if($this->user->access_level >= 4)
		return true;
		if(COMINGSOON && $this->current_page['template_name'] != 'popup' && !isset($this->current_page['exclude_maintenance']) && $this->current_page['current_page'] != 'coming-soon')
		{
			utils::redirect($this->prm->site_url.'coming-soon');
		}
	}
	
	private function get_regexp_uri($sections)
	{
		$first = array_shift($sections);
		return '('.$first.')\/('.implode('|\*)'.'\/'.'(', $sections).'|\*)';
	}
	
	public function send_header()
	{
		if($this->current_page['id'] == 'not-found')
		{
			header("HTTP/1.0 404 Not Found");
		}
		if($this->current_page['id'] == 'error-403')
		{
			header("HTTP/1.0 403 Forbidden");
		}
	}
	
	public function getAllPages()
	{
		return $this->pages;
	}
	
	protected function _set($property, $value)
	{
		$this->current_page[$property] = $value;
	}
	
	protected function _get($property)
	{
		if($property == 'getAll')
		{
			return $this->current_page;
		} else {
			return(isset($this->current_page[$property]))	? $this->current_page[$property] : null;
		}
	}
}