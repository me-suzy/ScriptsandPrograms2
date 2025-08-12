<?php

if ( !session_id() )
{
	session_start();
}

if ( !isset( $_SESSION[ "loggedin" ] ) )
{
	header( "Location: Login.php" );
	exit;
}

if ( isset( $_POST[ "exit" ] ) && $_POST[ "exit" ] == "1" )
{
	unset( $_SESSION[ "loggedin" ] );
	header( "Location: Login.php" );
	exit;
}

?>