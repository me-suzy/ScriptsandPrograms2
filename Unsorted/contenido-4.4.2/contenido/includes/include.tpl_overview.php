<?php

/******************************************
* File      :   include.tpl_overview.php
* Project   :   Contenido 
* Descr     :   Shows all templates in the
*               left frame
*
* Author    :   Jan Lengowski
* Created   :   27.03.2003
* Modified  :   11.05.2003
*
* © four for business AG
******************************************/

$sql = "SELECT
            *
        FROM
            ".$cfg["tab"]["tpl"]."
        WHERE
            idclient = '".$client."'
        ORDER BY
            name";

$db->query($sql);
$tpl->reset();

$tpl->set('s', 'SID', $sess->id);

while ( $db->next_record() ) {

    if ( $perm->have_perm_item($area, $db->f("idtpl")) ) {         //idarea of area tpl is 12

        $name  = htmlentities($db->f('name'));
        $descr = htmlentities($db->f('description'));
        $idtpl = $db->f("idtpl");

        $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
        $tpl->set('d', 'BGCOLOR', $bgcolor);

        # create javascript multilink
        $tmp_mstr = '<a title="'.$descr.'" href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';

        $mstr = sprintf($tmp_mstr, 'right_top',
                                   $sess->url("main.php?area=tpl&frame=3&idtpl=$idtpl"),
                                   'right_bottom',
                                   $sess->url("main.php?area=tpl_edit&frame=4&idtpl=$idtpl"),
                                   $name);




        if ($perm->have_perm_area_action_item("tpl_edit","tpl_edit",$db->f("idtpl"))) {
            $tpl->set('d', 'NAME',  $mstr);
        } else {
            $tpl->set('d', 'NAME', $name);
        }

             /* Check if template is in use */
            $inUse = tplIsTemplateInUse($idtpl);

            if (!$inUse && ($perm->have_perm_area_action_item("tpl","tpl_delete",$db->f("idtpl")))) {
            	$delTitle = i18n("Delete template");
        		$delDescr = sprintf(i18n("Do you really want to delete the following template:<br><br>%s<br>"),$name);
            
                $tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteTemplate('.$idtpl.')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
                
            } else {
                $tpl->set('d', 'DELETE','<img src="'.$cfg['path']['images'].'delete_inact.gif">');
            }

           if ($perm->have_perm_area_action_item("tpl","tpl_dup", $db->f("idtpl"))) {
                $copybutton = '<a href="'.$sess->url("main.php?area=$area&action=tpl_duplicate&idtpl=$idtpl&frame=$frame").'" title="'.i18n("Duplicate template").'"><img src="'.$cfg["path"]["images"].'but_copy.gif'.'" border="0" title="'.i18n("Duplicate template").'" alt="'.i18n("Duplicate template").'"></a>';
                        
           } else {
               $copybutton = '<img src="images/spacer.gif" width="14" height="1">';
           }

           $tpl->set('d', 'COPY', $copybutton);
           $tpl->set('d', 'ID', 'tpl'.$tpl->dyn_cnt);

        $tpl->next();
    }
}

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['tpl_overview']);

?>
