<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: folders.rename.php,v $ - $Revision: 1.9 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_folrenamed';
require_once('./global.php');

// ############################################################################
$folderid = getinfo('folder', $folderid, true);

$DB_site->query("
	UPDATE hive_folder
	SET title = '".addslashes(urldecode($name))."'
	WHERE folderid = $folderid
");
rebuild_folder_cache();

eval(makeredirect("redirect_folrenamed", "folders.list.php"));

?>