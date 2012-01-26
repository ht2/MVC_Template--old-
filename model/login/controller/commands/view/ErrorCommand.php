<?php

require_once PUREMVC.'patterns/command/SimpleCommand.php';  
require_once PUREMVC.'interfaces/INotification.php';

class ErrorCommand extends SimpleCommand
{
	public function execute( INotification $notification )
	{	
		$html = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/container.html' );
		$this->facade->sendNotification( ApplicationFacade::TEMPLATE, $html );
		$this->facade->sendNotification( ApplicationFacade::RENDER );
	}
}

?>
