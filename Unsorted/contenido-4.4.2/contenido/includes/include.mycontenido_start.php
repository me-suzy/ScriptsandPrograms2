<?
/******************************************
* File      :   include.rights_create.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Timo A. Hummel
* Created   :   30.04.2003
* Modified  :   07.05.2003
*
* © four for business AG
*****************************************/

    $tpl->set('d', 'CONTENTTEXT', "<b>Willkommen im MyContenido-Bereich.</b>");
    $tpl->next();
    $tpl->set('d', 'CONTENTTEXT', 'In diesem Bereich können Sie allerlei tolle Sachen anstellen - wenn jemand diesen sinnfreien Text mal überarbeitet.');
    $tpl->next();
    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['mycontenido_start']);
?>
