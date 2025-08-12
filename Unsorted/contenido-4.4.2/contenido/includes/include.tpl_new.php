<?php
/******************************************
* File      :   include.tpl_new.php
* Project   :   Contenido 
* Descr     :   Link für "neues Template"
*
* Author    :   Olaf Niemann
* Created   :   27.03.2003
* Modified  :   27.03.2003
*
* © four for business AG
******************************************/
$tpl->reset();
$tpl->set('s', 'ACTION', '<a class="main" target="right_bottom" href="'.$sess->url("main.php?area=tpl_edit&frame=4&action=tpl_new").'">'.i18n("New template").'</a>');
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['left_top']);
?>
