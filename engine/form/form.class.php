<?php
class form
{
	var $fields = array();
	var $form_id = 'form-unfedined';
	var $html = '';
	var $form = '';
	var $label = '';
	var $input = '';
	var $select = '';
	var $textarea = '';
	var $wrapper = '';
	var $warning = '';
	
	function __construct($id, $fields)
	{
		$this->form_id = $id;
		$this->fields = $fields;
		$this->set_html();
		$this->parse_html($id);
	}
	
	private function set_html()
	{
		$files = array('form','label','input','select','textarea', 'wrapper', 'warning');
		$tpl_form_folder = TPL_PATH.DS.$this->form_id.'-form-fields';
		foreach($files as $fname)
		{
			$file = $tpl_form_folder.DS.$fname.'.php';
			if(is_file($file))
			{
				$this->$fname = file_get_contents($file);
			} else {
				$this->$fname = file_get_contents(TPL_PATH.DS.'form-fields'.DS.$fname.'.php');
			}
		}
	}
	
	private function parse_html($id)
	{
		$this->html = $this->parse_field();
	}
	
	private function parse_field()
	{
		$html = '';
		foreach($this->fields as $name=>$attrs)
		{
			$htm = '';
			$tag = $attrs['htm']['tag'];
			switch($tag)
			{
				case 'input':
					$htm = $this->get_input($name, $attrs);
				break;
				case 'select':
					$htm = str_replace('{FIELD_LABEL}', $attrs['label_name'], $this->label);
					$htm = str_replace('{REQUIRE}',  (($attrs['is_required']) ? '*' : ''), $htm);
					$htm .= str_replace('{SELECT_NAME}', $name, $this->select);
					$htm = str_replace('{SELECT_ID}', $name, $htm);
					$htm = str_replace('{OPTIONS}', $this->get_options($attrs['htm']['option'], $name), $htm);
				break;
				case 'textarea':
					$htm = str_replace('{FIELD_LABEL}', $attrs['label_name'], $this->label);
					$htm = str_replace('{REQUIRE}',  (($attrs['is_required']) ? '*' : ''), $htm);
					$htm .= str_replace('{TEXTAREA_NAME}', $name, $this->textarea);
					$htm = str_replace('{TEXTAREA_ID}', $name, $htm);
					$htm = str_replace('{TEXTAREA_VALUE}', '', $htm);
				break;
				default:
					reports::set_report('Tipo non gestito per il campo <b>'.$name.'</b>');
				break;
			}
			$html .= str_replace('{FIELD}', $htm, $this->wrapper);
		}
		return $html;
	}
	
	private function get_input($name, $attrs)
	{
		$type = $attrs['htm']['type'];
		$htm = '';
		switch($type)
		{
			case 'radio':
			case 'checkbox':
				$htm = str_replace('{FIELD_LABEL}', $attrs['label_name'], $this->label);
				$htm = str_replace('{REQUIRE}',  (($attrs['is_required']) ? '*' : ''), $htm);
				if(isset($attrs['htm']['count']))
				{
					for($i=1; $i<=$attrs['htm']['count']; $i++)
					{
						$ht = str_replace('{FIELD_TYPE}', $attrs['htm']['type'], $this->input);
						$ht = str_replace('{FIELD_NAME}', $name, $ht);
						$ht = str_replace('{FIELD_ID}', $name.'-'.$i, $ht);
						$ht = str_replace('{FIELD_VALUE}', $attrs['htm']['values'][$i], $ht);
						if($attrs['htm']['set_checked'])
						{
							$checked = $this->get_checked($name, $attrs['htm']['values'][$i]);
							$ht = str_replace('{CHECKED}', $checked, $ht);
						} else {
							$ht = str_replace('{CHECKED}', '', $ht);
						}
						$htm .= $ht.'<span class="user-management-span">'.$attrs['htm']['labels'][$i].'</span>';
						
					}
				}
			break;
			case 'password':
				$htm = str_replace('{FIELD_LABEL}', $attrs['label_name'], $this->label);
				$htm = str_replace('{REQUIRE}',  (($attrs['is_required']) ? '*' : ''), $htm);
				$htm .= str_replace('{FIELD_TYPE}', $attrs['htm']['type'], $this->input);
				$htm = str_replace('{FIELD_NAME}', $name, $htm);
				$htm = str_replace('{FIELD_ID}', $name, $htm);
				$htm = str_replace('{FIELD_VALUE}', '', $htm);
				$htm = str_replace('{CHECKED}', '', $htm);
			break;
			default:
				$value = (isset($_POST[$name])) ? $_POST[$name] : '';
				$htm = str_replace('{FIELD_LABEL}', $attrs['label_name'], $this->label);
				$htm = str_replace('{REQUIRE}', (($attrs['is_required']) ? '*' : ''), $htm);
				$htm .= str_replace('{FIELD_TYPE}', $attrs['htm']['type'], $this->input);
				$htm = str_replace('{FIELD_NAME}', $name, $htm);
				$htm = str_replace('{FIELD_ID}', $name, $htm);
				$htm = str_replace('{FIELD_VALUE}', $value, $htm);
				$htm = str_replace('{CHECKED}', '', $htm);
			break;
		}
		return $htm;
	}
	
	private function get_options($options, $fieldname)
	{
		$htm = '<option value=""></option>';
		switch($options['type'])
		{
			case 'calendar':
				$calendar = $this->get_date($options['start']);
				$s['m'][1] = 'Gennaio';
				$s['m'][2] = 'Febbraio';
				$s['m'][3] = 'Marzo';
				$s['m'][4] = 'Aprile';
				$s['m'][5] = 'Maggio';
				$s['m'][6] = 'Giugno';
				$s['m'][7] = 'Luglio';
				$s['m'][8] = 'Agosto';
				$s['m'][9] = 'Settembre';
				$s['m'][10] = 'Ottobre';
				$s['m'][11] = 'Novembre';
				$s['m'][12] = 'Dicembre';
				foreach($calendar as $anno=>$mesi) {
					foreach($mesi as $mese=>$giorni)
					{
						$htm .= '<optgroup label="'.$s['m'][$mese].' - '.$anno.'">';
						foreach($giorni as $giorno=>$day)
						{
							$selected = '';
							if(isset($_POST['chose-day-dal']) && $_POST['chose-day-dal'] == $day['data'])
							{
								$selected = ' selected="selected"';
							} else if ($dal == $day['data']) {
								$selected = ' selected="selected"';
							}
							$htm .= '<option value="'.$day['data'].'"'.$selected.'>'.$day['display_data'].'</option>';
						}
				$htm .= '</optgroup>';
					}
				}
			break;
			case 'values_list':
				for($i=$options['start']; $i<=$options['end']; $i++)
				{
					$htm .= '<option value="'.$i.'"'.$this->get_option_selected($fieldname, $i).'>'.$i.'</option>';
				}
			break;
			case 'list':
				foreach($options['values'] as $value)
				{
					$htm .= '<option value="'.$value.'"'.$this->get_option_selected($fieldname, $value).'>'.$value.'</option>';
				}
			break;
			case 'value_label':
				foreach($options['values'] as $value=>$label)
				{
					$htm .= '<option value="'.$value.'"'.$this->get_option_selected($fieldname, $value).'>'.$label.'</option>';
				}
			break;
		}
		return $htm;
	}
	
	private function get_checked($fieldname, $value)
	{
		if(isset($_POST[$fieldname]) && $_POST[$fieldname] == $value)
		{
			return ' checked="checked"';
		}
		return '';
	}
	
	private function get_option_selected($fieldname, $value)
	{
		if(isset($_POST[$fieldname]) && $_POST[$fieldname] == $value)
		{
			return ' selected="selected"';
		}
		return '';
	}
	
	private function get_date($start)
	{
		$calendar = array();
		$canno = date('Y', time());
		$cmese = date('m', time());
		$cgiorno = date('d', time());
		
		$start_data = date('Y-m-d', $start);
		
		$start_anno = date('Y', $start);
		$start_mese = date('m',$start);
		$start_giorno = date('d', $start);
		
		$tday = array(1=>31,2=>28,3=>30,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
		
		for($anno=$canno; $anno>=$start_anno;$anno--)
		{
			$mese = ($anno==$canno) ? $cmese : 12;
			for($mese;$mese>0;$mese--)
			{
				
				$giorno = ($anno==$canno && $mese == $cmese) ? $cgiorno : $tday[$mese];
				for($giorno;$giorno>0;$giorno--)
				{
					//Calcolo la stringa del giorno e del mese con lo 0 davanti se necessario
					// 01, 02, 12
					$tmp_mese = ((int)$mese<10) ? '0'.(int)$mese : $mese;
					$tmp_giorno = ((int)$giorno<10) ? '0'.(int)$giorno : $giorno;
					$calendar[$anno][$mese][$giorno] = array(
						'data'=>$anno.'-'.$tmp_mese.'-'.$tmp_giorno, 
						'display_data'=>$tmp_giorno.'-'.$tmp_mese.'-'.$anno
					);
					//echo $anno.'-'.$tmp_mese.'-'.$tmp_giorno.' 2012-10-13'.'<br>';
				}
			}
		}
		return $calendar;
	}
	
	private function parse_form()
	{
		$htm = str_replace('{FIELDS}', $this->html, $htm);
		$htm = str_replace('{FORM_NAME}', $this->form_id, $htm);
		$htm = str_replace('{FORM_ID}', $this->form_id, $htm);
		$htm = str_replace('{USER_FORM_ACTION}', '?add=', $htm);
		$this->form = $htm;
	}
	
	public function get_form($addForm, $addError)
	{
		$htm = '';
		if($addError)
		{
			$htm = str_replace('{WARNING}', reports::get_report(), $this->warning);
		}
		if($addForm)
		{
			$this->parse_form();
			$htm .=  $this->form; 
		} else {		
			$htm .= $this->html;
		}
		return $htm;
	}
	
}