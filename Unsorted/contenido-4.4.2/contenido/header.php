<?php

/******************************************
* File      :   header.php
* Project   :   Contenido 
* Descr     :   Header file
*
* Author    :   Jan Lengowski
* Created   :   18.03.2003
* Modified  :   18.03.2003
*
* © four for business AG
******************************************/
include_once ('./includes/config.php');

cInclude ("includes", 'functions.i18n.php');

cInclude ("classes", 'class.plugin.php');
cInclude ("classes", 'class.xml.php');
cInclude ("classes", 'class.navigation.php');
cInclude ("classes", 'class.template.php');
cInclude ("classes", 'class.backend.php');
cInclude ("classes", 'class.user.php');
cInclude ("classes", 'class.client.php');

$db = new DB_Contenido;


page_open(
    array('sess' => 'Contenido_Session',
          'auth' => 'Contenido_Challenge_Crypt_Auth',
          'perm' => 'Contenido_Perm'));

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

cInclude ("includes", 'cfg_sql.inc.php');
cInclude ("includes", 'cfg_language_de.inc.php');
cInclude ("includes", 'functions.general.php');
cInclude ("includes", 'functions.forms.php');

if (isset($killperms))
{
    $sess->unregister("right_list");
    $sess->unregister("area_rights");
    $sess->unregister("item_rights");
}

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

$sess->register("sess_area");

if (isset($area)) {
    $sess_area = $area;
} else {
    $area = (isset($sess_area)) ? $sess_area : 'login';
}

if (is_numeric($changelang)) {
	unset($area_rights);
	unset($item_rights);
	
    $sess->register("lang");
    $lang = $changelang;
}


if (!is_numeric($client)) { // use first client found
    $sess->register("client");
    $sql = "SELECT idclient FROM ".$cfg["tab"]["clients"]." ORDER BY idclient ASC";
    $db->query($sql);
    $db->next_record();
    $client = $db->f("idclient");
} else {
	$sess->register("client");
}

if (!is_numeric($lang)) { // use first language found
    $sess->register("lang");
    $sql = "SELECT * FROM ".$cfg["tab"]["lang"]." AS A, ".$cfg["tab"]["clients_lang"]." AS B WHERE A.idlang=B.idlang AND A.active='1' AND idclient='$client' ORDER BY A.idlang ASC";
    $db->query($sql);
    $db->next_record();
    $lang = $db->f("idlang");
} else {
    $sess->register("lang");
}

$perm->load_permissions();

$plugin     = new Plugin;
$xml        = new XML_doc;
$tpl        = new Template;
$nav        = new Contenido_Navigation;

$nav->buildHeader($lang);

page_close();

?>

