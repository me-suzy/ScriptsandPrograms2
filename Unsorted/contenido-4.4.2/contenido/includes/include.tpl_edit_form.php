<?php

/******************************************
* File      :   includes.tpl_edit_form.php
*
* Author    :   Olaf Niemann
* Created   :   27.03.2003
* Modified  :   27.03.2003
*
* © four for business AG
******************************************/

$tpl2 = new Template();

if (($action == "tpl_new") && (!$perm->have_perm_area_action($area, $action)))
{
    $notification->displayNotification("error", i18n("Permission denied"));
} else {

$sql = "SELECT
        a.idtpl, a.name as name, a.description, a.idlay, b.description as laydescription
        FROM
        ".$cfg["tab"]["tpl"]." AS a
        LEFT JOIN
        ".$cfg["tab"]["lay"]." AS b
        ON a.idlay=b.idlay
        WHERE a.idtpl='$idtpl'
        ORDER BY name";

$db->query($sql);

$db->next_record();

	$idtpl          = $db->f("idtpl");
    $tplname        = $db->f("name");
	$description    = $db->f("description");
	$idlay          = $db->f("idlay");
	$laydescription = nl2br($db->f("laydescription"));

$sql = "SELECT
        number, idmod
        FROM
        ".$cfg["tab"]["container"]."
        WHERE
        idtpl='$idtpl'";

$db->query($sql);
while( $db->next_record() ) {
	$a_c[$db->f("number")] = $db->f("idmod");
}



$tpl->reset();

# Set static pointers
$tpl->set('s', 'ACTION',    $sess->url("main.php?area=$area&frame=$frame&action=tpl_edit"));
$tpl->set('s', 'IDTPL',     $idtpl);
$tpl->set('s', 'NAME',      $tplname);
$tpl->set('s', 'DESCR',     $description);




//*************** List layouts ****************	
$tpl2->set('s', 'NAME', 'idlay');
$tpl2->set('s', 'CLASS', 'text_medium');
$tpl2->set('s', 'OPTIONS', 'onchange="tplform.changelayout.value=1;tplform.submit();"');

if ($idlay != "0") {
    	$tpl2->set('d', 'VALUE', 0);
        $tpl2->set('d', 'CAPTION', $lngForm["nothing"] );
        $tpl2->set('d', 'SELECTED', '');
        $tpl2->next();
    } else {
    	$tpl2->set('d', 'VALUE', 0);
        $tpl2->set('d', 'CAPTION', $lngForm["nothing"] );
        $tpl2->set('d', 'SELECTED', 'selected');
        $tpl2->next();
}
$sql = "SELECT
        idlay, name
        FROM
        ".$cfg["tab"]["lay"]."
        WHERE
        idclient='$client'
        ORDER BY name";
        
$db->query($sql);

while ($db->next_record()) {
   	if ($db->f("idlay") != "$idlay") {
    	$tpl2->set('d', 'VALUE', $db->f("idlay"));
        $tpl2->set('d', 'CAPTION', $db->f("name") );
        $tpl2->set('d', 'SELECTED', '');
        $tpl2->next();
   	} else {
    	$tpl2->set('d', 'VALUE', $db->f("idlay"));
        $tpl2->set('d', 'CAPTION', $db->f("name") );
        $tpl2->set('d', 'SELECTED', 'selected');
        $tpl2->next();
   	}
}

$select = $tpl2->generate($cfg['path']['templates'] . $cfg['templates']['generic_select'], true);

$tpl->set('s', 'SELECTBOXLAYOUTS', $select);


$tpl->set('s', 'LAYOUTDESCRIPTION', $laydescription);
      	

$tpl2->reset();

if ($idlay) {
	$tmp_returnstring = tplBrowseLayoutForContainers($idlay);
	$a_container = explode("&",$tmp_returnstring);

	foreach ($a_container as $key=>$value) {
		if ($value != 0) {
			//*************** Loop through containers ****************
			$tpl->set('d', 'CAPTION', 'Container'.$value);

            $tpl2->set('s', 'NAME', "c[".$value."]");
            $tpl2->set('s', 'CLASS', 'text_medium');
            $tpl2->set('s', 'OPTIONS', '');

			if (isset($a_c[$value]) && $a_c[$value] != "0") {
            	$tpl2->set('d', 'VALUE', 0);
                $tpl2->set('d', 'CAPTION', $lngForm["nothing"] );
                $tpl2->set('d', 'SELECTED', '');
                $tpl2->next();
            } else {
            	$tpl2->set('d', 'VALUE', 0);
                $tpl2->set('d', 'CAPTION', $lngForm["nothing"] );
                $tpl2->set('d', 'SELECTED', 'selected');
                $tpl2->next();
    		}
			$sql = "SELECT
                    idmod, name
                    FROM
                    ".$cfg["tab"]["mod"]."
                    WHERE
                    idclient='$client'
                    ORDER BY name";
                    
			$db->query($sql);
			
			while ($db->next_record()) {
			
	              	if (!isset($a_c[$value])) $a_c[$value]=0;
                    if ($db->f("idmod") != $a_c[$value]) {
                        $tpl2->set('d', 'VALUE', $db->f("idmod"));
                        $tpl2->set('d', 'CAPTION', $db->f("name") );
                        $tpl2->set('d', 'SELECTED', '');
                        $tpl2->next();
      				} else {
                        $tpl2->set('d', 'VALUE', $db->f("idmod"));
                        $tpl2->set('d', 'CAPTION', $db->f("name") );
                        $tpl2->set('d', 'SELECTED', 'selected');
                        $tpl2->next();
    				}
			}

            $select = $tpl2->generate($cfg['path']['templates'] . $cfg['templates']['generic_select'], true);
            $tpl2->reset();


            $tpl->set('d', 'VALUE',  $select);
	        $tpl->next();	
		}
	}
}	


$tpl->set('s', 'SID', $sess->id);

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['tpl_edit_form']);



}


?>
