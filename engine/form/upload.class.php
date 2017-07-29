<?php
class upload
{
	var $arrayfile = array();
	var $testo = array();
	var $new_name = '';
	var $prm = array();
	var $subdir = '';
	var $directory_upload = '';
	
	function upload($files, $subdir)
	{
		$this->prm = params::get_instance();
		//Utils::echoPre($this->prm);
		$this->subdir = $subdir;
		$this->arrayfile = $files;
		if(!is_array($this -> arrayfile['name'])) { $this->convert_to_array(); }
	}
	
	function convert_to_array()
	{
		$temparray = $this -> arrayfile;
		foreach($temparray as $key => $value)
		{
			$this -> arrayfile[$key] = array();
			$this -> arrayfile[$key][] = $value;
		}
	}
	
	function execute_upload()
	{
		if($this->check_dir() && $this->num_file() > 0)
		{	
			return $this->caricafile();
		}
	}
	
	private function check_dir()
	{
		$dirup = $this->prm->folders['upload'];
		$dirup = ($this->subdir == '') ? $dirup : $dirup.SP.$this->subdir.SP;
		$this->directory_upload = $dirup;
		if(!is_dir($dirup))
		{
			$mk_dir = mkdir($dirup, 775);
			if(!$mk_dir)
			{
				Reports::set_report('Errore nella creazione della directory'.$dirup);
				return false;
			}
		}
		return true;
	}
	
	function num_file()
	{
		$numfile = count($this -> arrayfile['tmp_name']);
		if($numfile == 0)
		{
			Reports::set_report('Nessun file da caricare');
			return false;
		}
		$this->check_errors_file();
		return count($this -> arrayfile['tmp_name']);
	}
	
	function check_errors_file()
	{
		$numero_file = count($this -> arrayfile['tmp_name']);
		for($i=0; $i<$numero_file; $i++)
		{
			if($this -> arrayfile['error'][$i] != 0)
			{
				$this -> unsetimage($i);
			}
		}
	}
	
	function unsetimage($i)
	{
		unset( $this -> arrayfile['name'][$i]);
		unset( $this -> arrayfile['type'][$i]);
		unset( $this -> arrayfile['size'][$i]);
		unset( $this -> arrayfile['error'][$i]);
		unset( $this -> arrayfile['tmp_name'][$i]);
	}
	
	function caricafile()
	{
		$return = array();
		foreach($this->arrayfile['name'] as $chiave=>$valore)
		{
			if($this->execute_function($chiave))
			{
				$return[$chiave]['data']['dir'] = str_replace($this->prm['document_root'], '', $this->directory_upload);
				$return[$chiave]['data']['filename'] = $this->new_name;
				$return[$chiave]['data']['size'] = $this->arrayfile['size'][$chiave];
				$return[$chiave]['data']['type'] = $this->arrayfile['type'][$chiave];
			} else {
				$return[$chiave]['data'] = false;
			}
		}
		return $return;
	}
	function execute_function($chiave)
	{
		$res = $this -> check_up($chiave);
		$res = ($res) ? $this -> check_size($chiave) : false;
		$res = ($res) ? $this -> check_type($chiave) : false;
		$res = ($res) ? $this -> check_write($chiave) : false;
		$res = ($res) ? $this -> move_file($chiave) : false;
		return $res;
	}
	
	function check_up($chiave)
	{
		if (!is_uploaded_file( $this -> arrayfile['tmp_name'][$chiave]))
		{
			Reports::set_report('Errore nel trasferimento del file: '.$this -> arrayfile['name'][$chiave]);
			return false;
		}
		return true;
	}
	function check_size($chiave)
	{
		if ($this -> arrayfile['size'][$chiave] > MAX_FILE_SIZE)
		{
			Reports::set_report('La dimensione del file inviato &egrave; superiore a quella consentita');
			return false;
		}
		return true;
	}
	function check_type($chiave)
	{
		if(!in_array($this->arrayfile['type'][$chiave], explode('|', ACCEPT_FILE_TYPES)))
		{
			Reports::set_report('Il tipo del file inviato '.$valore.' non &egrave; permesso.');
			return false;
		}
		return true;
	}
	
	function check_write($chiave)
	{
		$checkname = strtolower($this -> arrayfile['name'][$chiave]);
		$checkname = str_replace(' ', '-', $checkname);
		
		$dotpos = strrpos($checkname, '.');
		$ext = substr($checkname, $dotpos);
		$strlen = strlen($checkname)-(strlen($checkname)-$dotpos);
		$simplename = substr($checkname, 0, $strlen);
		$simplename = (strlen($simplename) >200) ? substr($simplename, 0, 200) : $simplename;
		
		$checkname = $simplename.$ext;
		
		if(!OVERWRITE_FILE)
		{	
			$c = 1;
			while(file_exists($this->directory_upload.$checkname) && $c <= 100)
			{
				$checkname = $simplename.'_'.$c.$ext;
				$c++;
			}
			if($c == 101)
			{
				Reports::set_report('Esiste gi&agrave; un file con questo nome: '.$this -> arrayfile['name'][$chiave]);
				return false;
			}
		}
		
		$this->new_name = $checkname;
		return true;
	}
	function move_file($chiave)
	{	
		if(!move_uploaded_file($this -> arrayfile['tmp_name'][$chiave], $this->directory_upload.$this->new_name))
		{
			Reports::set_report('Errore nel trasferimento del file '.$this -> arrayfile['name'][$chiave].' directory di destinazione: '.$this->directory_upload);
			return false;
		}
		return true; 
	}
}
?>
