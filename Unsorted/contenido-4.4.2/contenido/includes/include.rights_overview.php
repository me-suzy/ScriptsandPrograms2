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


$db2 = new DB_Contenido;

if(!$perm->have_perm_area_action($area,$action))
{
  $notification->displayNotification("error", i18n("Permission denied"));
} else {

if ( !isset($userid) )
{

} else {


    if (($action == "user_edit") && ($perm->have_perm_area_action($area, $action)))
    {
            $stringy_perms = array();
            if ($msysadmin)
            {
                array_push($stringy_perms, "sysadmin");
            }

            if (is_array($madmin)) {
                foreach ($madmin as $value) {
                    array_push($stringy_perms, "admin[$value]");
                }
            }

            if (is_array($mclient)) {
                foreach ($mclient as $value) {
                    array_push($stringy_perms, "client[$value]");
                }
            }

            if (is_array($mlang)) {
                foreach ($mlang as $value) {
                    array_push($stringy_perms, "lang[$value]");
                }
            }

            if (strlen($password) > 0)
            {
                if (strcmp($password, $passwordagain) == 0)
                {
                    $sql = 'UPDATE
                             '.$cfg["tab"]["phplib_auth_user_md5"].'
                            SET
                                password="'.md5($password).'"
                            WHERE
                                user_id = "'.$userid.'"';

                    $db->query($sql);


                    $sql = 'UPDATE
                             '.$cfg["tab"]["phplib_auth_user_md5"].'
                            SET
                              realname="'.$realname.'",
                              email="'.$email.'",
                              telephone="'.$telephone.'",
                              address_street="'.$address_street.'",
                              address_city="'.$address_city.'",
                              address_country="'.$address_country.'",
                              address_zip="'.$address_zip.'",
                              wysi="'.$wysi.'",
                              perms="'.implode(",",$stringy_perms).'" 
                            WHERE
                              user_id = "'.$userid.'"';
 
                    $db->query($sql);

                    $notification->displayNotification("info", i18n("Changes saved"));
                    
                } else {
                    $notification->displayNotification("error", i18n("Passwords don't match"));
                }
        } else {
                $sql = 'UPDATE
                         '.$cfg["tab"]["phplib_auth_user_md5"].'
                        SET
                          realname="'.$realname.'",
                          email="'.$email.'",
                          telephone="'.$telephone.'",
                          address_street="'.$address_street.'",
                          address_city="'.$address_city.'",
                          address_country="'.$address_country.'",
                          address_zip="'.$address_zip.'",
                          wysi="'.$wysi.'",
                          perms="'.implode(",",$stringy_perms).'" 
                        WHERE
                          user_id = "'.$userid.'"';

                $db->query($sql);

                $notification->displayNotification("info", i18n("Changes saved"));

        }
 }    
        


    $tpl->reset();
    $tpl->set('s','SID', $sess->id);
    $sql = "SELECT
                username, password, realname, email, telephone,
                address_street, address_zip, address_city, address_country, wysi
            FROM
                ".$cfg["tab"]["phplib_auth_user_md5"]."
            WHERE
                user_id = '".$userid."'";

    $db->query($sql);

    if(!isset($rights_perms)||$action==""||!isset($action)){

        $db3 = new DB_Contenido;
        //search for the permissions of this user
        $sql="SELECT perms FROM ".$cfg["tab"]["phplib_auth_user_md5"]." WHERE user_id='$userid'";
    
        $db3->query($sql);
        $db3->next_record();
        $rights_perms=$db3->f("perms");
    
    }

    $user_perms = array();
    $user_perms = explode(",", $rights_perms);
    
    $form = '<form name="user_properties" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="user_edit">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="userid" value="'.$userid.'">
                 <input type="hidden" name="idlang" value="'.$lang.'">';
                 
    $db->next_record();
    
    $tpl->set('s', 'JAVASCRIPT', $javascript);
    $tpl->set('s', 'FORM', $form);
    $tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
    $tpl->set('s', 'CANCELTEXT', i18n("Discard changes"));
    $tpl->set('s', 'CANCELLINK', $sess->url("main.php?area=$area&frame=4&userid=$userid"));

    if ($error)
    {
        echo $error;
    }

    $tpl->set('d', 'CLASS', 'textw_medium');
    $tpl->set('d', 'CATNAME', i18n("Property"));
    $tpl->set('d', 'BGCOLOR',  $cfg["color"]["table_header"]);
    $tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', i18n("Value"));
    $tpl->next();
   
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Username"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', $db->f("username").'<img align="top" src="images/spacer.gif" height="20">');
    $tpl->next();
       
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Name"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "realname", $db->f("realname"), 40, 255));
    $tpl->next();
   
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("New password"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("password", "password", "", 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Confirm new password"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("password", "passwordagain", "", 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("E-Mail"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "email", $db->f("email"), 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Phone number"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "telephone", $db->f("telephone"), 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Street"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "address_street", $db->f("address_street"), 40, 255));
    $tpl->next();

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("ZIP code"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "address_zip", $db->f("address_zip"), 10, 10));
    $tpl->next();    
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("City"));
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "address_city", $db->f("address_city"), 40, 255));
    $tpl->next();
    
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Country"));
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "address_country", $db->f("address_country"), 40, 255));
    $tpl->next();

    $userperm = split(",", $auth->auth["perm"]);

    if(in_array("sysadmin",$userperm)){
        $tpl->set('d', 'CLASS', 'text_medium');
        $tpl->set('d', 'CATNAME', i18n("System administrator"));
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
        $tpl->set('d', "CATFIELD", formGenerateCheckbox("msysadmin","1", in_array("sysadmin", $user_perms)));
        $tpl->next();
    }


        $sql="SELECT * FROM ".$cfg["tab"]["clients"];
        $db2->query($sql);
        $client_list = "";
        $gen = 0;
        while($db2->next_record())
        {
             
            if(in_array("admin[".$db2->f("idclient")."]",$userperm) || in_array("sysadmin",$userperm)){
                $client_list .= formGenerateCheckbox("madmin[".$db2->f("idclient")."]",$db2->f("idclient"),in_array("admin[".$db2->f("idclient")."]",$user_perms), $db2->f("name")." (".$db2->f("idclient").")")."<br>";
                $gen = 1;
            }
       }

        if ($gen == 1)
        {
            $tpl->set('d', 'CLASS', 'text_medium');
            $tpl->set('d', 'CATNAME', i18n("Administrator"));
            $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
            $tpl->set('d', "BGCOLOR", $cfg["color"]["table_dark"]);
            $tpl->set('d', "CATFIELD", $client_list);
            $tpl->next(); 
        }


    $sql = "SELECT * FROM " .$cfg["tab"]["clients"];
    $db2->query($sql);
    $client_list = "";
    

    
    while ($db2->next_record())
    {
            if(in_array("client[".$db2->f("idclient")."]",$userperm) || in_array("sysadmin",$userperm) || in_array("admin[".$db2->f("idclient")."]",$userperm)) {
                $client_list .= formGenerateCheckbox("mclient[".$db2->f("idclient")."]",$db2->f("idclient"),in_array("client[".$db2->f("idclient")."]",$user_perms), $db2->f("name")." (". $db2->f("idclient") . ")")."<br>";
            }

    }
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Access clients"));
    $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
    $tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
    $tpl->set('d', "CATFIELD", $client_list);
    $tpl->next();
    
    $sql = "SELECT
                a.idlang as idlang,
                a.name as name,
                b.name as clientname,
                b.idclient as idclient FROM
                " .$cfg["tab"]["lang"]." as a,
                " .$cfg["tab"]["clients_lang"]." as c,
                " .$cfg["tab"]["clients"]." as b
                WHERE
                    a.idlang = c.idlang AND
                    c.idclient = b.idclient";

    $db2->query($sql);
    $client_list = "";
    

    
    while ($db2->next_record())
    {
//            if($perm->have_perm_client_lang($client, $db2->f("idlang")in_array("lang[".$db2->f("idlang")."]",$userperm) || in_array("sysadmin",$userperm) || $perm->have_perm())
            if($perm->have_perm_client("lang[".$db2->f("idlang")."]") || $perm->have_perm_client("admin[".$db2->f("idclient")."]" ))
            {
                $client_list .= formGenerateCheckbox("mlang[".$db2->f("idlang")."]",$db2->f("idlang"),in_array("lang[".$db2->f("idlang")."]",$user_perms), $db2->f("name")." (". $db2->f("clientname") .")") ."<br>";
            }

    }
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Access languages"));
    $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
    $tpl->set('d', "BGCOLOR", $cfg["color"]["table_dark"]);
    $tpl->set('d', "CATFIELD", $client_list);
    $tpl->next(); 

	
	/* Generate user property table */
    $tempUser = new User();
    
    $tempUser->loadUserByUserID($userid);
    
    if (is_string($del_userprop_type) && is_string($del_userprop_name))
    {
    	$tempUser->deleteUserProperty($del_userprop_type, $del_userprop_name);
    }
    
    if (is_string($userprop_type) && is_string($userprop_name) && is_string($userprop_value)
        && !empty($userprop_type) && !empty($userprop_name))
    {
    	$tempUser->setUserProperty($userprop_type, $userprop_name, $userprop_value);
    }
    $properties = $tempUser->getUserProperties();
    
    if (is_array($properties))
    {
    	foreach ($properties as $entry)
    	{
    		$type = $entry["type"];
    		$name = $entry["name"];
    		$deleteButton = '<a href="'.$sess->url("main.php?area=$area&frame=4&userid=$userid&del_userprop_type=$type&del_userprop_name=$name").'"><img src="images/delete.gif" border="0" alt="Eigenschaft löschen" title="Eigenschaft löschen"></a>';
    		$value = $tempUser->getUserProperty($type,$name);
    		$propLines .= "<tr class=\"text_medium\"><td>$type</td><td>$name</td><td>$value</td><td>$deleteButton</tr>";
    	}
    }	
	$table = '<table width="100%" cellspacing="0" cellpadding="2" style="border: 1px; border-color:'.$cfg["color"]["table_border"].'; border-style: solid;">
                 <tr style="background-color:'.$cfg["color"]["table_header"].'" class="text_medium"><td>'.i18n("Area/Type").'</td><td>'.i18n("Property").'</td><td>'.i18n("Value").'</td><td>&nbsp;</td></tr>'. $propLines. 
			 '<tr class="text_medium"><td><input class="text_medium"  type="text" size="16" maxlen="32" name="userprop_type"></td>
              <td><input class="text_medium" type="text" size="16" maxlen="32" name="userprop_name"></td>
			  <td><input class="text_medium" type="text" size="32" name="userprop_value"></td><td>&nbsp;</td></tr></table>';
	
	$userProps = $table;
	
    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("User-defined properties"));
    $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
    $tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
    $tpl->set('d', "CATFIELD", $userProps);
    $tpl->next(); 

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Use WYSIWYG-Editor"));
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', 'CATFIELD', formGenerateCheckbox("wysi", "1", $db->f("wysi")));
    $tpl->next();
    

    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['rights_overview']);
}
}
?>
