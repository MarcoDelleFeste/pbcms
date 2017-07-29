<?php
class checkFields
{
	var $fields = array();
	var $field_attrs = array();
	var $post_data = array();
	var $current_fields = array();
	var $msg = array();
	var $cc = 0;
	
	function __construct($fields, $field_attrs, $post_data)
	{
		global $strings;
		$this->msg = $strings;
		$this->fields = $fields;
		$this->field_attrs = $field_attrs;
		$this->post_data = $post_data;
	}
	
	function get_result(&$obj)
	{
		$ret = $this->check_fields($obj,$this->fields,$this->field_attrs,$this->post_data);
		return $ret;
	}
	
	private function check_fields(&$obj, $fields=null, $field_attrs=null, $post_data=null, $callback=false, $cbk_return=true)
	{
		$return = $cbk_return;
		$cb_cf = array(); //callback_curren_fields
		foreach($fields as $key=>$field)
		{
			$field = (is_numeric($key) || is_array($field)) ? $field : $key;
			if(is_array($field))
			{
				if(isset($post_data[$key]))
				{
					$r = $this->_callback($obj, $field, $field_attrs[$key], $post_data[$key], $return);
					$return = $r[0];
					$cb_cf += ($callback) ? array($key=>$r[1]) : $r[1];
					if(!$callback) {
						
						$this->current_fields[$key] = $cb_cf;
						$cb_cf = array();
					}
					$this->cc++;
					continue;
				} else {
					reports::set_report('L\'array <b>$1</b> non &egrave; stato trovato', 'errors', array('$1'=>$key), $index_field);
					$return = false;
				}
			}
			
			$cfa = (isset($field_attrs[$field])) ? $field_attrs[$field] : $field_attrs;// $cfa is current field attributes
			
			//caso: il field è un array $_FILES
			if($cfa['match_type'] == 'file')
			{
				$r = $this->check_files($obj, $field, $cfa, $post_data[$field], $return);
				$return = $r;
				continue;
			}
			
			//caso: il field è un checkbox/radiobutton e non è stato valorizzato
			if(!isset($post_data[$field]) && $cfa['match_type'] == 'bool' && !isset($cfa['default_value'])) { $post_data[$field] = 0; }
			
			//caso: il field non esiste, è richiesto e non esiste un valore di default
			if(!isset($post_data[$field]) && $cfa['is_required'] === true && !isset($cfa['default_value']))
			{
				$index_field = (isset($cfa['index_field'])) ? $cfa['index_field'] : null;
				$cfa['label_name'] .= (is_numeric($key)) ? ' '.($key+1) : '';
				reports::set_report('Il campo <b>$1</b> non &egrave; stato trovato', 'errors', array('$1'=>$cfa['label_name']), $index_field);
				$return = false;
				continue;
			}
			
			//caso: il field non esiste nell'array post o è vuoto ed esiste un valore di default
			if((!isset($post_data[$field]) || $post_data[$field] === '') && isset($cfa['default_value']))
			{
				$post_data[$field] = $cfa['default_value'];
			}
			
			//caso: il field esiste nell'array post, è richiesto ma vuoto
			if(($post_data[$field] === '' || $post_data[$field] == 0 && $cfa['match_type'] == 'bool') && $cfa['is_required'] === true)
			{
				$index_field = (isset($cfa['index_field'])) ? $cfa['index_field'] : null;
				$cfa['label_name'] .= (is_numeric($key)) ? ' '.($key+1) : '';
				if(isset($cfa['empty']))
				{
					reports::set_report($cfa['empty'], 'errors', array('$1'=>$cfa['label_name']), $index_field);
				} else {
					reports::set_report('Il campo <b>$1</b> &egrave; obbligatorio', 'errors', array('$1'=>$cfa['label_name']), $index_field);
				}
				$return = false;
				continue;
			}
			
			//AZIONE: se tutti i controlli precedenti non hanno risposto false, eseguo tipo di controllo impostato per il field
			$params = (isset($cfa['attrs'])) ? $cfa['attrs'] : array();
			$temp_res = checkParams::call($cfa['match_type'], $post_data[$field], $params);
			
			//caso: l'azione precedente ritorna false ed è richiesto
			if($temp_res === false && $cfa['is_required'])
			{
				$index_field = (isset($cfa['index_field'])) ? $cfa['index_field'] : NULL;
				$cfa['label_name'] .= (is_numeric($key)) ? ' '.($key+1) : '';
				reports::set_report($cfa['warning'], 'errors', array('$1'=>$cfa['label_name']), $index_field);
				$return = false;
				continue;
			}
			
			if($callback)
			{
				$cb_cf[$field] = $temp_res;
			} else {
				$this->current_fields[$field] = $temp_res;
			}
			$obj->$field = $temp_res;
		}
		if($callback)
		{
			return array($return, $cb_cf);
		}
		return $return;
	}
	
	private function _callback($obj, $field, $field_attrs, $post_data, $return)
	{
		$r = array();
		if(isset($field_attrs['is_dinamic']) && $field_attrs['is_dinamic'])
		{
			$field = array_keys($post_data);
			$r = $this->check_fields($obj, $field, $field_attrs, $post_data, true, $return);
		} else {
			$r = $this->check_fields($obj, $field, $field_attrs, $post_data, true, $return);
		}
		return $r;
	}
	
	function check_files(&$obj, $field, $field_attrs, $post_data, $cbk_return)
	{
		if(!$cbk_return) { return array(false, ); }
		$return = true;
		$subdir = (isset($field_attrs['subdir'])) ? $field_attrs['subdir'] : '';
		$upl = new Upload($post_data, $subdir);
		//Utils::echoPre($upl->arrayfile);
		$ret = $upl->execute_upload();
		foreach($ret as $key=>$data)
		{
			if(!is_array($data))
			{
				$return = false;				
			}
			$this->current_fields[$field][] = $data;
		}
		return $return;
	}
}