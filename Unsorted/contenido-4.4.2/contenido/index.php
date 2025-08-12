<?php

/*****************************************
* File      :   main.php
* Project   :   Contenido
* Descr     :   Contenido main file
*
* Authors   :   Olaf Niemann
*               Jan Lengowski
*
* Created   :   20.01.2003
* Modified  :   23.01.2003
*
* © four for business AG, www.4fb.de
******************************************/
include_once ('./includes/config.php');

cInclude ("includes", 'functions.i18n.php');

cInclude ("classes", 'class.plugin.php');
cInclude ("classes", 'class.xml.php');
cInclude ("classes", 'class.navigation.php');
cInclude ("classes", 'class.template.php');
cInclude ("classes", 'class.backend.php');

cInclude ("includes", 'cfg_sql.inc.php');


page_open(
    array('sess' => 'Contenido_Session',
          'auth' => 'Contenido_Challenge_Crypt_Auth',
          'perm' => 'Contenido_Perm'));

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

cInclude ("includes", 'cfg_language_de.inc.php');
cInclude ("includes", 'functions.general.php');
cInclude ("includes", 'functions.forms.php');

$sess->register("belang");

// Create Contenido classes
$db  = new DB_Contenido;
$tpl = new Template;



// Sprache wechseln
if (is_numeric($changelang)) 
{ 
    $lang = $changelang;
}


// change Client
if (is_numeric($changeclient)){
     $client = $changeclient;
     unset($lang);
}

if (!is_numeric($client) || $client == "") {
    $sess->register("client");
    $sql = "SELECT idclient FROM ".$cfg["tab"]["clients"]." ORDER BY idclient ASC";
    $db->query($sql);
    $db->next_record();
    $client = $db->f("idclient");
} else {
    $sess->register("client");
}
    
if (!is_numeric($lang) || $lang == "") {
    $sess->register("lang");
    // search for the first language of this client
    $sql = "SELECT * FROM ".$cfg["tab"]["lang"]." AS A, ".$cfg["tab"]["clients_lang"]." AS B WHERE A.idlang=B.idlang AND A.active='1' AND idclient='$client' ORDER BY A.idlang ASC";
    $db->query($sql);
    $db->next_record();
    $lang = $db->f("idlang");
} else {
	$sess->register("lang");
}

$perm->load_permissions();


if (isset($area)) {
    $sess_area = $area;
} else {
    $area = (isset($sess_area)) ? $sess_area : 'login';
}

$tpl->reset();

$tpl->set('s', 'HEADER',    $sess->url('header.php?changelang='.$lang.'&changeclient='.$client));
$tpl->set('s', 'CONTENT',   $sess->url('main.php?area=login&frame=1&changelang='.$changelang.'&lang='.$lang.'&client='.$client));
$tpl->set('s', 'VERSION', $cfg["version"]);
$tpl->set('s', 'CONTENIDOPATH', $cfg["path"]["contenido_fullhtml"]."favicon.ico");
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['frameset']);

page_close();

?>
