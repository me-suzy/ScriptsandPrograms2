<?php

/******************************************
* File      :   include.mod_show_modules.php
* Project   :   Contenido 
* Descr     :   Listet die module auf
*
* Author    :   Jan Lengowski
* Created   :   21.03.2003
* Modified  :   21.03.2003
*
* © four for business AG
******************************************/

$tpl->reset();

$str  = '<table cellspacing="0" cellpadding="0" border="0">';
$str .= '<tr>';
$str .= '<td><a class="main" target="right_bottom" href="'.$sess->url("main.php?area=mod_edit&frame=4&action=mod_new").'"><img src="images/but_module_new.gif" width="21" height="15" border="0"></a></td><td>&nbsp;</td><td><a class="main" target="right_bottom" href="'.$sess->url("main.php?area=mod_edit&frame=4&action=mod_new").'">'.i18n("New module").'</a></a>';
$str .= '</tr>';
$str .= '</table>';


$tpl->set('s', 'ACTION', $str);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['left_top']);





?>
