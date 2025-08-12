<?php
/******************************************
* File      :   include.lang_edit.php
* Project   :   Contenido
* Descr     :   Displays rights
*
* Author    :   Timo A. Hummel
*               Jan Lengowski
*
* Created   :   30.04.2003
* Modified  :   12.05.2003
*
* © four for business AG
*****************************************/

$db2 = new DB_Contenido;

if(!$perm->have_perm_area_action($area, $action))
{

  $notification->displayNotification("error", i18n("Permission denied"));
  
} else {

if ( !isset($idlang) && $action != "lang_new")
{
  $notification->displayNotification("error", "no language id given. Usually, this shouldn't happen, except if you played around with your system. if you didn't play around, please report a bug.");

} else {

    if (($action == "lang_edit") && ($perm->have_perm_area_action($area, $action)))
    {
        if ($active != "1")
        {
            $active = "0";
        }

            $sql = "UPDATE 
                   ".$cfg["tab"]["lang"]."
                   SET
                       name = '".$langname."',
                       encoding = '".$charset."',
                       active = ".$active."
                   WHERE
                       idlang = ".$idlang;

        $db->query($sql);

        $notification->displayNotification("info", i18n("Changes saved"));
    } 


    $tpl->reset();
    
    $sql = "SELECT
                A.idlang AS idlang, A.name AS name, A.active as active, A.encoding as encoding,
				B.idclient AS idclient 
            FROM
                ".$cfg["tab"]["lang"]." AS A,
				".$cfg["tab"]["clients_lang"]." AS B
            WHERE
                A.idlang = '".$idlang."' AND
				B.idlang = '".$idlang."'";

    $db->query($sql);
    $db->next_record();

    $form = '<form name="lang_properties" target="left_bottom" method="post" action="'.$sess->url("main.php").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="lang">
                 <input type="hidden" name="action" value="lang_editlanguage">
                 <input type="hidden" name="frame" value="2">
				 <input type="hidden" name="targetclient" value="'.$db->f("idclient").'">
                 <input type="hidden" name="idlang" value="'.$idlang.'">';
                 

    
    $tpl->set('s', 'JAVASCRIPT', $javascript);
    $tpl->set('s', 'FORM', $form);
    $tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
    $tpl->set('s', 'CANCELTEXT', i18n("Discard changes"));
    $tpl->set('s', 'CANCELLINK', $sess->url("main.php?area=$area&frame=4&idlang=$idlang"));

    if ($error)
    {
        echo $error;
    }

    $tpl->set('d', 'CATNAME', i18n("Property"));
    $tpl->set('d', 'BGCOLOR',  $cfg["color"]["table_header"]);
    $tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', i18n("Value"));
    $tpl->next();
    
    $tpl->set('d', 'CATNAME', i18n("Language name"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "langname", $db->f("name"), 40, 255));
    $tpl->next();
    
    $selectform = '<select name="sencoding" size="1">';
    foreach ($cfg['AvailableCharsets'] as $charset)
    {
        if (strcmp($charset, $db->f("encoding")) == 0)
        {
            $selected = " selected";
        } else {
            $selected = "";
        }
        
        $selectform .= '<option value="'.$charset.'" '.$selected.'>'.$charset.'</option>';
    }

    $selectform .= "</select>";
    
    $tpl->set('d', 'CATNAME', i18n("Encoding"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', $selectform);
    $tpl->next();

    $tpl->set('d', 'CATNAME', i18n("Active"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateCheckbox ("active", "1",$db->f("active")));
    $tpl->next();
    
    $tpl->set('s', 'SID', $sess->id);

    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['lang_edit']);
}
}

?>
