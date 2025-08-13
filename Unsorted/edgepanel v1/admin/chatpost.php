<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| livechat.php :: Admin live chat loader                               |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: livechat.php,v 1.00.0.1 05/11/2002 18:58:58 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

//----------------------------------
# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Do we need to post a message?

if($_SUBMIT[ 'post' ] == 1) {
	
	//----------------------------------
	# Add message to the script
	
	if(!empty($_SUBMIT['message'])) {
	
		$message_string = "Admin||". addslashes($_SUBMIT[ 'message' ]);
		
		$message_string = ereg_replace("\'","&#39;",$message_string);
		$message_string = ereg_replace("\"","&quot;",$message_string);

		$result = $db->Query("SELECT * FROM `$CONF[table_prefix]livechats`
		    	    	    WHERE id = '$_SUBMIT[id]'");
	
		$row_info = $db->fetch_row($result);
	
		$full_script = $row_info['script'];
	
		$entries = explode( ":::" , $full_script );
	
		array_push($entries, $message_string);
	
		$string = implode( $entries , ":::");
	
		$result = $db->Query("UPDATE `$CONF[table_prefix]livechats`
			    	    SET script = '$string'
			    	    WHERE id = '$_SUBMIT[id]'");
		
	}
	
}

//----------------------------------
# Output page

echo(" <style type=\"text/css\">
<!--
BODY { font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
td {  font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
a {  color: #4F769E; text-decoration: none}
a:hover {  text-decoration: underline}
.heading { color: #4F769E;font-weight: bold}
INPUT {  font-family: Verdana; font-size: 11px; color: #383838;}
SELECT {  font-family: Verdana; font-size: 11px; color: #383838;}
TEXTAREA {  font-family: Verdana; font-size: 11px; color: #383838;}
.highlight { color: #EE9700;}
.unhighlight { }
-->

</style>");

echo("	<form action='chatpost.php?id=$_SUBMIT[id]' method=post>
	<input type=hidden name=post value=1>
	<input type=text name=message size=42> <input type=submit value='Send'>
	</form>");

?>