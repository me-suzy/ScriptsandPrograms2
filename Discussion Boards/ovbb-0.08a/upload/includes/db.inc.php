<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Set database connection information.
	$strDBAddress = 'dbaddress';
	$strDBUsername = 'dbusername';
	$strDBPassword = 'dbpassword';
	$strDBDatabase = 'dbname';

	// Create the DatabaseError() function.
	if(!function_exists('DatabaseError'))
	{
		function DatabaseError()
		{
			global $CFG;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<META http-equiv="content-type" content="text/html; charset=utf-8">
<LINK rel="SHORTCUT ICON" href="favicon.ico">
<TITLE><?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB :. Database Error</TITLE>

<BODY>

<TABLE cellpadding=0 cellspacing=0 border=0 align=left width=450>
<TR>
	<TD width="100%" style="line-height: 14px; padding: 15px; font-family: tahoma, arial, sans-serif; font-size: 11px; text-align: justify;">
	<B>There seems to be a problem with the database.</B><BR><BR>
	Please try again by pressing the <A href="javascript:window.location=window.location;">refresh</a> button in your browser. An e-mail message has been dispatched to the <a href="mailto:<?php echo($CFG['general']['admin']['email']); ?>">Webmaster</A>, whom you can also contact if the problem persists. We apologize for any inconvenience.<BR><BR>
	<B>Error</B>: <?php echo(mysql_error()); ?>.
	</TD>
</TR>
</TABLE>

<?php
	ShowQueries();
?>

</BODY>
</HTML>
<?php
			// We're done.
			exit;
		}
	}

	// Create the sqlquery() function.
	if(!function_exists('sqlquery'))
	{
		// Executes a SQL query on the database.
		function sqlquery($strSQL)
		{
			// Increment the query count.
			global $iQueries, $aQueries;
			$iQueries++;

			// Store the query.
			$aQueries[] = $strSQL;

			// Query the database.
			$sqlResult = @mysql_query($strSQL);

			// Handle the result.
			if(!$sqlResult)
			{
				// There was an error executing the query.
				DatabaseError();
			}

			// Everything went smoothly, so return the result.
			return $sqlResult;
		}
	}

	// Establish a connection to the database server.
	$objDBConn = mysql_connect($strDBAddress, $strDBUsername, $strDBPassword);

	// Make sure we connected okay.
	if(!$objDBConn)
	{
		DatabaseError();
	}

	// Select the database.
	if(!@mysql_select_db($strDBDatabase))
	{
		DatabaseError();
	}
?>