<?php

function moduleExternalLink ($linkref)
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
} // moduleExternalLink


if (!(isset($GLOBALS["rootdp"]))) {
	 ECHO 'Remote Code Execution Patch Installed on this implementation of ezContents';
	 DIE;
}
if ( (moduleExternalLink($GLOBALS["rootdp"])) || (moduleExternalLink($GLOBALS["modfiledir"])) ||
	 (moduleExternalLink($GLOBALS["modules_home"])) || (moduleExternalLink($GLOBALS["admin_home"])) ||
	 (moduleExternalLink($GLOBALS["language_home"])) ) {
	 ECHO 'Remote Code Execution Patch Installed on this implementation of ezContents';
	 DIE;
}

?>