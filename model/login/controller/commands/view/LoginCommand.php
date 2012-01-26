<?php
class LoginCommand extends ExtendedSimpleCommand
{
	var $session;
	
	public function execute( INotification $notification )
	{	
		if( $this->command=="login" )
		{
			$user = $this->facade->retrieveProxy( MySQLProxy::NAME )->login();
			
			if( $user != false )
			{
				// SUCCESS
				$this->session->user( $user ); 
				header( "Location: index.php" );
			}
			else
			{
				//Incorrect login - Redirect to actual login page
				header('Location: login.php?error=1');
			}
		}
		else
		{
			$error	 		= isset( $_REQUEST['error'] ) 	? intval($_REQUEST['error']) 	: 0;
			
			$content = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/login_form.html' );
			
			$error_content = "";
			
			switch( $error )
			{
				case 1:
					$error_content.= para("Your login credentials are incorrect.", "error" );
				break;
			}
			
			$tokens = array( 
				'{CONTENT}' => $content,
				'{LOGIN_ERRORS}' => $error_content
			);
			
			$this->facade->sendNotification( ApplicationFacade::TEMPLATE, $this->container);
			$this->facade->sendNotification( ApplicationFacade::TOKENIZE, $tokens );
			$this->facade->sendNotification( ApplicationFacade::TOKENIZE, $this->getUniversalTokens() );
			$this->facade->sendNotification( ApplicationFacade::RENDER );	
		}
	}
}

?>
