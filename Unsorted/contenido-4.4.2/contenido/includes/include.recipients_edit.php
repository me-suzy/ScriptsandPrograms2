<?php
/******************************************
* File      :   include.rights_overview.php
* Project   :   Contenido
* Descr     :   Displays rights
*
* Author    :   Timo A. Hummel
* Created   :   30.04.2003
* Modified  :   30.04.2003
*
* © four for business AG
*****************************************/

include_once ($cfg["path"]["contenido"] . $cfg["path"]["classes"] . "class.user.php");

$userclass = new User();

$db2 = new DB_Contenido;

if(!$perm->have_perm_area_action($area,$action))
{
  $notification->displayNotification("error", i18n("Permission denied"));
} else {

if ( !isset($newsrcpid) && $action != "recipients_createrecipient" )
{
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
} else {


    if ($action == "recipients_createrecipient" && $final == 1)
    {

                    if ($deactivated == "deactivated")
                    {
                        $deactivated = 1;
                    } else {
                        $deactivated = 0;
                    }

                    $newsrcpid = $db->nextid($cfg["tab"]["news_rcp"]);
                    $timestamp = date("Y-m-d H:i:s"); 
                    $sql = 'INSERT INTO
                             '.$cfg["tab"]["news_rcp"].'
                            SET
                              name="'.$name.'",
                              email="'.$email.'",
                              deactivated="'.$deactivated.'",
                              author="'.$auth->auth["uid"].'",
                              created="'.$timestamp.'",
                              lastmodified="'.$timestamp.'",
                              idclient="'.$client.'",
                              idlang="'.$lang.'",
                              idnewsrcp = "'.$newsrcpid.'"';
 
                    $db->query($sql);


                    $notification->displayNotification("info", i18n("Changes saved"));
    }
    if (($action == "recipients_editrecipient") && ($perm->have_perm_area_action($area, $action)))
    {
                    if ($deactivated == "deactivated")
                    {
                        $deactivated = 1;
                    } else {
                        $deactivated = 0;
                    }

                    $lastmodified = date("Y-m-d H:i:s"); 

                    $sql = 'UPDATE
                             '.$cfg["tab"]["news_rcp"].'
                            SET
                              name="'.$name.'",
                              email="'.$email.'",
                              deactivated="'.$deactivated.'",
                              lastmodified="'.$lastmodified.'"
                            WHERE
                              idnewsrcp = "'.$newsrcpid.'"';
                    $db->query($sql);

                    $notification->displayNotification("info", i18n("Changes saved"));
                    

        }
        


    $tpl->reset();
    
    $sql = "SELECT
                idnewsrcp, name, email, deactivated, created, lastmodified, author
            FROM
                ".$cfg["tab"]["news_rcp"]."
            WHERE
                idnewsrcp = '".$newsrcpid."'";

    if ($action != "recipients_createrecipient")
    {
        $db->query($sql);
    }

    if ($action != "recipients_createrecipient")
    {
    
        $form = '<form name="newsrcp_edit" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="recipients_editrecipient">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="newsrcpid" value="'.$newsrcpid.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
    } else {
        $form = '<form name="newsrcp_edit" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="recipients_createrecipient">
                 <input type="hidden" name="final" value="1">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="newsrcpid" value="'.$newsrcpid.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
    }
                 
    $db->next_record();
    
    $tpl->set('s', 'JAVASCRIPT', $javascript);
    $tpl->set('s', 'FORM', $form);
    $tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
    $tpl->set('s', 'CANCELTEXT', i18n("Discard changes"));
    $tpl->set('s', 'CANCELLINK', $sess->url("main.php?area=$area&frame=4&newsrcpid=$newsrcp"));

    $tpl->set('d', 'CLASS', 'textw_medium');
    $tpl->set('d', 'CATNAME', i18n("Property"));
    $tpl->set('d', 'BGCOLOR',  $cfg["color"]["table_header"]);
    $tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', i18n("Value"));
    $tpl->next();
   
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Name"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "name", $db->f("name"), 40, 255));
    $tpl->next();
  

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("E-Mail"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "email", $db->f("email"), 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Deactivated"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateCheckbox ("deactivated", "deactivated", $db->f("deactivated")));
    $tpl->next();
   
    if ($action != "recipients_createrecipient")
    {
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("Created"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', $db->f("created"));
        $tpl->next();
    
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("Changed"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', $db->f("lastmodified"));
        $tpl->next();
        
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("Author"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', 
                                $userclass->getUsername($db->f("author")).
                                " (".
                                $userclass->getRealname($db->f("author")).
                                ")");

        $tpl->next();

        
    }
           
    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['recipient_edit']);
}
}
?>
