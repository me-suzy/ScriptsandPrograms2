<?php
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

$tpl->reset();

if ($action == "mycontenido_editself")
    {


         if (strcmp($password, $passwordagain) == 0)
         {

            if (strlen($password) > 0)
            {
    	     $sql = 'UPDATE
                        '.$cfg["tab"]["phplib_auth_user_md5"].'
                      SET
                        password="'.md5($password).'",
                        email="'.$email.'",
                        wysi="'.$wysi.'"
                      WHERE 
    		            user_id="'.$auth->auth["uid"].'"';

            } else {
    	     $sql = 'UPDATE
                        '.$cfg["tab"]["phplib_auth_user_md5"].'
                      SET
                        email="'.$email.'",
                        wysi="'.$wysi.'"
                      WHERE 
    		            user_id="'.$auth->auth["uid"].'"';


            }
                        
            
            $db->query($sql); 
            $tpl->set('s', 'ERROR', $notification->returnNotification("info", i18n("Saved changes"))); 
            
    } else {
        $tpl->set('s', 'ERROR', $notification->returnNotification("error", i18n("Passwords don't match")));
      
    }
} else {
        $tpl->set('s', 'ERROR', "");
}
    
    
    $sql = "SELECT
                username, realname, email, wysi
            FROM
                ".$cfg["tab"]["phplib_auth_user_md5"]."
            WHERE
                user_id = '".$auth->auth["uid"]."'";

    $db->query($sql);

    $form = '<form name="user_properties" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="mycontenido_editself">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
                 
    $db->next_record();

	$settingsfor = sprintf(i18n("Settings for %s"), $db->f("username") . " (".$db->f("realname").")");
    $tpl->set('s', 'SETTINGSFOR', $settingsfor); 
    $tpl->set('s', 'FORM', $form);
    $tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
    $tpl->set('s', 'CANCELLINK', $sess->url("main.php?area=$area&frame=4"));

    $tpl->set('d', 'CATNAME', i18n("Property"));
    $tpl->set('d', 'BGCOLOR',  $cfg["color"]["table_header"]);
    $tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', i18n("Value"));
    $tpl->next();

    $tpl->set('d', 'CATNAME', i18n("New password"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("password", "password", "", 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CATNAME', i18n("Confirm new password"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("password", "passwordagain", "", 40, 255));
    $tpl->next();

    $tpl->set('d', 'CATNAME', i18n("E-Mail"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "email", $db->f("email"), 40, 255));
    $tpl->next();
    
    
    $tpl->set('d', 'CATNAME', i18n("Use WYSIWYG-Editor"));
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', 'CATFIELD', formGenerateCheckbox("wysi", "1", $db->f("wysi")));
    $tpl->next();
    

    
    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['mycontenido_settings']);
?>
