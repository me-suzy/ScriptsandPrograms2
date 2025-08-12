<?php

/******************************************
* File      :   include.upl_left_top.php
* Project   :   Contenido 
* Descr     :
*
* Author    :   Olaf Niemann
* Created   :   01.04.2003
* Modified  :   01.04.2003
*
* © four for business AG
******************************************/


$tpl->set('s', 'FORMACTION', '');

if ($perm->have_perm_area_action("upl","upl_mkdir") ) {
    $tpl->set('s', 'CAPTION', i18n("New directory in"));
    $inputfield = '<input type="hidden" name="path" value="'.$path.'">
                   <input class="text_small" type="text" name="foldername" value="" onChange="document.forms[0].submit();">';
    $tpl->set('s', 'TARGET', 'onSubmit="parent.frames[1].location.href=\''.$sess->url("main.php?area=upl&action=upl_mkdir&frame=2").'&path=\'+document.forms[0].path.value+\'&foldername=\'+document.forms[0].foldername.value;"');
    $tpl->set('s', 'SUBMIT', '<input type="image" src="'.$cfg["path"]["htmlpath"].'images/submit.gif">');
} else {
    $tpl->set('s', 'CAPTION', '');
    $inputfield = '';
    $tpl->set('s', 'TARGET', '');
    $tpl->set('s', 'SUBMIT', '');
}


$tpl->set('s', 'ACTION', $inputfield);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['upl_left_top']);


?>
