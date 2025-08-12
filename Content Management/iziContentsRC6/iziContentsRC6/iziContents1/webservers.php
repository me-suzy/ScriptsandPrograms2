<?php

/***************************************************************************

 webservers.php
 ---------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

if (strpos($_SERVER["SERVER_SOFTWARE"], 'Apache') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/apache.gif','Powered by Apache',0,'C');
} elseif ((strpos($_SERVER["SERVER_SOFTWARE"], 'Microsoft-IIS') !== FALSE) || (strpos($_SERVER["SERVER_SOFTWARE"], 'Microsoft-Internet-Information-Server') !== FALSE)) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/microsoft_IIS.gif','Powered by Microsoft IIS',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Microsoft-PWS') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/microsoft_PWS.gif','Powered by Microsoft PWS',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'iPlanet') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/iplanet.gif','Powered by iPlanet',0,'C');
} elseif ((strpos($_SERVER["SERVER_SOFTWARE"], 'Netscape') !== FALSE) || (strpos($_SERVER["SERVER_SOFTWARE"], 'Netsite') !== FALSE)) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/netscape.gif','Powered by Netscape',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Zeus') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/zeus.gif','Powered by Zeus',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'WebSTAR') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/webstar.gif','Powered by WebSTAR',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Website') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/website.gif','Powered by Website',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'tigershark') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/tigershark.gif','Powered by Tigershark',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'thttpd') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/thttpd.gif','Powered by thttpd',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Rapidsite') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/rapidsite.gif','Powered by Rapidsite',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Domino') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/domino.gif','Powered by Lotus Domino',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Stronghold') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/stronghold.gif','Powered by Stronghold',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'IBM-HTTP') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/ibm.gif','Powered by IBM http Server',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'AOLserver') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/aol.gif','Powered by AOLserver',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Xitami') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/xitami.gif','Powered by Xitami',0,'C');
} elseif (strpos($_SERVER["SERVER_SOFTWARE"], 'Zope') !== FALSE) {
	echo imagehtmltag($GLOBALS["icon_home"],'platforms/zope.gif','Powered by Zope',0,'C');
} else {
	echo '&nbsp;';
}

?>
