<?php
defined('INDEX_UNLOCK') or die('ACCESS DENIED');
class query
{
	private static $counter = 0;
	private static $action_type = null;
	private static $field_method = null;
	private static $query_store = array();
	private static $all_fields = false;
	
	public function __construct() { }
	
	public function new_query($table, $type='select')
	{
		$token = md5(time().self::$counter);
		self::$counter++;
		self::$query_store[$token]['action'] = self::get_action($type, $table);  
		return $token;
	}
	
	private function get_action($type, $table)
	{
		$type = strtoupper($type); 
		switch($type)
		{
			case 'SELECT':
				self::$action_type = 'select';
				self::$field_method = 'linear';
				return 'SELECT {@fields} FROM `'.$table.'` ';
			break;
			case 'INSERT':
				self::$action_type = 'insert';
				self::$field_method = 'linear';
				return 'INSERT INTO `'.$table.'` ';
			break;
			case 'UPDATE':
				self::$action_type = 'update';
				self::$field_method = 'intersect';
				return 'UPDATE `'.$table.'` SET ';
			break;
			case 'DELETE':
				self::$action_type = 'delete';
				return 'DELETE FROM `'.$table.'` ';
			break;
			default:
				self::notify_error(3, $type);
			break;
		}
	}
	
	public function set_fields($token, $fields)
	{
		$debug = debug_backtrace();
		//echo '<pre>'.print_r($debug, true).'</pre>';
		if(!is_array($fields)) self::notify_error(0, $debug);
		if(isset(self::$query_store[$token]))
		{
			switch(self::$field_method)
			{
				case 'linear':
					self::set_fields_linear($token, $fields, $debug);
				break;
				case 'intersect':
					self::set_fields_intersect($token, $fields, $debug);
				break;
			}
		} else {
			self::notify_error(1, $debug);
		}
	}
	
	private function set_fields_linear($token, $fields, $debug)
	{
		if(self::$all_fields) return;
		$str_fields = '';
		foreach($fields as $field)
		{
			if(preg_match('/^([a-zA-Z0-9\-_ ]*)(\.)([a-zA-Z0-9\-_ ]*)$/', $field, $af))
			{
				$str_fields .= '`'.$af[1].'`.`'.$af[3].'`, ';
			} elseif(preg_match('/^([a-zA-Z0-9\-_ ]*)(\.)(\*)$/', $field, $af))
			{
				$str_fields .= '`'.$af[1].'`.*, ';
			} elseif($field != '*')
			{
				$str_fields .= '`'.$field.'`, ';
			} else {
				self::$query_store[$token]['fields'] = '* ';
				self::$all_fields = true;
				return;
			}
		}
		if(isset(self::$query_store[$token]['fields']))
		{
			self::$query_store[$token]['fields'] = self::$query_store[$token]['fields'].$str_fields;
		} else {
			self::$query_store[$token]['fields'] = $str_fields;
		}
	}
	
	private function set_fields_intersect($token, $fields, $debug)
	{
		$str_fields = '';
		foreach($fields as $field=>$value)
		{
			if(preg_match('/^([a-zA-Z0-9\-_ ]*)(\.)([a-zA-Z0-9\-_ ]*)$/', $field, $af))
			{
				$field = '`'.$af[1].'`.`'.$af[3].'`';
			} elseif(preg_match('/^([a-zA-Z0-9\-_ ]*)$/', $field, $af))
			{
				$field = '`'.$af[1].'`';
			} else {
				continue;
			}
			$str_fields .= $field.' = \''.$value.'\', ';
		}
		if(isset(self::$query_store[$token]['fields']))
		{
			self::$query_store[$token]['fields'] = self::$query_store[$token]['fields'].$str_fields;
		} else {
			self::$query_store[$token]['fields'] = $str_fields;
		}
	}
	
	public function set_values($token, $values)
	{
		$debug = debug_backtrace();
		//echo '<pre>'.print_r($debug, true).'</pre>';
		if(!is_array($values)) self::notify_error(0, $debug);
		if(isset(self::$query_store[$token]))
		{
			$str_values = '';
			foreach($values as $value)
			{
				if(is_null($value))
				{
					$str_values .= "NULL, ";
				} else {
					$str_values .= "'".trim($value)."', ";
				}
			}
			if(isset(self::$query_store[$token]['values']))
			{
				self::$query_store[$token]['values'] = self::$query_store[$token]['values'].$str_values;
			} else {
				self::$query_store[$token]['values'] = $str_values;
			}
		} else {
			self::notify_error(1, $debug);
		}
	}
	
	public function set_condition($token, $where, $operator='AND', $id_group=null)
	{
		$debug = debug_backtrace();
		if(isset(self::$query_store[$token]))
		{
			if(!is_array($where))
			{
				self::notify_error(2, $debug);
			}
			$base_where = strtoupper($operator);
			if(preg_match('/^([a-zA-Z0-9\-_ ]*)(\.)([a-zA-Z0-9\-_ ]*)$/', $where[0], $af))
			{
				$field_left = ' `'.$af[1].'`.`'.$af[3].'` ';
			} else {
				$field_left = ' `'.$where[0].'` ';
			}
			$condition = strtoupper($where[1]);
			switch($condition)
			{
				case '=':
				case '>':
				case '>=':
				case '<':
				case '<=':
				case '!=':
				case 'NOT LIKE':
				case 'REGEXP':
				case 'NOT REGEXP':
				case 'IS NULL':
				case 'IS NOT NULL':
				case 'LIKE':
					$temp_where = $field_left.$condition.' \''.$where[2].'\'';
				break;
				case '%LIKE':
					$temp_where = $field_left.$condition.' \'%'.$where[2].'\'%';
				break;
				case 'IN':
					$temp_where = $field_left.'IN (\''.implode('\',\'', $where[2]).'\')';
				break;
			}
			if(!is_null($id_group))
			{
				if(isset(self::$query_store[$token]['where'][$id_group]))
				{
					self::$query_store[$token]['where'][$id_group][] = $base_where.$temp_where;
				} else {
					self::$query_store[$token]['where'][$id_group]['base_where'] = $base_where;
					self::$query_store[$token]['where'][$id_group][] = $temp_where;
				}
			} else {
				if(isset(self::$query_store[$token]['where']))
				{
					self::$query_store[$token]['where'][] = $base_where.$temp_where;
				} else {
					self::$query_store[$token]['where'][] = $temp_where;
				}
			}
		} else {
			self::notify_error(1, $debug);
		}
	}
	
	public function set_join($token, $tables, $match_fields, $type='inner')
	{
		if(isset(self::$query_store[$token]))
		{
			$type = strtoupper($type);
			$current_join  = $type.' JOIN `'.$tables[1].'` ON ( `'.$tables[0].'`.`'.$match_fields[0].'` = `'.$tables[1].'`.`'.$match_fields[1].'` ) ';
			self::$query_store[$token]['join'][] = $current_join;  
		} else {
			self::notify_error(1, debug_backtrace);
		}
	}
	
	public function set_order($token, $field, $direction='ASC')
	{
		if(isset(self::$query_store[$token]))
		{
			if(preg_match('/^([a-zA-Z0-9\-_ ]*)(\.)([a-zA-Z0-9\-_ ]*)$/', $field, $af))
			{
				$field = ' `'.$af[1].'`.`'.$af[3].'` ';
			} else {
				$field = ' `'.$field.'` ';
			}
			$direction = strtoupper($direction);
			if(isset(self::$query_store[$token]['order'][$direction]))
			{
				self::$query_store[$token]['order'][$direction] .= ','.$field;
			} else {
				self::$query_store[$token]['order'][$direction] = $field;
			}
		} else {
			self::notify_error(1, debug_backtrace);
		}
			
	}
	
	public function set_limit($token, $offset=0, $row_count=null)
	{
		if(isset(self::$query_store[$token]))
		{
			if(!is_null($row_count))
			{
				self::$query_store[$token]['limit'] = array('offset'=>$offset, 'row_count'=>$row_count);
			}
		} else {
			self::notify_error(1, debug_backtrace);
		}
	}
	
	public function set_group($token, $table, $field)
	{
		if(isset(self::$query_store[$token]))
		{
			self::$query_store[$token]['groupby'] = array('table'=>$table, 'offset'=>$field);
		} else {
			self::notify_error(1, debug_backtrace);
		}
	}
	
	public function get_query($token)
	{
		$query = false;
		if(isset(self::$query_store[$token]))
		{
			$aq = self::$query_store[$token];
			switch(self::$action_type)
			{
				case 'select':
					$query = self::get_base($aq['action'], $aq['fields']);
					$query .= (isset($aq['join'])) ? self::get_join($aq['join']) : '';
					$query .= (isset($aq['where'])) ? self::get_where($aq['where']) : '';
					$query .= (isset($aq['order'])) ? self::get_order($aq['order']) : '';
					$query .= (isset($aq['limit'])) ? self::get_limit($aq['limit']) : '';
				break;
				case 'insert':
					$query = $aq['action'].'('.self::strip_comma($aq['fields']).') VALUES ( '.self::strip_comma($aq['values']).')';
				break;
				case 'update':
					$query = $aq['action'];
					$query .= self::strip_comma($aq['fields']).' ';
					$query .= self::get_where($aq['where']);
					if(isset($aq['limit']))
					{
						$query .= self::get_limit($aq['limit']);
					}
				break;
				case 'delete':
					$query = $aq['action'];
					$query .= self::get_where($aq['where']);
					if(isset($aq['limit']))
					{
						$query .= self::get_limit($aq['limit']);
					}
				break;
			}
		} else {
			self::notify_error(1, debug_backtrace);
		}
		return $query;
	}
	
	private function get_base($action, $fields)
	{
		$fields = self::strip_comma($fields);
		return str_replace('{@fields}', $fields, $action);
	}
	
	private function get_join($join)
	{
		$str = '';
		foreach($join as $part)
		{
			$str .= $part;
		}
		return $str;
	}
	
	private function get_where($where)
	{
		$str = 'WHERE ';
		foreach($where as $key=>$value)
		{
			if(is_array($value))
			{
				$str .= ' '.$value['base_where'];
				unset($value['base_where']);
				$str .= ' ( '.implode(' ', $value).' ) ';
			} else {
				$str .= ' '.$value;
			}
		}
		return $str;
	}
	
	private function get_order($order)
	{
		$str = ' ORDER BY ';
		if(isset($order['ASC']))
		{
			$str .= $order['ASC'].' ASC ';
		}
		if(isset($order['DESC']))
		{
			$str .= $order['DESC'].' DESC ';
		}
		return $str;
	}
	
	private function get_limit($limit)
	{
		$str = 'LIMIT '.$limit['offset'];
		$str .= (!is_null($limit['row_count'])) ? ', '.$limit['row_count'] : '';
		return $str;
	}
	
	private function notify_error($case, $debug)
	{
		switch($case)
		{
			case 0:
				$message = 'Error: first param for method <b>'.$debug[0]['function'].'</b> called in the file: <b>'.$debug[0]['file'].'</b> at line: <b>'.$debug[0]['line'].'</b> is not an array';
			break;
			case 1:
				$message = 'The query request in the file: <b>'.$debug[0]['file'].'</b> at line: <b>'.$debug[0]['line'].'</b> do not exist';
			break;
			case 2:
				$message = '<b>Fatal error</b>: argument 2 for <b>'.$debug[0]['function'].'</b> called in: <b>'.$debug[0]['file'].'</b> at line: <b>'.$debug[0]['line'].'</b> must be an array';
			break;
			case 3:
				$message = '<b>Fatal error</b>: the type of query <b>&ldquo;'.$debug.'&rdquo;</b> is out of range';
			break;
		}
		die($message);
	}
	
	private function strip_comma($string)
	{
		return preg_replace('/^(.*?)(, )$/', '$1', $string);
	}
	
	public function get_stored_query($token=null)
	{
		return (!is_null($token) && isset(self::$query_store[$token])) ? self::$query_store[$token] : self::$query_store;
	}
}