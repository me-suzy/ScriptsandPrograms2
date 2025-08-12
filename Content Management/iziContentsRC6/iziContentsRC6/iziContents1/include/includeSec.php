<?php

/***************************************************************************

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

function includeExternalLink ($linkref)
{
	if ($linkref != '') {
		if ( (substr($linkref,0,5) == 'http:')		|| (substr($linkref,0,6) == 'https:')	||
			 (substr($linkref,0,5) == 'file:')		|| (substr($linkref,0,4) == 'ftp:')		||
			 (substr($linkref,0,7) == 'gopher:')	|| (substr($linkref,0,7) == 'mailto:')	||
			 (substr($linkref,0,5) == 'news:')		|| (substr($linkref,0,7) == 'telnet:')	||
			 (substr($linkref,0,5) == 'wais:') ) {
			 return True;
		} else {
			return False;
		}
	} else {
		return False;
	}
} // includeExternalLink


if (!(isset($GLOBALS["rootdp"]))) {
	 ECHO 'Remote Code Execution Patch Installed on this implementation of ezContents';
	 DIE;
}
if (includeExternalLink($GLOBALS["rootdp"])) {
	ECHO 'Remote Code Execution Patch Installed on this implementation of ezContents';
	DIE;
}

?>