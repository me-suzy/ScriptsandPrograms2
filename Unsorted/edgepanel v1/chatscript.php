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
# Now we need to output the page
# firstly checking if we are connected
# to chat yet

echo("<style type=\"text/css\">
<!--
input {  font-family: Verdana; font-size: 11px; border: 1px #CFD7DA solid; padding-top: 1px; padding-right: 0px; padding-bottom: 0px; padding-left: 1px; margin-top: 2px; margin-right: 2px; margin-bottom: 2px; margin-left: 2px; color: #778890; font-weight: bold}
BODY {font-family: Verdana; font-size: 11px; color: #495760;text-align: justify}
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


echo("<html><head><title>Hi</title></head>");

echo("<body>");

echo("<div class=heading>Live Chat</div><br>View and post messages below.<br><br>");

//----------------------------------

echo("<table cellpadding=5 cellspacing=0 style=\"border: 1px solid #495760\" width=100% bgcolor=#CFD4D6>
	<tr><td height=150 valign=top>
	<iframe src=chattext.php?id=$_SUBMIT[id] width=330 height=140 name=text scrolling=auto frameborder=0 name></iframe>
	</td></tr></table></body></html>");

?>