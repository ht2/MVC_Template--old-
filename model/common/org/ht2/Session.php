<?php

class Session
{	
	var $theSite = "UNIQUE_SITE_NAME";
	
	function __construct()
	{		
		session_name($this->theSite);
		if( !isset( $_SESSION[ $this->theSite ] ) ) 
		{			
			@session_start();
			$_SESSION[ $this->theSite ] = $this->theSite;
		}
	}
	
	public function user( $user )
	{
		$_SESSION['user'] = $user;
		$_SESSION['user_id'] = (int)$user->user_id;
		$_SESSION['email'] = $user->email;
		$_SESSION['fname'] = $user->fname;
		$_SESSION['lname'] = $user->lname;
		$_SESSION['user_name'] = $user->fname." ".$user->lname;
		$_SESSION['user_session'] = md5( $_SESSION['user_id'].session_id().$this->theSite );		
	}
	
	
	public function valid()
	{
		if( isset($_SESSION['user_session']) && isset($_SESSION['user_id']) )  
		{
			if( $_SESSION['user_session'] == md5( $_SESSION['user_id'].session_id().$this->theSite ) )
			{
				return true;				
			} else {
				$this->destroy();
				return false;
			}
		} else {
			$this->destroy();
			return false;
		}
	}
	
	public function exists( $key )
	{
		return isset( $_SESSION[ $key ] );
	}
	
	function delete()
	{
		foreach( $_SESSION as $key => $value ) unset( $_SESSION[ $key ] );
	}	
	
	function delete_all()
	{
		foreach( $_SESSION as $key => $value ) unset( $_SESSION[ $key ] );
	}
	
	function destroy()
	{
		$this->delete_all();
		if( isset( $_SESSION[ $this->theSite ] ) ) 
		{
			session_unset( $this->theSite );
			session_destroy();
		}
	}
	
	public function __get( $key )
	{
		return @$_SESSION[ $key ];
	}
	
	public function __set( $key, $value )
	{
		$_SESSION[ $key ] = $value;
	}	
}

?>