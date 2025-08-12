<?php        
/******************************************
* File      :   include.lang_overview.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Olaf Niemann
* Created   :   02.04.2003
* Modified  :   02.04.2003
*
* © four for business AG
*****************************************/

$area="lang";

if (!isset($action)) $action = "";

if (!is_numeric($targetclient))
{
	$targetclient = $client;
}

$sql = "SELECT
        *
        FROM
        ".$cfg["tab"]["lang"]." AS A,
        ".$cfg["tab"]["clients_lang"]." AS B
        WHERE
        A.idlang=B.idlang AND
        B.idclient='$targetclient'
        ORDER BY A.idlang";
        
$db->query($sql);

$tpl->set('s','TARGETCLIENT',$targetclient);

while ($db->next_record()) {

    $idlang = $db->f("idlang");

    if ($db->f("active") == 0) {
         //activate
        $message = i18n("Activate language");
        $active = "<a title=\"$message\" href=\"".$sess->url("main.php?area=$area&action=lang_activatelanguage&frame=$frame&targetclient=$targetclient&idlang=".$db->f("idlang"))."#clickedhere\"><img src=\"".$cfg["path"]["images"]."offline.gif"."\" border=\"0\" title=\"$message\" alt=\"$message\"></a>";
    } else {
        //deactivate
		$message = i18n("Deactivate language");
        $active = "<a title=\"$message\" class=action href=\"".$sess->url("main.php?area=$area&action=lang_deactivatelanguage&frame=$frame&targetclient=$targetclient&idlang=".$db->f("idlang"))."#clickedhere\"><img src=\"".$cfg["path"]["images"]."online.gif"."\" border=\"0\" title=\"$message\" alt=\"$message\"></a>";
    }

    // Delete Button
    $deleteMsg = sprintf(i18n("Do you really want to delete the language %s?"),$db->f("name"));
    $deleteAct = i18n("Delete language");
    $deletebutton = '<a title="'.$deleteAct.'" href="javascript://" onclick="box.confirm(\''.$deleteAct.'\', \''.$deleteMsg.'\', \'deleteLang('.$db->f("idlang").')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$deleteAct.'" alt="'.$deleteAct.'"></a>';

    $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
    
    $tpl->set('d', 'BGCOLOR',       $bgcolor);
    $tpl->set('d', 'LANGUAGE',      '<a target="right_bottom" href="'.$sess->url("main.php?area=lang_edit&idlang=$idlang&frame=4").'">'.$db->f("name").'</a>&nbsp;<span style="font-size:10px">('.$idlang.')</span>');
    $tpl->set('d', 'ACTIVATEBUTTON',  $active);
    $tpl->set('d', 'DELETEBUTTON',  $deletebutton);
    
    $tpl->next();
}

$newlanguageform = '<form name=newlanguage method="post" action="'.$sess->url("main.php?area=$area&frame=$frame").'">
                    '.$sess->hidden_session().'
                    <input type="hidden" name="action" value="lang_newlanguage">
                    <table cellpadding="0" cellspacing="0" border="0">
                    <tr><td class="text_medium">'.i18n("New language").':
                    <INPUT type="text" name="name">&nbsp;&nbsp;&nbsp;
                    <INPUT type="image" src="'.$cfg['path']['images'].'but_ok.gif" border="0">
                    </td></tr></table></from>';

$tpl->set('s', 'NEWLANGUAGEFORM', $newlanguageform);
$tpl->set('s', 'SID', $sess->id);

if ( $tmp_notification ) {

    $noti_html = '<tr><td colspan="3">'.$tmp_notification.'</td></tr>';
    $tpl->set('s', 'NOTIFICATION', $noti_html);

} else {

    $tmp_notification = $notification->returnNotification("info", i18n("Language deleted"));
    
    $noti_html = '<tr><td colspan="3">'.$tmp_notification.'</td></tr>';
    $tpl->set('s', 'NOTIFICATION', '');
    
}

# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['lang_overview']);


?>
