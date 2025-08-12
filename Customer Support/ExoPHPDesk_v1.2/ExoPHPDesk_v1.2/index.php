<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Main File
// >>
// >> INDEX . PHP File - Main File Of Helpdesk
// >> Started : January 24, 2004
// >> Edited  : June 28, 2004
// << -------------------------------------------------------------------- >>

//error_reporting( E_ERROR ); // we only need errors!

ob_start();

$B_FILE = 'INDEX';

if( $_GET['fn'] == 'troubles' )
{
	$FILE = 'troubles';
}

if( $_GET['fn'] == NULL )
{
	$EX_FILE = 1;
}

// Required Files
include_once('common.php');

//
// Set Call Variable For An
// Indirect Call Identification
//
$InDirectCall = TRUE;

//
// OverWrite PHP_SELF Variable. Sets the right URL
// And Also Helps The Web Servers That Doesn't
// Support PHP_SELF
//
$_SERVER['PHP_SELF'] = 'index.php?fn=' . FNAME .'&s='. $SID .'&';	

//
// Check For Authentication
// If Available, Move Next
//
if( $NO_AUTH == 0 )
{
	// Parse The Header File
	if ( $L_TYPE == 'admin' && $_GET['H_MENU'] != 1 )
	{
		$_SERVER['PHP_SELF'] = 'admin.php';
		_parse( $tpl_dir.'admin_head.tpl' );
		echo $class->read;
	}
	elseif ( $L_TYPE == 'staff' && $_GET['H_MENU'] != 1 )
	{
		$_SERVER['PHP_SELF'] = 'staff.php';
		_parse( $tpl_dir.'staff_head.tpl' );
		echo $class->read;				
	}
	elseif ( $L_TYPE == 'members' && $_GET['H_MENU'] != 1 )
	{
		$_SERVER['PHP_SELF'] = 'member.php';
		_parse( $tpl_dir.'member_head.tpl' );
		echo $class->read;				
	}
	
	// Reset PHP_SELF Once Again
	$_SERVER['PHP_SELF'] = 'index.php?fn=' . FNAME .'&s='. $SID .'&';		
	
	// Include The Appropriate
	// File according to Request
	switch ( FNAME )
	{
		case 'ticket':
			include( 'libs/ticket.php' );
			break;

		case 'notes':
			include( 'libs/notes.php' );
			break;
		
		case 'search':
			include( 'libs/search.php' );
			break;

		case 'servers':
			include( 'libs/servers.php' );
			break;
		
		case 'saved':
			include( 'libs/saved.php' );
			break;

		case 'troubles':
			break;
		
		case 'rate':
			include( 'libs/rate.php' );
			break;
			
		case 'announce':
			include( 'libs/announce.php' );
			break;
			
		case 'personal':
			include( 'libs/personal.php' );
			break;
		
		case 'profile':
			include( 'libs/profile.php' );
			break;
			
		case 'statistics':
			include( 'libs/statistics.php' );
			break;
			
		case 'calender':
			include( 'libs/calender.php' );
			break;

		default:
			break;
			
	}

}

//
// Check For File Types That Have
// Anonymous Access Allowed
//
if( FNAME == NULL )
{
	include( 'libs/index.php' );
}
elseif( FNAME == 'troubles' )
{
	include( 'libs/troubles.php' );
}

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>