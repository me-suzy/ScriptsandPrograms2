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
* Modified  :   21.03.2003
*
* © four for business AG, www.4fb.de
******************************************/

include_once ('./includes/config.php');
include_once ($cfg["path"]["classes"] . 'class.template.php');

page_open(
    array('sess' => 'Contenido_Session',
          'auth' => 'Contenido_Challenge_Crypt_Auth',
          'perm' => 'Contenido_Perm'));

cInclude ("includes", 'functions.i18n.php');

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

cInclude ("includes", 'cfg_sql.inc.php');
cInclude ("includes", 'cfg_language_de.inc.php');
cInclude ("includes", 'functions.general.php');
cInclude ("includes", 'functions.forms.php');

# Create Contenido classes
$db  = new DB_Contenido;
$tpl = new Template;

# Build the Contenido
# Content area frameset
$tpl->reset();

$tpl->set('s', 'FRAME[1]', $sess->url("main.php?area=$area&frame=1"));
$tpl->set('s', 'FRAME[2]', $sess->url("main.php?area=$area&frame=2"));
$tpl->set('s', 'FRAME[3]', $sess->url("main.php?area=$area&frame=3"));
$tpl->set('s', 'FRAME[4]', $sess->url("main.php?area=$area&frame=4"));
$tpl->set('s', 'VERSION', $cfg['version']);

/* Hide menu-frame for some areas */
$menuless_areas = array("str", "logs", "debug", "system");

if ( in_array($area, $menuless_areas) ) {
    $menuless = true;
}

$tpl->set('s', 'CONTENIDOPATH', $cfg["path"]["contenido_fullhtml"]."favicon.ico");

if ($menuless) {
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['frameset_menuless_content']);
} else {
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['frameset_content']);
}

page_close();

?>
