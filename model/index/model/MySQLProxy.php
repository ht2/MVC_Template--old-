<?php

require_once PUREMVC.'patterns/proxy/Proxy.php';
require_once COMMON.'model/vo/VO.php';
require_once COMMON.'MySQL.php';
require_once BASEDIR.'ApplicationFacade.php';

class MySQLProxy extends Proxy
{
	const NAME = "MySQLProxy";
	var $mysql;
	
	public function __construct()
	{
		parent::__construct( MySQLProxy::NAME, new VO() );
		$this->mysql = new MySQL(); 
	}
	
	public function getUser( $id )
	{
		$this->mysql->select("users", "user_id='$id'");
		return $this->mysql->singleResult();		
	}

	public function vo()
	{
		return $this->getData();
	}
}
?>
