<?php

/******************************************
* File      :   include.con_left_top.php
* Project   :   Contenido 
* Descr     :   Misc. functions for area
*               con
*
* Author    :   Jan Lengowski
* Created   :   26.03.2003
* Modified  :   26.03.2003
*
* © four for business AG
******************************************/

$sql = "SELECT
            idtpl,
            name
        FROM
            ".$cfg['tab']['tpl']."
        WHERE
            idclient = '".$client."'
        ORDER BY
            name";

$db->query($sql);

$tpl->reset();

$tpl->set('s', 'ID',        'oTplSel');
$tpl->set('s', 'CLASS',     'text_medium');
$tpl->set('s', 'OPTIONS',   '');
$tpl->set('s', 'CAPTION',   i18n("Choose template"));
$tpl->set('s', 'SESSID',    $sess->id);
$tpl->set('s', 'BELANG', $belang);

$tpl->set('d', 'VALUE',     '0');
$tpl->set('d', 'CAPTION',   i18n("--- None ---"));
$tpl->set('d', 'SELECTED',  '');

$tpl->next();

while ($db->next_record()) {

    $tplname = $db->f('name');

    if (strlen($tplname) > 18)
    {
        $tplname = substr($tplname, 0, 15) . "...";
    }
    $tpl->set('d', 'VALUE', $db->f('idtpl'));
    $tpl->set('d', 'CAPTION', $tplname);
    $tpl->set('d', 'SELECTED', '');
    $tpl->next();
}

$select = $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_select'], true);

$tpl->set('s', 'ACTION', $select);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['con_left_top']);



?>
