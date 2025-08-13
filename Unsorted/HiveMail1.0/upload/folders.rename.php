<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: folders.rename.php,v $
// | $Date: 2002/10/28 18:17:29 $
// | $Revision: 1.7 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_folrenamed';
require_once('./global.php');

// ############################################################################
$folderid = getinfo('folder', $folderid, true);

$DB_site->query("
	UPDATE folder
	SET title = '".addslashes(urldecode($name))."'
	WHERE folderid = $folderid
");

eval(makeredirect("redirect_folrenamed", "folders.list.php"));

?>