<?php

require_once PUREMVC.'patterns/command/SimpleCommand.php';  
require_once COMMON.'controller/command/ExtendedSimpleCommand.php';  
require_once PUREMVC.'interfaces/INotification.php';
require_once BASEDIR.'model/MySQLProxy.php';
require_once BASEDIR.'model/MenuProxy.php';
require_once BASEDIR.'model/OverviewProxy.php';
require_once COMMON.'model/proxies/TemplateProxy.php';
require_once COMMON.'view/TemplateMediator.php';  
require_once COMMON.'view/Template.php'; 
require_once COMMON.'model/proxies/IncludesProxy.php';

class InitialiseCommand extends SimpleCommand
{
	public function execute( INotification $notification )
	{	
		// Register Mediators / Proxies
		$this->facade->registerProxy( new MySQLProxy() );
		$this->facade->registerProxy( new IncludesProxy() );
		$this->facade->registerProxy( new MenuProxy() );
		$this->facade->registerProxy( new OverviewProxy() );
		$this->facade->registerProxy( new TemplateProxy() );
		$this->facade->registerMediator( new TemplateMediator( new Template() ) );
	
		// Get current state
		$this->facade->sendNotification( ApplicationFacade::STATE );	
	}
}

?>
