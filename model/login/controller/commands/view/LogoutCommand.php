<?php

require_once PUREMVC.'patterns/command/SimpleCommand.php';  
require_once PUREMVC.'interfaces/INotification.php';
require_once COMMON.'Session.php';

class LogoutCommand extends SimpleCommand
{
	public function execute( INotification $notification )
	{	
		$this->session = new Session();
		$this->session->destroy();

		header('Location: index.php');	
	}
}

?>
