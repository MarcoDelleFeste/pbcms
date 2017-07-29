<?php
defined('INDEX_UNLOCK') or die();
abstract class directives extends service
{
	//Entry point del service
	final protected function directivesListener()
	{
		$this->directiveListener();
		$this->load_elements();
		$this->load_template();
	}
	//Inizializza il template
	private function load_template()
	{
		$this->template->check_template(TPL_PATH.SP.'tpl.'.$this->page->template_name.'.php');
	}
	//Individua gli elements
	private function load_elements()
	{
		$elements = $this->elements->getAll;
		foreach($elements as $element)
		{
			if($element['callback'] && method_exists($this, $element['callback']))
			{
				$this->$element['callback']();
			}
		}
	}
	
	abstract protected function directiveListener();
}