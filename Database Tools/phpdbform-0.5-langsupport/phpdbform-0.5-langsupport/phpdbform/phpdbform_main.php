<?php
// Copyright 2002 - Coral Informática Ltda
// Paulo Assis
if(strstr($_SERVER["PHP_SELF"], "/phpdbform/phpdbform_main.php"))  die ("You can't access this file directly...");
session_start();

class phpdbform_main
{
	var $access;
	var $db;
	var $menu;
	var $theme;
}

$phpdbform_main = new phpdbform_main();
// siteconfig will fill all data needed by phpdbform_main class
require_once( "siteconfig.php" );

// By Iko (2004-10-17): Language support: _LANGERRORCANTCONNECT
if( !$phpdbform_main->db->connect() ) die( _LANGERRORCANTCONNECT );

if( isset($_GET["act"]) )
{
	if( $_GET["act"] == "logout" ) $phpdbform_main->access->logout();
}

$erro = "";
if( isset($_POST["admLogin"]) )
{
	$admLogin = trim(strip_tags($_POST["admLogin"]));
	$admPasswd = trim(strip_tags($_POST["admPasswd"]));
	if( !$phpdbform_main->access->do_login( $admLogin, $admPasswd ) )
	{
		// By Iko (2004-10-17): Language support: _LANGERRORINVALIDLOGIN
		$erro = _LANGERRORINVALIDLOGIN;
	}
}

function check_login( $min_level )
{
	global $phpdbform_main;
	if( !$phpdbform_main->access->check_login( $min_level ) ) Header("Location: index.php");
}

function draw_adm_header( $title )
{
	global $phpdbform_main;
	$phpdbform_main->theme->draw_adm_header( $title );
}

function draw_adm_footer()
{
	global $phpdbform_main;
	$phpdbform_main->theme->draw_adm_footer();
}

function delmagic( $valor )
{
	// this function removes backslashes ig magic_quotes_gpc is on
	if( get_magic_quotes_gpc() ) return stripslashes( $valor );
	else return $valor;
}
?>