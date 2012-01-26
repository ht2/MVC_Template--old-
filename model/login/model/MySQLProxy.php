<?php

require_once BASEDIR.'ApplicationFacade.php';
require_once PUREMVC.'patterns/proxy/Proxy.php';
require_once COMMON.'model/vo/VO.php';
require_once COMMON.'MySQL.php';

class MySQLProxy extends Proxy
{
	const NAME = "MySQLProxy";
	var $mysql;
	
	public function __construct()
	{
		parent::__construct( MySQLProxy::NAME, new VO() );
		$this->mysql = new MySQL(); 
	}
	
	public function login()
	{
		$email 			= isset( $_REQUEST['email'] ) 			? strtolower(trim($_REQUEST['email'])) 		: "";
		$password 		= isset( $_REQUEST['password'] ) 		? strtolower(trim($_REQUEST['password'])) 	: "";

		$this->mysql->select("users", "email='$email'");
		$user = $this->mysql->singleResult();
		if( $user )
		{
			if( $user->password == md5( $password . $user->salt ) )
			{
				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function vo()
	{
		return $this->getData();
	}
}
?>
