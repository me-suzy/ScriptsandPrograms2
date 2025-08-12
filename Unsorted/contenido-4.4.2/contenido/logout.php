<?php

/*****************************************
* File      :   $RCSfile: logout.php,v $
* Project   :   Contenido
* Descr     :   Contenido Logout function
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   20.05.2003
* Modified  :   $Date: 2003/10/15 13:08:19 $
*
* © four for business AG, www.4fb.de
*
* $Id: logout.php,v 1.6 2003/10/15 13:08:19 timo.hummel Exp $
******************************************/

include_once ('./includes/config.php');

cInclude ("includes", 'functions.i18n.php');

cInclude("classes", 'class.user.php');
cInclude("classes", 'class.plugin.php');
cInclude("classes", 'class.xml.php');
cInclude("classes", 'class.navigation.php');
cInclude("classes", 'class.template.php');
cInclude("classes", 'class.backend.php');
cInclude("classes", 'class.table.php');
cInclude("classes", 'class.notification.php');
cInclude("classes", 'class.area.php');
cInclude("classes", 'class.module.php');
cInclude("classes", 'class.layout.php');
cInclude("classes", 'class.client.php');
cInclude("classes", 'class.cat.php');

page_open(array('sess' => 'Contenido_Session',
                'auth' => 'Contenido_Challenge_Crypt_Auth',
                'perm' => 'Contenido_Perm'));

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);

cInclude("includes",  'cfg_sql.inc.php');
cInclude("includes",   'cfg_language_de.inc.php');
cInclude("includes",   'functions.general.php');
cInclude("includes",   'functions.i18n.php');
cInclude("includes",   'functions.forms.php');

$auth->logout();
page_close();
$sess->delete();
header("location:index.php");

?>
