<?php 
/* 
   Blowfish e PHP - Fabio Donatantonio 2010

   Sintassi:
   $blowfish = new Crittografia($chiave);
   $txt_cifrato = $blowfish->cifratura($txt);
   $txt_chiaro = $blowfish->decifratura($txt_cifrato);
*/

// Richiamo il codice che implementa Blowfish
require_once('blowfish.php');

class Crittografia{
	var $chiave;
	var $blowfish;

	// Costruttore della classe Crittografia - prende in input la chiave in chiaro
	function Crittografia($stringa){
		$this->chiave = $stringa;
	}

	// Funzione di codifica di un testo in chiaro
	function cifratura($stringa){   
		$this->blowfish = new Horde_Cipher_blowfish;
 
		$encrypt = '';

    
		$mod = strlen($stringa) % 8;

    
		if ($mod > 0) {
        
			$stringa .= str_repeat("\0", 8 - $mod);

		}

    
		foreach (str_split($stringa, 8 ) as $chunk){
       
			$encrypt .= $this->blowfish->encryptBlock($chunk, $this->getChiave());
   
		}
    
		return base64_encode($encrypt);
	}

	// Funzione di decodifica di un testo cifrato
	function decifratura($stringa){
		$this->blowfish = new Horde_Cipher_blowfish;
 
		$decrypt = '';
    
		$data = base64_decode($stringa);

    
		foreach (str_split($data, 8 ) as $chunk){
        
			$decrypt .= $this->blowfish->decryptBlock($chunk, $this->getChiave());
    
		}
    
		return trim($decrypt);
	}

	// Funzione per restituzione della chiave
	function getChiave(){
		return $this->chiave;
	}
}