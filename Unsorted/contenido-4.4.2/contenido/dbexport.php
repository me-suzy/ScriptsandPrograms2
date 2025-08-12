<?php

/*****************************************
* File      :   dbexport.php
* Project   :   Contenido
* Descr     :   Contenido database exporter
*
* Authors   :   Timo A. Hummel
*
* Created   :   17.06.2003
* Modified  :   17.06.2003
*
* © four for business AG, www.4fb.de
******************************************/

/*****************************************
  WARNING - THIS FILE EXPORTS THE CURRENT
  DATABASE IN ORDER TO CREATE AN UPGRADE-
  ABLE CONTENIDO VERSION. THIS FILE IS
  ONLY THOUGHT TO BE USED BY THE CONTENIDO
  DEVELOPERS, NOT BY END-USERS.
  IT DOES -NOT- EXPORT YOUR ARTICLES!
******************************************/

die("Access denied");

include_once ('./includes/config.php');
include_once ($cfg["path"]["classes"] . 'class.user.php');
include_once ($cfg["path"]["classes"] . 'class.plugin.php');
include_once ($cfg["path"]["classes"] . 'class.xml.php');
include_once ($cfg["path"]["classes"] . 'class.navigation.php');
include_once ($cfg["path"]["classes"] . 'class.template.php');
include_once ($cfg["path"]["classes"] . 'class.backend.php');
include_once ($cfg["path"]["classes"] . 'class.table.php');
include_once ($cfg["path"]["classes"] . 'class.notification.php');
include_once ($cfg["path"]["classes"] . 'class.area.php');
include_once ($cfg["path"]["classes"] . 'class.module.php');
include_once ($cfg["path"]["classes"] . 'class.layout.php');
include_once ($cfg["path"]["classes"] . 'class.client.php');
include_once ($cfg["path"]["classes"] . 'class.cat.php');
include_once ($cfg["path"]["classes"] . 'class.treeitem.php');

include ($cfg["path"]["includes"] . 'cfg_sql.inc.php');
include ($cfg["path"]["includes"] . 'cfg_language_de.inc.php');
include ($cfg["path"]["includes"] . 'functions.general.php');
include ($cfg["path"]["includes"] . 'functions.str.php');
include ($cfg["path"]["includes"] . 'functions.con.php');
include ($cfg["path"]["includes"] . 'functions.database.php');

# Create Contenido classes
$db = new DB_Contenido;
$notification = new Contenido_Notification;
$classarea = new Area();
$classmodule = new Module();
$classlayout = new Layout();
$classclient = new Client();
$classuser = new User();
$classcat = new Cat();

$client = 1;
$lang = 1;

class DB_Upgrade extends DB_Contenido {};

foreach ($cfg["tab"] as $key => $value)
{
	dbDumpStructure($key);
}
?>