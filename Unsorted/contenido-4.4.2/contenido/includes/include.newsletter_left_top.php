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
$area = "news";
$mstr = sprintf($tmp_mstr, 'right_top',
                                   $sess->url("main.php?area=news&frame=3"),
                                   'right_bottom',
                                   $sess->url("main.php?area=news&frame=4&action=news_createnewsletter"),
                                   i18n("Create newsletter"));
$tpl->set('s', 'NEWNEWSLETTER', $mstr);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['newsletter_left_top']);
?>
