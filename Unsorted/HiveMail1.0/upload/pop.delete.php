<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: pop.delete.php,v $
// | $Date: 2002/10/28 18:17:30 $
// | $Revision: 1.7 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_popdeleted';
require_once('./global.php');

// ############################################################################
$pop = getinfo('pop', $popid);
$DB_site->query("
	DELETE FROM pop
	WHERE popid = $popid
");
$DB_site->query("
	UPDATE user
	SET haspop = haspop - 1
	WHERE userid = $hiveuser[userid]
");

eval(makeredirect("redirect_popdeleted", "pop.list.php"));

?>