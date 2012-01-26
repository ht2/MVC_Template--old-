<?php

require_once PUREMVC.'patterns/proxy/Proxy.php';
require_once COMMON.'model/vo/VO.php';
require_once BASEDIR.'ApplicationFacade.php';
require_once COMMON.'Session.php';

class BreadcrumbProxy extends Proxy
{
	const NAME = "BreadcrumbProxy";
	
	public function __construct()
	{
		parent::__construct( BreadcrumbProxy::NAME, new VO() );
		$this->session = new Session();
	}
	
	public function siteTitle()
	{
		return "Duke CE Admin";
	}

	public function vo()
	{
		return $this->getData();
	}
}
?>
