<?
// ----------------------------------------------------------------------
// ModName: info.php
// Purpose: Show all information about the PHP environment
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

if (!IsUserAdmin())
	RedirectToPreviousPage();

phpinfo();
//phpinfo(INFO_MODULES);

?>

