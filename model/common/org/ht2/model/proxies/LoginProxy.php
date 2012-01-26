<?php

require_once PUREMVC.'patterns/proxy/Proxy.php';
require_once COMMON.'model/vo/VO.php';
require_once BASEDIR.'ApplicationFacade.php';
require_once COMMON.'Session.php';

class LoginProxy extends Proxy
{
	const NAME = "LoginProxy";
	
	public function __construct()
	{
		parent::__construct( LoginProxy::NAME, new VO() );
		$this->session = new Session();
	}
	
	public function getLoginView()
	{
		if( $this->session->valid() )
		{				
			$html = br("Hi ".$this->session->fname." ".$this->session->lname."!");
			$html.= br(" | ");
			$html.= br("<a href='users.php?view=edit_user&amp;id={USER_ID}' title='Logout'>Edit Profile</a>");
			$html.= br(" | ");
			$html.= br("<a href='login.php?command=logout' title='Logout'>Logout</a>");
		} else 
		{
			$html = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/header_login_form.html' );			
		}
		
		return $html;
	}
	
}

?>