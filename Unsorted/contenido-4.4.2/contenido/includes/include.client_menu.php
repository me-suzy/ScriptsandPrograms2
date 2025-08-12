<?php        
/******************************************
* File      :   include.lang_menu.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Timo A. Hummel
* Created   :   08.05.2003
* Modified  :   08.05.2003
*
* © four for business AG
*****************************************/

$area="lang";
$tpl->set('s', 'SID', $sess->id);
if (!isset($action)) $action = "";

if ($action == "client_delete")
{
    if ($perm->have_perm_area_action("client", "client_delete")) {

   $sql = "DELETE FROM "
             .$cfg["tab"]["clients"].	
          " WHERE
             idclient = \"" .$idclient."\"";
   $db->query($sql);
    } 
          
}

$sql = "SELECT
        *
        FROM
        ".$cfg["tab"]["clients"];

$db->query($sql);

while ($db->next_record()) {
	$idclient = $db->f("idclient");
	if ((strpos($auth->auth["perm"],"admin[$idclient]") !== false) ||
		(strpos($auth->auth["perm"],"sysadmin") !== false))
	{
    	$dark = !$dark;
    	if ($dark) {
	        $bgColor = $cfg["color"]["table_dark"];
	    } else {
        	$bgColor = $cfg["color"]["table_light"];
    	}

        $tmp_mstr = '<a href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';
        $area = "lang";
        $idclient = $db->f("idclient");
        $mstr = sprintf($tmp_mstr, 'right_top',
                                       $sess->url("main.php?area=$area&frame=3&idclient=$idclient"),
                                       'right_bottom',
                                       $sess->url("main.php?area=client_edit&frame=4&idclient=$idclient"),
                                       $db->f("name") );
        if (!$classclient->hasLanguageAssigned($idclient) && $perm->have_perm_area_action('client',"client_delete") ) {
       		$delTitle = i18n("Delete client");
        	$delDescr = sprintf(i18n("Do you really want to delete the following client:<br><br>%s<br>"),$db->f("name"));
    
        	$tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteClient(\\\''.$idclient.'\\\')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
        		
            } else {
                $tpl->set('d', 'DELETE', '&nbsp;');
            }
    
        $tpl->set('d', 'BGCOLOR', $bgColor);
        $tpl->set('d', 'TEXT', $mstr);
        $tpl->next();
	}
}    
# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['client_menu']);


?>
