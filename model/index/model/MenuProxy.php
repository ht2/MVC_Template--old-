<?php

require_once PUREMVC.'patterns/proxy/Proxy.php';
require_once COMMON.'model/vo/VO.php';
require_once BASEDIR.'ApplicationFacade.php';
require_once COMMON.'Session.php';

class MenuProxy extends Proxy
{
	const NAME = "MenuProxy";
	
	public function __construct()
	{
		parent::__construct( MenuProxy::NAME, new VO() );
		$this->session = new Session();
	}
	

	public function vo()
	{
		return $this->getData();
	}
}


?>
