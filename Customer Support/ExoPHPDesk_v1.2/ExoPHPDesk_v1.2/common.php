<?php

// << -------------------------------------------------------------------- >>
// >> EXOPHPDesk Common File
// >>
// >> COMMON . PHP File - A header like File
// >> Started : December 06, 2003
// >> Edited  : June 17, 2004
// << -------------------------------------------------------------------- >>

// SET ERROR REPORTING TO ERRORS, AS FOR COMPATIBALITY
error_reporting ( E_ERROR );

ob_start();

//
// SET SESSION ID
//
$SID = $_GET['s'];

// INCLUDE REQUIRED FILE
include_once('conf.php');

// Delete Inactive Sessions - Deprecated!
// we no more need to delete the sessions..
// $db->query( "DELETE FROM phpdesk_sessions WHERE timeout <= UNIX_TIMESTAMP()" );

//
// CHECK FOR SESSION ID
//
if( !empty( $_GET['s']))
{
	$SIQ = $db->query( "SELECT name,pass,ip FROM phpdesk_sessions WHERE sid = '{$_GET['s']}'" );
	if( $db->num( $SIQ ))
	{
		$SIF = $db->fetch( $SIQ );
		if( $SIF['ip'] == $_SERVER['REMOTE_ADDR'] )
		{
			$_COOKIE['help__user'] = $SIF['name'];
			$_COOKIE['help__pass'] = $SIF['pass'];
			
			// Update The Session TimeOut
			$db->query( "UPDATE phpdesk_sessions SET timeout = UNIX_TIMESTAMP()+{$STimeOut} WHERE 
							sid = '{$SID}'" );
		}
	}
}

//		
// Required Constants
//
define("MAIN", "Location: {$_SERVER['PHP_SELF']}");
define("USER", $_COOKIE['help__user']);
define("PASS", $_COOKIE['help__pass']);
define("SELF", $_SERVER['PHP_SELF']);
define("ACT",  $_REQUEST['action']);
define("TYPE", $_REQUEST['type']);
define("SUBM", $_POST['submit']);
define("FNAME", $_GET['fn']);

// Include Language File
include_once($lang_file);

//
// AppendBox To Success And Error Vars
//
$success = AppendBox( $success, 'Success' );
$error   = AppendBox( $error, 'Error' );

if ( !empty ( $tpl_dirs ) )
{
	$tpl_dir = $tpl_dirs;
}

//
// Generate Some Headers
//
if ( $doHeaders )
{
	@header( "HTTP/1.1 200 OK" );
	@header( "Content-type: text/html" );
	// Apache 2 Cache Control
	if ( strstr( $_SERVER['SERVER_SOFTWARE'], 'Apache/2' ))
	{
		@header( 'Cache-Control: no-cache, pre-check=0, post-check=0' );
	}
	else
	{
		@header( "Cache-Control: private, pre-check=0, post-check=0, max-age=0" );
	}
	@header( "Expires: 0" );
	@header( "Pragma: no-cache" );
}

// Check If Ip Is Banned
foreach( $BannedArray AS $IP )
{
	if( $_SERVER['REMOTE_ADDR'] == $IP )
	{
		$BANNED = 1;
		BREAK;
	}
}

// Parse Header File
_parse( $tpl_dir.'header.tpl' );

//
// Print Headers If LiveChat
// Not Called
//
if( ACT != 'livechat' && $_GET['print'] != 1 )
{
	echo $class->read;
}
else
{
	$HEADER = $class->read;
}

if( $BANNED == 1 )
{
	die( $error['banned'] );
}

$NO_AUTH = 1;

//
// Guess L_TYPE According To Cookies If Any
//
if( empty( $_GET['l_type'] ) && empty( $L_TYPE ))
{
	if( USER != NULL && PASS != NULL )
	{
		$L_TYPE = preg_replace( '/phpdesk_(.*)/i', "\\1", where( USER ));
	}
} else if($FILE == 'troubles') {
	// FIX (19/10/04) - No more weirdo errors with troubleshooter!
	$L_TYPE = $_GET['l_type'];
}

// Prepare L_TYPE Variable I.E. User Type Variable
//$L_TYPE = ( empty( $L_TYPE ) && empty ( $_GET['l_type'] ) ) ? '' : ( ( empty( $_GET['l_type'] ) ) ? '_'.$L_TYPE : '_'.$_GET['l_type'] );

//
// Desk Offline??
// If the desk is offline then send a message to the user that desk is offline..
//
if( $desk_offline == 1 && ADMIN_AREA != 1 && $L_TYPE != 'admin' )
{
	echo $general['desk_offline'];
}
//
// CHECK IF A SPECIAL FILE
//
elseif ( $EX_FILE == 1 )
{
	// CHECK FOR COOKIES
	if ( USER == NULL && PASS == NULL )
	{
		$NO_AUTH = 1;
	}
}
elseif ( $FILE == 'troubles' && ( TYPE == NULL || TYPE == 'view' ) && $MEM_TROUBLESHOOTER = 1 && $L_TYPE == NULL )
{
	
	$NO_AUTH = 1;
	
}
elseif( USER == "" || PASS == "" )
{
	if( $B_FILE == 'INDEX' )
	{
		header( 'Location: member.php' );
	}
	
	if(!isset($_POST['submit']))
	{
		// See if there is any query
		if($_GET['login'] == 'error')
		{
			$errord = $error['login_user'];
		}
		elseif($_GET['login'] == 'out')
		{
			$errord = $success['logout_user'];
		}

		$TYPE = ( $L_TYPE == '_admin' || $L_TYPE == '_staff' ) ? '_admin' : NULL;

		// Parse Login File
		_parse($tpl_dir.'login'. $TYPE .'.tpl');
		echo $class->read;
		
		setcookie( 'help__test', 'TEST' );
		
	}
	else
	{
		
		$NO_AUTH = 1;

		//
		// Setcookies and go for authentication
		//
		if( ( $_POST['remember'] != '1' && $_COOKIE['help__test'] != 'TEST' ) OR $_POST['logintype'] == 'Session' )
		{
			$Type = preg_replace( '/phpdesk_(.*)/i', "\\1", where( $_POST['user'] ));
			$SID  = md5( time() );
			
			$db->query("DELETE FROM phpdesk_sessions WHERE name = '{$_POST['user']}'");
			$db->query( "INSERT INTO phpdesk_sessions SET `sid` = '{$SID}', `name` = '{$_POST['user']}',
							`pass` = md5('{$_POST['pass']}'), `ip` = '{$_SERVER['REMOTE_ADDR']}', 
							`timeout` = UNIX_TIMESTAMP()+{$STimeOut}, `type` = '{$Type}'" );
			
			header( "Location: ". SELF ."?l_type=". $_GET['l_type'] ."&s=". $SID );			
		}
		else
		{
		
			// 
			//	Remember Me ????
			//
			if( $_POST['remember'] == '1' )
			{
				setcookie('help__user', $_POST['user'], time() + 3600*24*30 );
				setcookie('help__pass', md5($_POST['pass']), time() + 3600*24*30 );
			}
			else
			{
				setcookie('help__user', $_POST['user']);
				setcookie('help__pass', md5($_POST['pass']));
			}
			
			setcookie('help__test', '', time()-3600);
			header( "Location: ".SELF."?l_type=".$_GET['l_type'] );
		}
	}
}
else
{

	$L_TYPE = ( empty( $L_TYPE ) && empty ( $_GET['l_type'] ) ) ? '_members' :
				 (( empty( $_GET['l_type'] ) ) ? $L_TYPE : '_'.$_GET['l_type']);
	
	$L_TYPE = '_' . str_replace( '_', NULL, $L_TYPE );
	$SQL = ( $L_TYPE == '_admin' ) ? "SELECT * FROM `phpdesk". $L_TYPE ."` WHERE name='".USER."' AND pass = '".PASS."'" :
				"SELECT * FROM `phpdesk". $L_TYPE ."` WHERE username='".USER."' AND password = '".PASS."'";
	
	//
	// Check For Auth and set Vars
	//
	$check = $db->query($SQL);
	$_F = $db->fetch($check);
	$a_id = $_F['id'];
	$a_tppage = $_F['tppage'];
	
	//
	// If no user found, go back to login
	//
	if( !$db->num($check) )
	{
		$location = where( USER );
		$check2 = ( $location == 'phpdesk_admin' ) ? "SELECT * FROM ". $location ." WHERE name='".USER."' AND pass = '".PASS."'" :
				"SELECT * FROM ". $location ." WHERE username='".USER."' AND password = '".PASS."'";
		$check2 = $db->query( $check2 );
		
		if( !$db->num( $check2 ))
		{
			setcookie('help__user', '', time()-3600);
			setcookie('help__pass', '', time()-3600);
			setcookie('help__test', '', time()-3600);
			setcookie('help__sid', '', time()-3600);
			
			if( !empty( $SID ))
			{
				$db->query( "DELETE FROM phpdesk_sessions WHERE sid = '{$SID}'" );
			}
		
			$NO_AUTH = 1;
			header( MAIN  . "?login=error&l_type=".$_GET['l_type'] );
		}
		else
		{
			$location = ( $location == 'phpdesk_admin' ) ? 'admin.php' : (( $location == 'phpdesk_staff') ? 'staff.php' : 'member.php' );

			// Move to area				
			header( 'Location: '. $location ."?s={$SID}" );
		}
		
	}
	elseif( $L_TYPE == '_members' && $_F['disabled'] == 1 )
	{
		$NO_AUTH = 1;
		echo $error['disabled'];
	}	
	else
	{
		
		$query = $db->query( "SELECT * FROM phpdesk_sessions WHERE sid = '{$_COOKIE[help__sid]}'" );
		if( $db->num( $query ) && !empty( $_COOKIE['help__sid'] ) && empty( $SID ) )
		{			
			$C_SID = $_COOKIE['help__sid'];
			
			// Update The Session TimeOut
			$query = $db->query( "UPDATE phpdesk_sessions SET timeout = UNIX_TIMESTAMP()+{$CSTimeOut} WHERE 
							sid = '{$C_SID}'" );
			
			if( !$query )
			{
				setcookie( 'help__sid', NULL, time()-3600 );
			}

			//setcookie( 'help__sid', $_COOKIE['help__sid'], time() + $CSTimeOut );
		}
		
		if( empty( $_COOKIE['help__sid'] ) && empty( $SID ))
		{
			//
			//  Create session now!
			//      AND / OR
			//  Delete session now!
			//
			$Type = preg_replace( '/phpdesk_(.*)/i', "\\1", where( $_COOKIE['help__user'] ));
			$Sid  = md5( time() );
			
			$db->query( "DELETE FROM phpdesk_sessions WHERE name = '{$_COOKIE['help__user']}'" );
			$db->query( "INSERT INTO phpdesk_sessions SET `sid` = '{$Sid}', `name` = '{$_COOKIE['help__user']}',
						`pass` = 'GUEST', `ip` = 'GUEST',
						`timeout` = UNIX_TIMESTAMP()+{$CSTimeOut}, `type` = '{$Type}'" );
			
			setcookie( 'help__sid', $Sid, time() + $CSTimeOut );
		}
								
		$L_TYPE = str_replace( '_', NULL, $L_TYPE );
		$NO_AUTH = 0;

		// set the edit ticket var if a staff logged in
		if( $L_TYPE == 'staff') 
		{
			$edit_ticket = $_F['edit_ticket'];
			$edit_response = $_F['edit_response'];
		}
		
	}
}

if ( USER != NULL )
{
	// Unset the Queries Used
	$db->count = 1;
}

// Check if board is offline, if it is then include the footer file and die..
if( $desk_offline == 1 && ADMIN_AREA != 1 && $L_TYPE != 'admin' )
{
	include( 'footer.php' );
	die();
}

ob_end_flush();

?>