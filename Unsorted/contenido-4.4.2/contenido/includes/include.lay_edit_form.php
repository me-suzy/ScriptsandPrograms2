<?php

/******************************************
* File      :   include.lay_edit_form.php
*
* Author    :   Olaf Niemann
* Created   :   24.01.2003
* Modified  :   24.01.2003
*
* © four for business AG
******************************************/

if (!isset($idlay)) $idlay = 0;

if (($action == "lay_new") && (!$perm->have_perm_area_action($area, $action)))
{
    $notification->displayNotification("error", i18n("Permission denied"));
} else {

    $sql = "SELECT
            *
            FROM
            ".$cfg['tab']['lay']."
            WHERE idclient = '".$client."'
            AND idlay = '".$idlay."'
            ORDER BY name ASC";
    
    $db->query($sql);
    $db->next_record();
    
    $tpl->reset();

    # Set static pointers
    $tpl->set('s', 'ACTION',    $sess->url("main.php?area=$area&frame=$frame&action=lay_edit"));
    $tpl->set('s', 'IDLAY',     $db->f('idlay'));
    $tpl->set('s', 'DESCR',     $db->f('description'));
    $tpl->set('s', 'CLASS', 'code_sfullwidth');
    $tpl->set('s', 'NAME',      $db->f('name'));
    
    # Set dynamic pointers
    $tpl->set('d', 'CAPTION', i18n("Code").':');
    $tpl->set('d', 'VALUE',   htmlspecialchars($db->f('code')));
    $tpl->set('d', 'CLASS', 'code_fullwidth');
    $tpl->set('d', 'NAME',    'code');
    $tpl->next();
    
    
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['lay_edit_form']);


}
?>
