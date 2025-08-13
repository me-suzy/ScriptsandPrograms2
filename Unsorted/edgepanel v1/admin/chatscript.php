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

//----------------------------------
# Output page

echo("<div class=heading>Live Chat</div>View and post messages below.<br><br>");

echo("<table width=100% cellpadding=5 cellspacing=0 style=\"border: 1px solid $_TEMPLATE[border_color]\">
	<tr><td height=140 bgcolor=#FBFBFB valign=top>
	<iframe src=chattext.php?id=$_SUBMIT[id] width=330 height=130 name=text scrolling=auto frameborder=0 name></iframe>
	</td></tr></table>");

?>