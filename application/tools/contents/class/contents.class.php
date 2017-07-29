<?php
class contents
{
	private static $instance = null;
	private $prm = array();
	protected $response = null;
	protected $tools = null;
	
	public static function get_instance($params)
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c($params);
		}
		return self::$instance;
	}
	
	function __construct($params)
	{
		$this->prm = params::get_instance();
	}
	
	/***CONTROLLO I PARAMETRI GET E POST IN ENTRATA***/
	//questa operazione deve essere pilotata dalla action corrente
	//	NOTA: la action presente tra i parametri get è valida solo per il tool chiamato da url, 
	//		  i tool che vengono eseguiti in modalità schedulazione avranno come action di riferimento quella associata alla schedulazione.
	
	//Che cosa fa in astratto questo metodo? Verifica che i dati necessari per eseguire l'attività definita dalla action siano disponibili.
	//questo essendo un'attività che varia da tool a tool, potrà essere centralizzato limitatamente all'esistenza del metodo, mentre quello che 
	//saranno nella pratica le operazioni eseguite dipenderà da ciò che il tool è programmato/determinato a fare.
	function check_need_params()
	{
		
	}
	
	//Sempre in base alla action definisco il metodo da chiamare che sarà il vero e proprio trigger che avvierà le operazioni che il tool deve eseguire.
	function exec_tool(&$tools)
	{
		$this->tools = $tools;
		$this->set_response();
	}
	
	//al fine di fornire un risultato delle attività del tool, viene eseguito dal metodo exec_tool il metodo privato set_response 
	//che si occupa di definire in modo corretto la risposta che verrà poi restituita al core tramite il metodo pubblico get_respose 
	//chiamato dal service 
	private function set_response()
	{
		$this->response = 'ciao mi chiamo contents è sono un oggetto';
	}
	
	function get_param($name)
	{
		return (isset($this->params[$name])) ? $this->params[$name] : null;
	}
	
	function get_response()
	{
		return $this->response();
	}
}