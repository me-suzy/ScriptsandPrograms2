<?php
   
include ("includes/start.php");

extract( $_GET );
extract( $_POST );

$cart = new Cart;

// destroy session
if( isset( $_SESSION["valid_user"] ) ) {
	
	$result = true;
	$old_user = $_SESSION["valid_user"];
	unset( $_SESSION["valid_user"] );
}

header("Location:index.php");
exit;

?>