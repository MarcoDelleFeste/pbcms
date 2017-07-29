<?php
include('htmlMimeMail.php');
class Mail
{
	var $prm = null;
	var $cs;
	var $html = '';
	var $stringhe = array();
	var $mailSubject = 'Concorso Ciobar';
	
	function __construct($case)
	{
		$this -> cs = $case;
		$this -> init();
	}
	
	function init()
	{
		$this -> prm = params::get_instance();
		$this -> setFiles();	
	}
	
	function setFiles()
	{
		$this->html = file_get_contents($this->prm->folders['structure'].SP.'mails/mail.'.$this->cs.'.html');
		require($this->prm->folders['structure'].SP.'mails/mail.'.$this->cs.'.schema.php');
		$this->mailSubject = $mailsubject;
		$this->stringhe = $stringhe;
	}
	
	function buildMail($values)
	{
		$domain = (IN_FACEBOOK) ? 'http://www.ciobar.it/' : $this->prm->site_url;
		$this->html = str_replace('{SITE_URL}', $domain, $this->html);
		$this->html = str_replace('{FB_TAB}', $this->prm->fb_tab_url, $this->html);
		foreach($this->stringhe as $key=>$placeholder)
		{
			if(!isset($values[$key]))
			{
				return false;
			}
			$this->html = str_replace($placeholder, $values[$key], $this->html);
		}
		return $this;
	}
	
	function send($email)
	{
		$mail = new htmlMimeMail();
		$mail->setSubject($this->mailSubject);
		$mail->setHtml($this->html);
		$mail->setReturnPath(EMAIL_SYSTEM);
		$mail->setFrom(EMAIL_SYSTEM);
		//$mail->setBcc('Livio Giovenco<livio.giovenco@digintel.it>');
		$result = $mail->send(array($email));
		if (!$result)
		{
			return false;
		}
		return true;
	}
}
?>