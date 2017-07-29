<?php
class wsInterface
{
	
	private $ch = null;
	
	function __construct()
	{
		$this->ch = curl_init();
		
	}
	
	
	public function call($url, $custom_request, $fields)
	{
		$this->set_base_opt($custom_request);		
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);
		$this->set_url($url);
		$ret = curl_exec($this->ch);
		$info = curl_getinfo($this->ch);	
		$res = json_decode($ret);
		$this->close();
		return array('info'=>$info,'response'=>$res,'url'=>$url);
	}
	
	private function set_url($url)
	{		
		curl_setopt($this->ch, CURLOPT_URL, $url);
	}
					
	private function set_base_opt($custom_request='GET')
	{
		curl_setopt($this->ch, CURLOPT_USERPWD, 'garnier:ghiL*8Uh');
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $custom_request);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_POST, 1);
	}					
						
	private function close()
	{
		curl_close($this->ch);
	}
}