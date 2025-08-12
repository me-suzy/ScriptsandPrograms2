<?php

/******************************************
* File      :   include.lay_overview.php
* Project   :   Contenido
* Descr     :   Listet die layouts auf
*
* Author    :   Olaf Niemann
* Created   :   27.03.2003
* Modified  :   27.03.2003
*
* © four for business AG
******************************************/

$sql = "SELECT
        *
        FROM
        ".$cfg["tab"]["lay"]."
        WHERE
        idclient = '".$client."'
        ORDER BY name";

$db->query($sql);

$tpl->reset();

$tpl->set('s', 'SID', $sess->id);

while ($db->next_record()) {

    if($perm->have_perm_area_action_item("lay_edit","lay_edit",$db->f("idlay"))){      //idlay of area lay is 8

        $name  = htmlentities($db->f('name'));
        $descr = htmlentities($db->f('description'));
        $idlay = $db->f('idlay');

        if (strlen($descr)  > 64) {
            $descr = substr($descr, 0, 64);
            $descr .= ' ..';
        }

        //action 20 is editlayout

        if ($perm->have_perm_area_action_item("lay_edit","lay_edit",$db->f("idlay"))) {
            $tpl->set('d', 'NAME',  '<a target="right_bottom" href="'.$sess->url("main.php?area=lay_edit&frame=4&idlay=$idlay").'" title="'.$descr.'">'.$name.'</a>');
        } else {
            $tpl->set('d', 'NAME',  $name);
        }
        $inUse = $classlayout->layoutInUse($db->f("idlay"));

        if ($darkrow)
        {
            $bgColor = $cfg["color"]["table_dark"];
        } else {
            $bgColor = $cfg["color"]["table_light"];
        }

        $darkrow = !$darkrow;
        $tpl->set('d', 'BGCOLOR', $bgColor);

        if ($db->f("deletable") == 0)
        {
            $delDescription = i18n("Layout is not deleteable");
        }
        
        if ((!$perm->have_perm_area_action_item("lay","lay_delete",$db->f("idlay"))) && (!$perm->have_perm_area_action("lay","lay_delete")))
        {
            $delDescription = i18n("No permission");
        }

        if ($inUse)
        {
        	$delDescription = i18n("Layout is in use, cannot delete");
        	$inUseDescription = i18n("Layout is in use");
            $tpl->set('d', 'INUSE','<img src="'.$cfg['path']['images'].'exclamation.gif" border="0" title="'.$inUseDescription.'" alt="'.$inUseDescription.'">');
        } else {
            $tpl->set('d', 'INUSE','');    
        }
        
        if (
            $db->f("deletable") == 1 &&
            $perm->have_perm_area_action_item("lay","lay_delete",$db->f("idlay")) &&
            !$inUse)
            {
            	$delTitle = i18n("Delete layout");
            	$delDescr = sprintf(i18n("Do you really want to delete the following layout:<br><br>%s<br>"),$name);
            	
                $tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteLayout('.$idlay.')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
        } else {
            $tpl->set('d', 'DELETE','<img src="'.$cfg['path']['images'].'delete_inact.gif" border="0" title="'.$delDescription.'" alt="'.$delDescription.'">');
        }
        
        $tpl->set('d', 'ID', 'lay'.$tpl->dyn_cnt);

        $tpl->next();

    }
}
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['lay_overview']);





?>
