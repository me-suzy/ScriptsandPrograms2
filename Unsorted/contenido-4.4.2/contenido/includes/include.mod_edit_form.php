<?php

/******************************************
* File      :   include.mod_edit_form.php
*
* Author    :   Olaf Niemann
* Created   :   21.01.2003
* Modified  :   21.01.2003
*
* © four for business AG
******************************************/

if (!isset($idmod)) $idmod = 0;

if (($action == "mod_new") && (!$perm->have_perm_area_action($area, $action)))
{
    $notification->displayNotification("error", i18n("No permission"));
} else {

    $sql = "SELECT
            *
            FROM
            ".$cfg['tab']['mod']."
            WHERE idclient = '".$client."'
            AND idmod = '".$idmod."'
            ORDER BY name ASC";
    
    $db->query($sql);
    $db->next_record();

    $tpl->reset();

    # Set static pointers
    $tpl->set('s', 'ACTION',    $sess->url("main.php?area=$area&frame=$frame&action=mod_edit"));
    $tpl->set('s', 'IDMOD',     $db->f('idmod'));
    $tpl->set('s', 'DESCR',     $db->f('description'));
    $tpl->set('s', 'CLASS',     'text_fullwidth_120');
    $tpl->set('s', 'NAME',      $db->f('name'));

	$inputok = modTestModule($db->f("input"), $db->f("idmod"). "i");

	if (!$inputok)
    {
    	$errorMessage = sprintf(i18n("Error in module. Error location: %s"),$modErrorMessage);
    	$tpl->set('d', 'ERROR', '<img src="images/but_online_no.gif" alt="'.$errorMessage.'" title="'.$errorMessage.'">');
    } else {
    	$okMessage = i18n("Module successfully compiled");
    	$tpl->set('d', 'ERROR', '<img src="images/but_online.gif" alt="'.$okMessage.'" title="'.$okMessage.'">');
    }
    # Set dynamic pointers
    $tpl->set('d', 'CAPTION', i18n("Input").':');
    $tpl->set('d', 'VALUE',   htmlspecialchars($db->f('input')));
    $tpl->set('d', 'CLASS', 'code_fullwidth');
    $tpl->set('d', 'NAME',    'input');
    $tpl->next();

    $outputok = modTestModule ($db->f("output"), $db->f("idmod") . "o",true);
    
    if (!$outputok)
    {
    	$errorMessage = sprintf(i18n("Error in module. Error location: %s"),$modErrorMessage);
    	$tpl->set('d', 'ERROR', '<img src="images/but_online_no.gif" alt="'.$errorMessage.'" title="'.$errorMessage.'">');
    } else {
    	$okMessage = i18n("Module successfully compiled");
    	$tpl->set('d', 'ERROR', '<img src="images/but_online.gif" alt="'.$okMessage.'" title="'.$okMessage.'">');
    }
    $tpl->set('d', 'CAPTION', i18n("Output").':');
    $tpl->set('d', 'VALUE',   htmlspecialchars($db->f('output')));
    $tpl->set('d', 'CLASS', 'code_fullwidth');
    $tpl->set('d', 'NAME',    'output');
    $tpl->next();
    
    /* Out.. not used JL
    $tpl->set('d', 'ERROR', '&nbsp;');
    $tpl->set('d', 'CAPTION', i18n("Template").':');
    $tpl->set('d', 'VALUE',   htmlspecialchars($db->f('template')));
    $tpl->set('d', 'CLASS', 'code_fullwidth');
    $tpl->set('d', 'NAME',    'template');
    $tpl->next();
    */

    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['mod_edit_form']);

}

?>
