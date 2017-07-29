<?php
defined('INDEX_UNLOCK') or die('ACCESS DENIED');
require('dbPool.class.php');
require('query.class.php');
/**
*
* Classe DbInt
* Estende la classe DbPool e si occupa di fornire metodi pubblici che predispongono la base dati richiesta in un formato adattabile al parsing html.
* @see DbPool
* @author Marco Delle Feste <marco.dellefeste@gmail.com>
* @version 1.0
* @package DbPool
*
*/
class dbInt extends dbPool
{
	/**
	* Metodo exec_select.
	* Da utilizzare per eseguire query di selezione.
	* Questo metodo permette di effettuare la richiesta di informazioni sui campi interessati dalla query passata.
	* Nel caso la query ritorna un insieme vuoto il metodo risponderà con false
	* in alternativa risponderà con un array composto come segue: Array
	*															 (
	*															 		[property] => Array() | false,
    *																	[num_rows] => int,
    *																	[row] => Array()
	*															 )
	* L'indice property (se richiesto) conterrà i dati riguardanti i campi interessati dalla query.
	* L'indice num_rows indicherà la quantità di record interessati dalla query il suo valore potrà variare da 1 ad n
	* L'indice row sarà un array associativo, dove l'indici primari saranno numerici-incrementali se la query interessa più righe,
	* in alternativa gli indici saranno gli stessi nomi dei campi.
	* @param string $query
	* @param string $get_rows 
	* @param string $name_key
	* @param boolean $info_table
	* @return array|false
	*/
	public function exec_select($query, $get_rows=null, $name_key=null, $info_table=false)
	{
		//echo 'OPTIMIZE TABLE `$table`';
		DbPool::connection();
		$result = DbPool::set_query($query) or self::get_error($query);
		$num_rows = DbPool::get_n_rows($result);
		$data_field = ($info_table) ? self::exec_infotab('', $result) : $info_table;
		$response['property'] = $data_field;
		$response['num_rows'] = $num_rows;
		if(!$response['row'] = self::elab_rows($num_rows, $get_rows, $name_key, $result))
		{
			return false;
		}
		return $response;
	}
	/**
	* Metodo exec_limit_select.
	* Da utilizzare per eseguire query di selezione che prevede la pagina dei risultati.
	* I parametri del metodo solo gli stessi di exec_select.
	* La differenza tra i due metodi sta nel fatto che exec_limit_select impone obbligatoriamente un LIMIT alla query passata.
	* @return array|false
	*/
	/*public function exec_limit_select($query, $get_rows=null, $name_key=null, $info_table=false)
	{
		DbPool::connection();
		$total = self::get_count($query);
		die($total);
		$result = DbPool::set_query($query) or self::get_error($query);
		$num_rows = DbPool::get_n_rows($result);
		$data_field = ($info_table) ? self::exec_infotab('', $result) : $info_table;
		$response['property'] = $data_field;
		$response['num_rows'] = $num_rows;
		if(!$response['row'] = self::elab_rows($num_rows, $get_rows, $name_key, $result))
		{
			return false;
		}
		return $response;
	}*/
	/**
	*
	* Metodo get_result.
	* Eesegue una query select utilizzando i paramtri passati.
	* @param string $field
	* @param string $table
	* @param string $match_field
	* @param string $value
	*
	*/
	public function get_result($field, $table, $match_field, $value)
	{
		DbPool::connection();
		$query = "SELECT ".$field." FROM ".$table." WHERE ".$match_field." = '".$value."'";
		$result = DbPool::set_query($query) or self::get_error($query);
		$row = DbPool::get_assoc($result);
		return ($row) ? $row[$field] : false;
	}
	/**
	*
	* Metoro exec_update.
	* Eesegue qualsiasi query diverse dalle select.
	* Oltre al primo parametro attraverso cui passare la query, questo metodo è provviso da altri due parametri opzionali 
	* ($ii, $afcrow), che se valorizzati come true ritorneranno rispettivamente l'insert id e il valore affected rows
	* @param string $query
	* @param boolean $ii
	* @param boolean $afcrow
	*
	*/
	public function exec_update($query, $ii=false, $afcrow=false)
	{
		DbPool::connection();
		$result = DbPool::set_query($query) or self::get_error($query);
		$response['success'] = $result;
		$response['insid']	 = ($ii)	 ? DbPool::get_ii() : false;
		$response['aftrows'] = ($afcrow) ? DbPool::get_affected_rows() : false;
		return $response;
	}
	/**
	* ESEGUE UNA QUERY DI TIPO COUNT.
	*/
	public function exec_count($campo, $table, $where=1, $limit=1)
	{
		$query = "SELECT COUNT(".$campo.") AS NumId FROM ".$table." WHERE ".$where." LIMIT ".$limit."";
		$result = self::exec_select($query);
		if($result[0])
		{
			$result = $result['row']['NumId'];
		}
		return $result;
	}
	/**
	* METODO PUBBLICO PER exec_infotab
	* aggiunto il: 20/09/2011
	*/
	public function get_infotab($query, $result=false)
	{
		return self::exec_infotab($query, result);
	}
	/**
	* ESEGUE UNA QUERY SPECIFICA PER ESTRARRE LE PROPRIETA' DELLE COLONNE DELLA TABELLA INDICATA
	*/
	public function get_count($query)
	{
		$query = preg_replace("/^(LIMIT)(([0-9, ]+)|([0-9]+))$/", '', $query);
		$query = preg_replace("/^(SELECT)([a-zA-Z0-9_\-,*\.\(=\) ]+)(FROM)/i", 'SELECT COUNT(*) AS numId FROM', $query);
		//echo $query;
		$result = DbPool::set_query($query) or self::get_error($query);
		$row = DbPool::get_assoc($result);
		return $row['numId'];
	}
	
	public function debug_query($query, $return_no=false, $report=true)
	{
		DbPool::set_query($query);
		$num = mysql_errno();
		switch($num)
		{
			case 0:
				return true;
			break; 
			default:
				if($report)
				reports::set_report(mysql_error());
				return ($return_no) ? mysql_errno() : false;
			break;
		}
	}
	
	private function exec_infotab($query, $result=false)
	{
		$info_table = false;
		if(!$result && !$query) { return false; }
		if(!$result && $query)
		{
			DbPool::connection();
			$result = DbPool::set_query($query);
		}
		$num_field = DbPool::get_f_num($result);
		for($i=0; $i < $num_field; $i++)
		{ 
			$field_prop = DbPool::get_f_field($result);
			$field_len = DbPool::get_f_len($result, $i); 
			$info_table[$field_prop->name] = array('type' => $field_prop->type, 'primary' => $field_prop->primary_key, 'not_null' => $field_prop->not_null, 'numeric' => $field_prop->numeric, 'table' => $field_prop->table, 'field_len' => $field_len);
		}
		return $info_table;
	}
	
	private function elab_rows($num_rows, $get_rows, $name_key, $result)
	{
		switch($num_rows)
		{
			case 0:
				return false;
			case 1:
				if($get_rows == 'one-or-more')
				{
					if(is_null($name_key))
					{
						while($row = DbPool::get_assoc($result))
						{
							$response[] = $row;
						}
					} else {
						while($row = DbPool::get_assoc($result))
						{
							$response[$row[$name_key]] = $row;
						}
					}
				} else {
					$response = DbPool::get_assoc($result);
				}
			break;
			default:
				if(is_null($name_key))
				{
					while($row = DbPool::get_assoc($result))
					{
						$response[] = $row;
					}
				} else {
					while($row = DbPool::get_assoc($result))
					{
						$response[$row[$name_key]] = $row;
					}
				}
			break;
		}
		return $response;
	}
	
	private function get_error($query)
	{
		$msg = 'La query:<br /><b>'.$query.'</b><br />ha generato un errore.<br />Di seguito il messaggio riportato dal database.<br /><br /><i>'.mysql_error().'</i> numero dell\'errore '.mysql_errno();
		if(DEBUG)
		{
			die($msg);
		} else {
			mail(EMAIL_ADMIN, COMPACTSITENAME.' - query error', $msg);
		}
	}
}