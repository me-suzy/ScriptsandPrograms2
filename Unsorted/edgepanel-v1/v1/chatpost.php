<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| openchat.php :: User script to check for an operator and provide     |
|                  a link for initation of support chat                |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: openchat.php,v 1.00.0.1 03/11/2002 12:44:15 mark Exp $     */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Do we need to post a message?

if($_SUBMIT[ 'post' ] == 1) {
	
	//----------------------------------
	# Add message to the script
	
	if(!empty($_SUBMIT['message'])) {
	
		$message_string = "You||". addslashes($_SUBMIT[ 'message' ]);
		
		$message_string = ereg_replace("\'","&#39;",$message_string);
		$message_string = ereg_replace("\"","&quot;",$message_string);
	
		$chat_id = $HTTP_COOKIE_VARS['chat_id'];

		$result = $db->Query("SELECT * FROM `$CONF[table_prefix]livechats`
		    	    	    WHERE id = '$chat_id'");
	
		$row_info = $db->fetch_row($result);
	
		$full_script = $row_info['script'];
	
		$entries = explode( ":::" , $full_script );
	
		array_push($entries, $message_string);
	
		$string = implode( $entries , ":::");
		
		if(($row_info['admin_id'] != 0) && ($row_info['closed'] != 1)) {
	
			$result = $db->Query("UPDATE `$CONF[table_prefix]livechats`
			    	    	    SET script = '$string'
			    	    	    WHERE id = '$chat_id'");
			
		}
		
	}
	
}

echo("<style type=\"text/css\">
<!--
input {  font-family: Verdana; font-size: 11px; border: 1px #CFD7DA solid; padding-top: 1px; padding-right: 0px; padding-bottom: 0px; padding-left: 1px; margin-top: 2px; margin-right: 2px; margin-bottom: 2px; margin-left: 2px; color: #778890; font-weight: bold}
select {  font-family: Verdana; font-size: 11px; background-image: url(gfx/textfield-back.jpg); border: 1px #CFD7DA solid; padding-top: 1px; padding-right: 0px; padding-bottom: 0px; padding-left: 3px; margin-top: 2px; margin-right: 2px; margin-bottom: 2px; margin-left: 2px; color: #778890;font-weight: bold}
textarea {  font-family: Verdana; font-size: 11px; border: 1px #CFD7DA solid; padding-top: 1px; padding-right: 0px; padding-bottom: 0px; padding-left: 3px; margin-top: 2px; margin-right: 2px; margin-bottom: 2px; margin-left: 2px;  color: #778890; clip:  rect(2px 2px 2px 2px);}
td {  font-family: Verdana; font-size: 11px; color: #495760;text-align: justify}
.heading {  font-weight: bold; color: #495760}
A {color: #495760;text-decoration: none}
A:Hover {text-decoration: underline}
.difficulties {color: #990000}
.normal {color: #006633 }
-->
</style>");

//----------------------------------
# Now output the posting thingy

echo("<a name=bottom></a>
	<form action='$PHP_SELF' method=post>
	<input type=hidden name=post value=1>
	<input type=text name=message size=42> <input type=submit value='Send'>
	</form>");

?>