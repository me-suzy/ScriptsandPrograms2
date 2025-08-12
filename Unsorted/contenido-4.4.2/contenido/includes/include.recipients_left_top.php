<?php

/******************************************
* File      :   include.stat_left_top.php
* Project   :   Contenido 
*
*
* Author    :   Timo A. Hummel
* Created   :   29.04.2003
* Modified  :   29.04.2003
*
* © four for business AG
******************************************/

$tpl->set('s', 'ID', 'oTplSel');
$tpl->set('s', 'CLASS', 'text_medium');
$tpl->set('s', 'OPTIONS', '');
$tpl->set('s', 'CAPTION', '');
$tpl->set('s', 'SESSID', $sess->id);

$tpl->set('s', 'ACTION', $select);

$tmp_mstr = '<a class="main" href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';
$area = "recipients";
$mstr = sprintf($tmp_mstr, 'right_top',
                                   $sess->url("main.php?area=recipients&frame=3"),
                                   'right_bottom',
                                   $sess->url("main.php?area=recipients&frame=4&action=recipients_createrecipient"),
                                   i18n("Create recipient"));
$tpl->set('s', 'NEWRECIPIENT', $mstr);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['recipient_left_top']);
?>
