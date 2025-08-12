<?php

/******************************************
* File      :   include.mod_overview.php
* Project   :   Contenido 
* Descr     :   Listet die module auf
*
* Author    :   Jan Lengowski
* Created   :   21.03.2003
* Modified  :   11.05.2003
*
* © four for business AG
******************************************/


$sql = "SELECT
        *
        FROM
        ".$cfg["tab"]["mod"]."
        WHERE
        idclient = '".$client."'
        ORDER BY name";
        
$db->query($sql);

$tpl->reset();

$tpl->set('s', 'SID', $sess->id);

while ($db->next_record()) {

   if($perm->have_perm_item($area,$db->f("idmod"))){      //idlay of area lay is 8
    $name  = htmlentities($db->f('name'));
    
    if ($name == "")
    {
    	$name = i18n("- Unnamed Module -");
    }
    
    $descr = htmlentities($db->f('description'));
    $idmod = $db->f('idmod');

    $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
    $tpl->set('d', 'BGCOLOR', $bgcolor);

    $inputcode = $db->f('input');
    $outputcode = $db->f('output');
    
    $inputok = modTestModule($inputcode, $db->f("idmod"). "i", false);
    $outputok = modTestModule ($outputcode, $db->f("idmod") . "o",true);
    
    if ($inputok && $outputok)
    {
    	// Currently, default color is none :)
		$colName = $name;    	
    } else {
    	if ($inputok || $outputok)
    	{
    		$colName = '<font color="#B1AC58">' . $name . '</font>';
    	} else {
    		$colName = '<font color="red">' . $name . '</font>';
    	}
    }
    if ($perm->have_perm_area_action_item("mod_edit","mod_edit",$db->f("idmod"))) {
        $tpl->set('d', 'NAME',  '<a target="right_bottom" title="'.$descr.'" href="'.$sess->url("main.php?area=mod_edit&frame=4&idmod=$idmod").'">'.$colName.'</a>');
    } else {
        $tpl->set('d', 'NAME', $colName);
    }
    $tpl->set('d', 'DESCR', $descr);

    $inUse = $classmodule->moduleInUse($db->f("idmod"));

    if ($perm->have_perm_area_action_item("mod","mod_delete",$db->f("idmod")))
    {
       $delDescription = i18n("No permission");
    }

    if ($inUse)
    {
        $delDescription = i18n("Module in use, cannot delete");        
    }
        
    if ($inUse)
        {
        	$inUseString = i18n("In use");
            $tpl->set('d', 'INUSE','<img src="'.$cfg['path']['images'].'exclamation.gif" border="0" title="'.$inUseString.'" alt="'.$inUseString.'">');
        } else {
            $tpl->set('d', 'INUSE','');    
        }
        
    if($perm->have_perm_area_action_item("mod","mod_delete",$db->f("idmod")) && !$inUse)
    {
    	$delTitle = i18n("Delete module");
        $delDescr = sprintf(i18n("Do you really want to delete the following module:<br><br>%s<br>"),$name);
            	
        $tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteModule('.$idmod.')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
    } else {

        $tpl->set('d', 'DELETE', '<img src="'.$cfg['path']['images'].'delete_inact.gif" border="0" title="'.$delDescription.'" alt="'.$delDescription.'">');
    }
    
    $tpl->set('d', 'ID', 'mod'.$tpl->dyn_cnt);
    
    $tpl->next();
    
}
}
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['mod_overview']);





?>
