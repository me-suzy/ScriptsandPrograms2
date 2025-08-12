<?php

/*****************************************
* File      :   $RCSfile: move_old_stats.php,v $
* Project   :   Contenido
* Descr     :   Cron Job to move old statistics into the stat_archive table
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   26.05.2003
* Modified  :   $Date: 2003/11/10 10:42:57 $
*
* © four for business AG, www.4fb.de
*
* $Id: move_old_stats.php,v 1.4.2.1 2003/11/10 10:42:57 timo.hummel Exp $
******************************************/

include_once ('../includes/config.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.user.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.plugin.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.xml.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.navigation.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.template.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.backend.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.table.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.notification.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.area.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.module.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.layout.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.client.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.cat.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["classes"] . 'class.treeitem.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["includes"] . 'cfg_sql.inc.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["includes"] . 'cfg_language_de.inc.php');
include_once ($cfg['path']['contenido'].$cfg["path"]["includes"] . 'functions.general.php');
include_once ('../includes/functions.stat.php');

$db = new DB_Contenido;
$year = date("Y");
$month = date("m");

if ($month == 1)
{
	$month = 12;
	$year = $year -1;
} else {
	$month = $month -1;
}
statsArchive(sprintf("%04d%02d",$year,$month));

?>