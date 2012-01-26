<?php
class ResetPasswordCommand extends ExtendedSimpleCommand
{
	public function execute( INotification $notification )
	{	
		if( $this->session->valid()===true ) $this->session->destroy();
		
		$id 		= isset( $_REQUEST['id'] ) 			? $_REQUEST['id'] 			: 0;
		$checksum 	= isset( $_REQUEST['checksum'] ) 	? $_REQUEST['checksum'] 	: "";
		
		$user = $this->facade->retrieveProxy( MySQLProxy::NAME )->getUser( $id );
		
		$valid_check = ( $user &&  md5($user->password.$user->user_id) == $checksum );
		
		if( $user && $valid_check)
		{
			switch( $this->command )
			{
				case "process_password":
					$pass1 = isset( $_REQUEST['pass1'] ) ? $_REQUEST['pass1'] : "";
					$pass2 = isset( $_REQUEST['pass2'] ) ? $_REQUEST['pass2'] : "";
					
					if( $pass1 != "" && $pass2 != "" && $pass1==$pass2 )
					{
						$hash_pass = md5($pass1.$user->salt);
						
						$this->mysql->update( "users",
											 array( "password"=>$hash_pass ),
											 "user_id",
											 $id
											 );
						$success = true;
					} else {
						$success = false;
					}
					
					$content = ($success) ?  "Password change successful." : "There was an error. <a href='index.php?view=reset_password&id=$id&checksum=$checksum' title='Reset Password'>Please try again.</a>";
					$html = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/resetpassword.html' );
					$tokens = array( 							
						'{CONTENT}' 		=> $content,
						'{USERNAME}' 		=> $user->username,
						'{USER_FNAME}' 		=> $user->fname,
						'{USER_LNAME}' 		=> $user->lname,
						'{USER_EMAIL}' 		=> $user->email,
						'{USER_ID}' 		=> $user->user_id,
						'{CHECKSUM}' 		=> $checksum									
					);
				break;
				default:				
					$content = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/resetpassword_form.html' );
					$html = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/resetpassword.html' );
					$tokens = array( 							
						'{CONTENT}' 		=> $content,
						'{USERNAME}' 		=> $user->username,
						'{USER_FNAME}' 		=> $user->fname,
						'{USER_LNAME}' 		=> $user->lname,
						'{USER_EMAIL}' 		=> $user->email,
						'{USER_ID}' 		=> $user->user_id,
						'{CHECKSUM}' 		=> $checksum									
					);
				break;
				
			}
		} else {
			$content = "<p>Sorry, there is a problem with your link.</p><p>For security reasons the link is designed for one use only. If you have changed your password since you recieved the link then it will not work anymore. Please use the forgotten password function when logging into Curatr.</p>";
			$html = $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/resetpassword.html' );
			$tokens = array( 
				'{CONTENT}' => $content,
			);
		}
		
		$this->facade->sendNotification( ApplicationFacade::TEMPLATE, $this->container );
		$this->facade->sendNotification( ApplicationFacade::TOKENIZE, $tokens );
		$this->facade->sendNotification( ApplicationFacade::TOKENIZE, $this->getUniversalTokens() );
		$this->facade->sendNotification( ApplicationFacade::RENDER );

	}
}

?>
