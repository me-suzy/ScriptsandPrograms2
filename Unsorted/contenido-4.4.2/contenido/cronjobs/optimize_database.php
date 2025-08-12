<?php

/*****************************************
* File      :   $RCSfile: optimize_database.php,v $
* Project   :   Contenido
* Descr     :   Cron Job to move old statistics into the stat_archive table
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   26.05.2003
* Modified  :   $Date: 2003/08/30 16:16:05 $
*
* © four for business AG, www.4fb.de
*
* $Id: optimize_database.php,v 1.2 2003/08/30 16:16:05 timo.hummel Exp $
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

foreach ($cfg["tab"] as $key => $value)
{
	$sql = "OPTIMIZE TABLE ".$value;
	$db->query($sql);
}
?>