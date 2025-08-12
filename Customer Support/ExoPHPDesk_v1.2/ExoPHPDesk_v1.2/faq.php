<?php

// << -------------------------------------------------------------------- >>
// >> EXOPHPDesk Knowledge Base for Guests
// >>
// >> FAQ . PHP File - Example F.A.Q. File
// >> Started : November 20, 2003
// << -------------------------------------------------------------------- >>

error_reporting ( E_ERROR );

ob_start();

$EX_FILE  =  1;

// INCLUDE COMMON FILE
include_once( 'common.php' );

$KB->VIEW = 'GUEST';
			
if(TYPE == '')
{
	if(isset($_GET['group']))
	{
		$KB->GROUP = $_GET['group'];
		echo $KB->kb_view();
	}
	else
	{
		echo $KB->kb_list_group();
	}
}
elseif(TYPE == 'view')
{
	$KB->ID = $_GET['id'];
	echo $KB->kb_view_in();
}

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>