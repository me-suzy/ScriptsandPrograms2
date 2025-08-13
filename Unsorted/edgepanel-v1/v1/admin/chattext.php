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

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Retrieve chat details

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]livechats`
		    WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

//----------------------------------

if($row_info[ 'admin_id' ] == 0) {
	
	//----------------------------------
	# Update chat for this admin
	
	$result = $db->Query("UPDATE `$CONF[table_prefix]livechats`
			    SET admin_id = '".$HTTP_COOKIE_VARS['admin_data']['3']."'
			    WHERE id = '$_SUBMIT[id]'");
	
	//----------------------------------
	
}

//----------------------------------
# Download the script

$full_script = $row_info['script'];

$entries = explode( ":::" , $full_script );

foreach($entries as $entry) {
	
	if(!empty($entry)) {
	
		$parts = explode( "||" , $entry );
		
		if($parts[0] == "You") {
			
			$parts[0] = "User";
			
		}
		else {
			
			$parts[0] = "You";
			
		}
	
		$script .= "<b>$parts[0]&gt;</b> $parts[1]<br>";
		
	}
	
}

//----------------------------------
# See if chat has now ended

if($row_info['closed'] == 1) {
	
	$script .= "The chat has ended.\n";
	
}

echo("<html>
<head>
<title>Text</title>
<style type=\"text/css\">
<!--
input {  font-family: Verdana; font-size: 11px; border: 1px #CFD7DA solid; padding-top: 1px; padding-right: 0px; padding-bottom: 0px; padding-left: 1px; margin-top: 2px; margin-right: 2px; margin-bottom: 2px; margin-left: 2px; color: #778890; font-weight: bold}
BODY {font-family: Verdana; font-size: 11px; color: #495760;text-align: justify
	scrollbar-face-color: #495760;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;}
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

if($row_info['closed'] != 1) {
	
	echo("<meta http-equiv='refresh' content='1; url=chattext.php?id=$_SUBMIT[id]'>");
	
}

echo("</head>
	<body bgcolor=#FBFBFB topmargin=0 leftmargin=0>
	$script

	<script language=javascript>
	 scrollTo(0,document.body.scrollHeight);
	</script>
	</body>
	</html>");

?>