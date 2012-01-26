<?php
class ExtendedSimpleCommand extends SimpleCommand
{
	protected $module;
	protected $session;
	protected $view;
	protected $sub_view;
	protected $command;
	protected $page_title;
	protected $header_image 			= 'default.png';
	protected $secnavbar;
	protected $includes;
	protected $inits;
	
	protected $footer;
	
	public function __construct()
	{		
		parent::__construct();
		$this->session 		= new Session();		
		$this->mysql 		= new MySQL();
		
		$this->view 		= isset( $_REQUEST['view'] ) 			? strtolower(trim($_REQUEST['view'])) 	: "home";
		$this->command 		= isset( $_REQUEST['command'] )			? strtolower(trim($_REQUEST['command']))	: "";
		
		$base_includes 	 	= $this->facade->retrieveProxy( IncludesProxy::NAME )->includes( "jquery" );	
		$base_inits	  		= '';
		
		$this->includes 	= $base_includes;
		$this->inits 		= $base_inits;
		
		$this->module		= "";
		$this->site_title 	= "BP GBLS";
		$this->logout_link	= constructURL("login.php", array( array("command", "logout") ) );
		
		$this->container	= $this->facade->retrieveProxy( TemplateProxy::NAME )->loadFile( HTML.'common/container.html' );
		
		ob_start(); // start output buffer
		include HTML.'common/header.php';
		$this->header = ob_get_contents(); // get contents of buffer
		ob_end_clean();
		
		ob_start(); // start output buffer
		include HTML.'common/footer.php';
		$this->footer = ob_get_contents(); // get contents of buffer
		ob_end_clean();
		
				
	}
	
	public function execute( INotification $notification ){}
	
	public function buildSecNavBar( $links=array() ){
		$html = "";
		$i = 1;
		$numItems = count($links);
		foreach( $links as $link )
		{
			$name 	= $link[0];
			$lname	= $link[1];
			$class	= ( $lname == $this->sub_view || ($this->sub_view == "" && $i==1 )) ? "selected" : "";
			$class .= ( $i == 1 ) 			? " first" 	: "";
			$class .= ( $i == $numItems ) 	? " last" 	: "";
			$html .= "<li><a href='/{VIEW}/$lname' class='$class'>$name</a></li>";
			$i++;
		}
		
		$this->secnavbar = $html;
	}
	
	protected function getUniversalTokens()
	{		
		return array(	
			'{INCLUDES}' 			=> $this->includes,
			'{INITIALISERS}' 		=> $this->inits,
			'{PAGE_TITLE}'			=> $this->page_title,
			'{HEADER}'				=> $this->header,
			'{FOOTER}'				=> $this->footer,
			'{MODULE}'				=> $this->module,
			'{SITE_TITLE}'			=> $this->site_title,
			'{VIEW}' 				=> $this->view,
			'{COMMAND}' 			=> $this->command,
		);
	}
	
	protected function loginCheck()
	{
		if( !$this->session->valid() )
		{
			header('Location:login.php'); 
			exit();			
		}
	}
	
	
}

?>