<?php

/***************** NOTES ***********************

ExoPHPDesk Extra File
 
This file is intended to run in a subdirectory.
If you want it to run in the main directory, then
remove $tpl_dirs variable, and remove ../ from
paths.

***********************************************/

// SET THE TEMPLATE DIRECTORY
$tpl_dirs = '../tpl/Blue/';

// Uncomment the variable below to disable login box
//$EX_FILE = 1;

// INCLUDE THE FILE COMMON.PHP
include ( '../common.php' );

// CHECK FOR AUTHENTICATION
// Replace $NO_AUTH == 0 with TRUE to disable auth check
if ( $NO_AUTH == 0 )
{

	// CREATE A NAMED OF CURRENT FILE
	$SELF  =  $_SERVER['PHP_SELF'];
	
	// < -- USER HEAD -- >	
	
	// IF LOGGED IN AS ADMIN GET ADMIN HEAD
	if ( $L_TYPE == 'admin' )
	{
		$_SERVER['PHP_SELF'] = '../admin.php';
		_parse ( $tpl_dirs . 'admin_head.tpl' );
		echo $class->read;
	}
	// IF LOGGED IN AS A STAFF
	elseif ( $L_TYPE == 'staff' )
	{
		$_SERVER['PHP_SELF'] = '../staff.php';
		_parse ( $tpl_dirs . 'staff_head.tpl' );
		echo $class->read;	
	}
	// IF ITS A MEMBER
	else
	{
		$_SERVER['PHP_SELF'] = '../member.php';
		_parse ( $tpl_dirs . 'member_head.tpl' );
		echo $class->read;
	}
	// USER HEAD END -->
	
	// SET CURRENT FILE NAME
	$_SERVER['PHP_SELF'] = $SELF;
	
	// PRINT
	echo 'Its a test page, it can be helpful if you want to create pages for ExoPHPDesk and want to use the same '
   		.'header, footer and css files.';
}

// PARSE FOOTER FILE
include ( '../footer.php' );

?>
