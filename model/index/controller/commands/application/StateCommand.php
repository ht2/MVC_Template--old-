<?php

require_once PUREMVC.'patterns/command/SimpleCommand.php';  
require_once PUREMVC.'interfaces/INotification.php';
require_once BASEDIR.'ApplicationFacade.php';

class StateCommand extends SimpleCommand
{
	public function execute( INotification $notification )
	{		
		$view = ( isset( $_REQUEST['view'] ) ) ? $_REQUEST['view'] : 'default';	
		
		switch( $view )
		{
			case 'default': $this->facade->sendNotification( ApplicationFacade::VIEW_OVERVIEW ); break;
			case 'overview': $this->facade->sendNotification( ApplicationFacade::VIEW_OVERVIEW ); break;		
		}
	}
}

?>
