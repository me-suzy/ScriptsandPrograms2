<?php
/******************************************
* File      :   include.newsletter_edit.php
* Project   :   Contenido
* Descr     :   Newsletter Editor
*
* Author    :   Timo A. Hummel
* Created   :   09.05.2003
* Modified  :   10.05.2003
*
* © four for business AG
*****************************************/

include_once ($cfg["path"]["contenido"] . $cfg["path"]["classes"] . "class.user.php");

$userclass = new User();

$db2 = new DB_Contenido;

if(!$perm->have_perm_area_action($area))
{
  $notification->displayNotification("error", i18n("No permission"));
} else {

if ( !isset($newsid) && $action != "news_createnewsletter" )
{
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
} else {


    if ($action == "news_createnewsletter" && $finalstep == 1)
    {
                    $newsid = $db->nextid($cfg["tab"]["news"]);
                    $timestamp = date("Y-m-d H:i:s"); 
                    $sql = 'INSERT INTO
                             '.$cfg["tab"]["news"].'
                            SET
                              name="'.$name.'",
                              subject="'.$subject.'",
                              message="'.$message.'",
                              newsfrom="'.$newsfrom.'",
                              author="'.$auth->auth["uid"].'",
                              newsdate="'.$timestamp.'",
                              idclient="'.$client.'",
                              idlang="'.$lang.'",
                              idnews = "'.$newsid.'"';
 
                    $db->query($sql);


                    $notification->displayNotification("info", i18n("Changes saved"));
    }
    if (($action == "news_editnewsletter") && ($perm->have_perm_area_action($area, $action)))
    {

                    $sql = 'UPDATE
                             '.$cfg["tab"]["news"].'
                            SET
                              name="'.$name.'",
                              subject="'.$subject.'",
                              message="'.$message.'",
                              newsfrom="'.$newsfrom.'"
                            WHERE
                              idnews = "'.$newsid.'"';
                    $db->query($sql);

                    $notification->displayNotification("info", i18n("Changes saved"));
                    

        }
        


    $tpl->reset();
    
    $sql = "SELECT
                idnews, name, subject, message, newsfrom, newsdate, author
            FROM
                ".$cfg["tab"]["news"]."
            WHERE
                idnews = '".$newsid."'";

    $db->query($sql);

    if ($action != "news_createnewsletter")
    {
    
        $form = '<form name="news_edit" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="news_editnewsletter">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="newsid" value="'.$newsid.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
    } else {
        $form = '<form name="news_edit" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="news_createnewsletter">
                 <input type="hidden" name="finalstep" value="1">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="newsid" value="'.$newsid.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
    }
                 
    $db->next_record();
    
    $tpl->set('s', 'JAVASCRIPT', $javascript);
    $tpl->set('s', 'FORM', $form);
    $tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
    $tpl->set('s', 'CANCELTEXT', i18n("Discard changes"));
    $tpl->set('s', 'CANCELLINK', $sess->url("main.php?area=$area&frame=4&newsid=$userid"));

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
    $tpl->set('d', 'CATNAME', i18n("From"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "newsfrom", $db->f("newsfrom"), 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Subject"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("textbox", "subject", $db->f("subject"), 80, 3));
    $tpl->next();
   
    if ($action != "news_createnewsletter")
    {
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("Created"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', $db->f("newsdate"));
        $tpl->next();
    
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("Author"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', 
                                $userclass->getUsername($db->f("author")).
                                " (".
                                $userclass->getRealname($db->f("author")).
                                ")");

        $tpl->next();
    }
           
    $messageHelp = "<br><br><b>". i18n("Special Message Tags (will be replaced when sending)").":</b><br>".
                   "MAIL_NAME: ".i18n("Name of the recipient")."<br>".
                   "MAIL_DATE: ".i18n("Date of when the mail has been sent")."<br>".
                   "MAIL_TIME: ".i18n("Time of when the mail has been sent")."<br>".
                   "MAIL_NUMBER: ".i18n("Number of recipients")."<br>".
                   "MAIL_UNSUBSCRIBE: ".i18n("Link to unsubscribe")."<br>".
                   "MAIL_STOP: ".i18n("Link to pause the subscription")."<br>".
                   "MAIL_GOON: ".i18n("Link to resume the subscription")."<br>";

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Message"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("textbox", "message", $db->f("message"), 80, 20) . $messageHelp);
    $tpl->next();
    

    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['newsletter_edit']);
}
}
?>
