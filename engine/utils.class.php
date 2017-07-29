<?php
/**
 *
 * Classe utils.
 * Questa classe contiene metodi di uso generale
 * @author Marco Delle Feste <marco.dellefeste@gmail.com>
 * @version 3.0
 * @package libs
 *
 */
class Utils
{
	function __construct()
	{
		//echo 'Nothing to do...';
	}
	/**
	 *
	 * Metodo is_email.
	 * Controlla la correttezza formale di una stringa quando ci si aspetta che sia un indirizzo e-mail.
	 * @param string $value
	 * @return boolean
	 *
	 */
	static function is_email($value)
	{
		$matchString = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";
		return (preg_match($matchString, $value)) ? true : false;
	}
	/**
	 *
	 * Metodo is_img.
	 * Controlla se la stringa passata attraverso il parametro $fileType corrisponde ad uno dei formati possibili per i files di tipo immagine.
	 * Il valore di $fileType deve provenire dall'indice type corrispondente all'elemento dell'array $_FILES che si vuole controllare.
	 * @param string $fileType
	 * @return boolean
	 *
	 */
	static function is_img($fileType)
	{
		$types = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/bmp');
		return (in_array(fileType, $types)) ? true : false;
	}
	/**
	 *
	 * Metodo get_ext.
	 * Data una stringa passata attroverso il parametro $fileName il metodo ritorna una porzione della stringa corrispondente alla parte presente
	 * dopo l'eventuale ultimo punto presente nella stringa.
	 * Se la stringa non contiene punti, il metodo ritorna false.
	 * @param string $fileName
	 * @return string|boolan
	 *
	 */
	static function get_ext($fileName)
	{
		$index = strrpos($fileName, '.');
		return ($index) ? substr($fileName, $index+1) : false;
	}
	/**
	 *
	 * Metodo set_checked.
	 * Confronta il parametro $value con $ref e ritorna una stringa che setta a checked l'input in cui � inserito.
	 * Esempio d'uso: <input type="radio" name="name" value="some_value"<?=Utils::set_check('some_value', $_current_value_checked)?>" />.
	 * Nel caso in cui il confronto dia esito negativo il metodo restituir� null.
	 * Il terzo paramtro (opzionale) permette farsi restituire dal metodo il valore stringa anche nel caso in cui il confronto fallisca.
	 * @param string $value
	 * @param string $ref
	 * @param boolean $need_match
	 * @return string|null
	 *
	 */
	static function set_checked($ref, $value, $need_match=true)
	{
		return (!$need_match || $value == $ref) ? ' checked="checked" ' : NULL;
	}
	/**
	 *
	 * Metodo set_option.
	 * Confronta il parametro $value con $ref e ritorna una stringa che setta a selected l'option in cui � inserito.
	 * Esempio d'uso: <option value="some_value"<?=Utils::set_option('some_value', $_current_value_selected)?>">Some value</option>.
	 * Nel caso in cui il confronto dia esito negativo il metodo restituir� null.
	 * Il terzo paramtro (opzionale) permette di farsi restituire dal metodo il valore stringa anche nel caso in cui il confronto fallisca.
	 * @param string $ref
	 * @param string $value
	 * @param boolean $need_match
	 * @return string|null
	 *
	 */
	static function set_option($ref, $value, $need_match=true)
	{
		return (!$need_match || $value == $ref) ? ' selected="selected" ' : NULL;
	}
	/**
	 *
	 * Metodo get_random_code.
	 * Utile per la generazione di password casuali.
	 * Si può definire la lunghezza della password utilizzando il parametro opzionale $str_length, se questo sarà zero il metodo restituir� false
	 * @param integer $str_length
	 * @return string|false
	 *
	 */
	static function get_random_code($str_length=6, $an=false)
	{
		$str = false;
		$alphanum = (!$an) ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890' : '1234567890';
		$strlen = strlen($alphanum);
		for($i=0; $i< $str_length; $i++)
		{
			$key = rand(0, $strlen);
			$str .= substr($alphanum, $key, 1);
		}
		return $str;
	}
	/**
	 *
	 * Metodo is_leap.
	 * Dato un valore numerico di quattro cifre, il metodo lo considererà come anno e restituirà true se è bisestile o false se non lo �.
	 * @param integer $year
	 * @return boolean
	 *
	 */
	static function is_leap($year)
	{
		return (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) ? true : false;
	}
	/**
	 *
	 * Metodo is_adult.
	 * Metodo che permette il riconoscimento della maggiore età. I primi tre parametri, sono la data da confrontare, il quarto (opzionale)
	 * permette di definire un'età diversa dai diciotto anni.
	 * @param integer $day
	 * @param integer $mounth
	 * @param integer $year
	 * @param integer $match
	 * @return boolean
	 *
	 */
	static function is_adult($day, $mounth, $year, $match=18)
	{
		$age = date('Y') - $year;
		if (date('m') < $mounth) { $age--; }
		else if (date('d') < $day) { $age--; }
		return ($age >= $match) ? true : false;
	}
	/**
	 *
	 * Metodo echoPre.
	 * Dato un array di valori, questo metodo la manda in output preformattandolo.
	 * Passando true come secondo parametro, si avere un output con eventuali entità html già codificate.
	 * @param string $array
	 * @param boolean $parse_html
	 * @return void
	 *
	 */
	static function echoPre($array, $parse_html=false)
	{
		if(!$parse_html)
		{
			echo '<pre>'.print_r($array, true).'</pre>';
		} else {
			echo '<pre>'.htmlentities(print_r($array, true)).'</pre>';
		}
	}
	/** alias di echoPre **/
	static function trace($array, $parse_html=false)
	{
		utils::echoPre($array,$parse_html);
	}
	
	/**
	 *
	 * Metodo escape.
	 * Previene l'interruzione inaspettata di una stringa dovuta alla presenza di apici.
	 * Se come secondo parametro si passata true, il sistema di escpace utilizzerà la funzione mysql_real_escape_string invece del pi� classico addslashes.
	 * @param string $string
	 * @param boolean $use_mysql_escape
	 * @return boolean
	 *
	 */
	static function escape($string, $use_mysql_escape=false)
	{
		$string = stripslashes($string);
		return (!$use_mysql_escape) ? addslashes($string) : mysql_real_escape_string($string);
	}
	/**
	 *
	 * Metodo trim_values
	 * applica la funzione trim sull'arrat passato con il parametro $array.
	 * Utilizzato in associazione alla funzione array_walk per pulire i valori degli array ricevuti in $_POST.
	 *
	 */
	static function trim_values($values)
	{
		array_walk($values, create_function('&$val', 'if(!is_array($val)){$val = trim($val);} else {$val = Utils::trim_values($val);}'));
		return $values;
	}
	/**
	 *
	 * Metodo make_abstract.
	 * Crea un abstract dal testo passato con il parametro $string. Se il testo contiene tag html, il metodo sarà in grado di preservare i link
	 * e la formattazione basilare, cioè i bold, i corsivi ed i sottolineati.
	 * Qualunque altro tipo di tag verrà eliminato.
	 * @param string $string
	 * @param integer $max_length
	 * @param integer $word_wrap
	 * @param string $end_str
	 * @return boolean
	 *
	 */
	static function make_abstract($string, $max_length, $word_wrap=25, $end_str='...')
	{
		return self::get_abstract($string, $max_length, $word_wrap, $end_str);
		$string = strip_tags($string, '<u><em><i><strong><b><a>');
		preg_match_all('/(<([a-z]+)(.*?)>)|(<\/([a-zA-Z]+)>)|(.[^<>]+)/', $string, $array_tags, PREG_PATTERN_ORDER);
		
		$array_string = $array_tags[6];
		$str_len = 0;
		$new_string = '';
		$stop = false;
		$tags_to_close = array();
		foreach($array_string as $key=>$value)
		{
			if($value != '')
			{
				$value_len = strlen($value);
				$str_len += $value_len;
				if($str_len>=$max_length)
				{	$length = $value_len-($str_len-$max_length);
					$value = substr($value, 0, $length);
					$stop = true;
				}
				$new_string .= $value;
			} else {
				$tag = $array_tags[1][$key];
				if($tag != '')
				{
					$tags_to_close[] = $array_tags[2][$key];
				} else {
					$tag = $array_tags[4][$key];
					array_pop($tags_to_close);
				}
				$new_string .= $tag;
			}
			if($stop)
			{
				$reversed = array_reverse($tags_to_close);
				foreach($reversed as $nameTag)
				{
					$new_string .= '</'.$nameTag.'>';
				}
				break;
			}
		}
		return trim($new_string).$end_str;
	}
	
	
	static function get_abstract($string, $max_length, $word_wrap=25, $end_str='...')
	{
		$string = strip_tags($string, '<u><em><i><strong><b><a>');
		$total_length = strlen(strip_tags($string));
		preg_match_all('/(<([a-z]+)(.*?)>)|(<\/([a-zA-Z]+)>)|(.[^<>]+)/', $string, $array_tags, PREG_PATTERN_ORDER);
		
		$array_string = $array_tags[6];
		$str_len = 0;
		$new_string = '';
		$stop = false;
		$tags_to_close = array();
		
		foreach($array_string as $key=>$value)
		{
			if($value != '')
			{
				$value_len = strlen($value);
				$str_len += $value_len;
				if($str_len>=$max_length)
				{
					$length = $value_len-($str_len-$max_length);
					$value = wordwrap($value, $length);
					$value = substr($value, 0, strpos($value, "\n"));
					$stop = true;
				}
				$new_string .= $value;
			} else {
				$tag = $array_tags[1][$key];
				if($tag != '')
				{
					$tags_to_close[] = $array_tags[2][$key];
				} else {
					$tag = $array_tags[4][$key];
					array_pop($tags_to_close);
				}
				$new_string .= ' '.$tag;
			}
			if($stop)
			{
				$reversed = array_reverse($tags_to_close);
				foreach($reversed as $nameTag)
				{
					$new_string .= '</'.$nameTag.'>';
				}
				break;
			}
		}
		if($total_length > $max_length)
		{
			return trim($new_string).$end_str;
		} else {
			return trim($string);
		}
	}
	/**
	 *
	 * Metodo link_email.
	 * Utile nella creazione di testo estratto da database.
	 * Ad esempio se si deve costruire una tabella con dati anagrafici.
	 * Il metodo permette di definire una label per il link, ma non � obbligatorio, se non si indica nulla, la label corrisponder� all'indirizzo e-mail.
	 * E' possibile inoltre passare come terzo parametro il nome di una o pi� classi CSS che possano fornire al link uno stile personalizzato.
	 * @param string $email
	 * @param string $label
	 * @return string $class
	 * @TODO trasformare il terzo parametro in un array che contiene i coppie di nomi e valori di parametri,
	 *  così da permettere il passaggio di un id o attributo rel
	 */
	static function link_email($email, $label='', $class='')
	{
		$class = ($class != '') ? ' class="'.$class.'"' : $class;
		$label = ($label == '') ? $email : $label;
		return '<a'.$class.' href="mailto:'.$email.'">'.$label.'</a>';
	}
	/**
	 * Metodo format_date.
	 * Ritorna la data passata in diversi formati.
	 * Il parametro $date accetta anche la stringa "now", se passata il metodo restituisce il momento attuale corrispondente alla funzione php time().
	 * Quelli attualmente definiti sono:
	 * it-short: GG/MM/AAAA
	 * en-short: MM/GG/AAAA
	 * it-short-wtime: GG/MM/AAAA HH:mm:ss
	 * en-short-wtime: MM/GG/AAAA hh:mm:ss AM/PM
	 * it-long: giorno GG mese AAAA
	 * en-long: month GG AAAA
	 * it-long-wtime: giorno GG mese AAAA alle ore HH:mm:ss
	 * en-long-wtime: month GG, AAAA at hh:mm:ss AM/PM
	 * Se $format è vuoto il formato di defaut è: MM-GG-AAAA  hh:mm:ss PM/AM
	 * @param string $date
	 * @param string $format
	 *
	 */
	static function format_date($date, $format=NULL)
	{
		global $strings;
		$d = ($date == 'now') ? time() : strtotime($date);
		if(!is_numeric($d)) {return ($date=='') ? '0000-00-00 00:00:00' : $date; }
		$f_date = NULL;
		switch($format)
		{
			case 'it-short':
				$f_date = date('j/m/Y', $d);
				break;
			case 'en-short':
				$f_date = date('m/j/Y', $d);
				break;
			case 'it-short-wtime':
				$f_date = date('j/m/Y H:i:s', $d);
				break;
			case 'en-short-wtime':
				$f_date = date('m/j/Y h:i:s', $d);
				break;
			case 'en-short-wtime-APM':
				$f_date = date('m/j/Y h:i:s A', $d);
				break;
			case 'it-long':
				$c_date = date('w,j,m,Y', $d);
				list($gs,$g,$m,$a) = explode(',', $c_date);
				$f_date = $strings['week'][$gs].' '.$g.' '.$strings['month'][$m].' '.$a;
				break;
			case 'en-long':
				$f_date = date('F j Y', $d);
				break;
			case 'it-long-wtime':
				$c_date = date('w,j,n,Y,H,i,s', $d);
				list($gs,$g,$m,$a,$h,$mi,$s) = explode(',', $c_date);
				$f_date = $strings['week'][$gs].' '.$g.' '.$strings['month'][$m].' '.$a.', alle ore '.$h.':'.$mi.':'.$s;
				break;
			case 'en-long-wtime':
				$f_date = date('F j Y \a\t  h:i:s A', $d);
				break;
			case 'mysql-date':
				$f_date = date('Y-m-j h:i:s', $d);
				break;
			default:
				$f_date = date('m-j-Y h:i:s A', $d);
				break;
		}
		return $f_date;
	}
	
	static function get_new_token($length, $f='', $s='')
	{
		$token = ($f != '') ? $f.'-' : '';
		for($i=1; $i<=$length; $i++)
		{
			$token .= rand(0, 9);
		}
		$token .= ($s != '') ? '-'.$s : '';
		return $token;
	}
	
	static function bytes_to_size ($bytes)
	{
		$symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$exp = ($bytes > 0) ? floor(log($bytes) / log(1024)) : 0;
		return sprintf('%.2f ' . $symbol[$exp], ($bytes / pow(1024, $exp)));
	}
	
	static function replace_array($array1, $array2)
	{
		$res_array = array();
		foreach($array1 as $key=>$value)
		{
			if(isset($array2[$key]))
			{
				$res_array[$key] = $array2[$key];
			}
		}
		return $res_array;
	}
	
	static function array_keys_recursive($array=array())
	{
		if(!is_array($array)) { return false; }
		global $output;
		$output = array();
		array_walk_recursive($array, create_function('$val, $key', 'global $output; array_push($output, $key);'));
		return $output;
	}
	
	static function array_value_recursive($array=array())
	{
		if(!is_array($array)) { return false; }
		global $output;
		$output = array();
		array_walk_recursive($array, create_function('$val, $key', 'global $output; array_push($output, $val);'));
		return $output;
	}
	
	static function array_shuffle_assoc($array, $preserve_key=true)
	{
		$keys = array_keys($array);
		$values = array_values($array);
		$new_array = array();
		if(!$preserve_key)
		{
			shuffle($values);
			foreach($values as $ind => $value)
			{
				$new_array[$keys[$ind]] = $value;
			}
		} else {
			shuffle($keys);
			foreach($keys as $ind => $value)
			{
				$new_array[$value] = $array[$value];
			}
		}
		return $new_array;
	}
	
	static function implode_assoc($glue1='', $glue2='', $pieces=array())
	{
		$tmp = array();
		foreach($pieces as $key=>$value)
		{
			$tmp[] = $key.$glue1.Utils::escape($value);
		}
		return implode($glue2, $tmp);
	}
	
	static function check_access($eval_access, $user_access_level)
	{
		if($eval_access == $user_access_level) return true;
		if($eval_access < ACCESS_RANGE && $eval_access > $user_access_level) return false;
		if($eval_access != GLOBAL_ACCESS_LEVEL)
		{
			$global_user_access_level = $user_access_level*ACCESS_RANGE;
			return ($eval_access <= $global_user_access_level) ? true : false;
		}
		return true;
	}
	
	static function set_return_data($return_post_data)
	{
		/*to do:
		* serializzare anche i parametri get?
		*/
		$prm = params::get_instance();
		if($return_post_data)
		{
			$_SESSION['post-return'] = serialize($prm->http_data['_POST']);
		}
		$_SESSION['alert-return'] = reports::get_report('errors');
		$_SESSION['success-return'] = reports::get_report('success');
	}
	
	static function get_return_data()
	{
		$prm = params::get_instance();
		if(isset($_SESSION['post-return']) && empty($prm->http_data['_POST']))
		{
			$http_data = $prm->http_data;
			$http_data['_POST'] = unserialize($_SESSION['post-return']);
			$prm->http_data = $http_data;
		}
		if(isset($_SESSION['alert-return']))
		{
			reports::set_report($_SESSION['alert-return'], 'errors');
		}
		if(isset($_SESSION['success-return']))
		{
			reports::set_report($_SESSION['success-return'], 'success');
		}
		unset($_SESSION['post-return'], $_SESSION['alert-return'],$_SESSION['success-return']);
	}
	
	static function redirect($url=false, $return_post_data=true)
	{
		$prm = params::get_instance();
		self::set_return_data($return_post_data);
		if(!isset($_GET['destination'])) {
			$url = (!$url) ? $prm->http_data['_REFERER'] : $url;
			
		} else {
			$url = $prm->http_data['_GET']['destination'];
		}
		$url = (substr($url, 0, 1) == '/') ? $prm->site_url.substr($url, 1, strlen($url)) : $url;
		$url = (substr($url, 0, 4) != 'http' && substr($url, 0, 5) != 'https') ? $prm->site_url.$url : $url;
		header('Location: '.$url);
		exit();
	}
	
	static function refresh()
	{
		$prm = params::get_instance();
		$_SESSION['alert-return'] = reports::get_msg_errors();
		$_SESSION['success-return'] = reports::get_msg_success();
		header('Refresh:0;url='.$prm->current_url);
		exit();
	}
	
	static function set_header_json()
	{
		header("HTTP/1.0 200 OK");
		header('Content-type: text/json; charset=utf-8');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Pragma: no-cache");
	}
	
	static function get_($name, $data, $obj=false)
	{
		if(!$obj)
		{
			return (isset($data[$name])) ? $data[$name] : null;
		} else {
			return (isset($data->$name)) ? $data->$name : null;
		}
	}
}