<?php
define( 'DEBUG', $_SERVER['REMOTE_ADDR']=='127.0.0.1' );
define( 'HOST', dirname( __FILE__ ) );
define( 'HTML', HOST.'/view/templates/' );
define( 'COMMON', HOST.'/model/common/org/ht2/' );
define( 'PUREMVC', HOST.'/model/common/org/puremvc/php/' );
define( 'BASEDIR', HOST.'/model/index/' );

require_once BASEDIR.'Application.php'; new Application();
?>
