<?php
defined('INDEX_UNLOCK') or die('ACCESS DENIED');
/**
*
* Classe DbPool.
* Questa classe contiene i metodi per l'interazione con un database mysql
* @author Marco Delle Feste <marco.dellefeste@gmail.com>
* @version 1.0
* @package DbPool
*
*/
class dbPool
{
	static $connection = NULL;

	/**
	* Metodo connection.
	* Apre la connessione al database passando ai parametri hostname, username e password le costanti DBHOST, DBUSER, DBPASS.
	* @return void
	*/
	final function connection()
	{
		if(is_null(self::$connection))
		{
			self::$connection = mysql_connect(DBHOST, DBUSER, DBPASS) or die();
			mysql_set_charset('utf8',self::$connection);
			self::db_select();
		}
	}
	private function db_select()
	{
		mysql_select_db(DBNAME);
	}
	final function set_query($q)
	{
		return mysql_query($q);
	}
	final function get_assoc($r)
	{
		return mysql_fetch_assoc($r);
	}
	final function get_obj($r, $obj)
	{
		return mysql_fetch_object($r, $obj);
	}
	final function get_f_field($r)
	{
		return mysql_fetch_field($r);
	}
	final function get_f_len($r, $i)
	{
		return mysql_field_len($r, $i);
	}
	final function get_f_num($r)
	{
		return mysql_num_fields($r);
	}
	final function get_n_rows($r)
	{
		return mysql_num_rows($r);
	}
	final function get_affected_rows()
	{
		return mysql_affected_rows();
	}
	final function get_ii()
	{
		return mysql_insert_id();
	}
}
?>
