<?php

/******************************************
* File      :   main.login.php
* Project   :   contendio
* Descr     :   contenido start screen
*
* Author    :   Jan Lengowski
* Created   :   21.01.2003
* Modified  :   21.01.2003
*
* © four for business AG
******************************************/

$tpl->reset();


if ($saveLoginTime == true)
{
	$sess->register("saveLoginTime");
	$saveLoginTime = 0;
	
	$vuser = new User();
	
	$vuser->loadUserByUserID($auth->auth["uid"]);
	$lastTime = $vuser->getUserProperty("backend","currentlogintime");
	$timestamp = date("Y-m-d H:i:s");
	$vuser->setUserProperty("backend","currentlogintime",$timestamp);
	$vuser->setUserProperty("backend","lastlogintime",$lastTime);
	
}



 $vuser = new User();
$vuser->loadUserByUserID($auth->auth["uid"]);
 $lastlogin = $vuser->getUserProperty("backend","lastlogintime");
        if ($lastlogin == "")
        {
        	$lastlogin = i18n("No Login Information available.");
        }

$userid = $auth->auth["uid"];

$sql = "SELECT realname FROM ".$cfg["tab"]["phplib_auth_user_md5"]." WHERE user_id = '".$userid."'";

$db->query($sql);
$db->next_record();

$str  = i18n("Welcome") ." <b>" . $db->f("realname") . "</b>. ";
$str .= i18n("You are logged in as").": <b>" . $auth->auth["uname"] . "</b>.<br><br>";
$str .= i18n("Last login").": ".$lastlogin;
$tpl->set('s', 'WELCOME', $str);

 $clients = $classclient->getAccessibleClients();
        if(count($clients) > 1)
        {
        
            $clientform = '<form style="margin: 0px" name="clientselect" method="post" target="_top" action="'.$sess->url("index.php").'">';
            $clientselect = '<select class="text_medium" name="changeclient">';

            foreach ($clients as $key => $v_client)
            {
                if ($perm->have_perm_client_lang($key, $lang))
                {

                    $selected = "";
                    if ($key == $client)
                    {
                        $selected = "selected";
                    }
                    $clientselect .= '<option value="'.$key.'" '.$selected.'>'.$v_client['name']." (". $key . ')</option>';
                }
            }

            $clientselect .= "</select>";
            $tpl->set('s', 'CLIENTFORM', $clientform);
            $tpl->set('s', 'PULL_DOWN_MANDANTEN', $clientselect);
            $tpl->set('s', 'OKBUTTON', '<input type="image" src="images/but_ok.gif" alt="'.i18n("Change client").'" title="'.i18n("Change client").'" border="0">');
         } else {
            $tpl->set('s', 'OKBUTTON', '');
            $tpl->set('s', 'CLIENTFORM', '');
            
            foreach ($clients as $key => $v_client)
            {
                $name = $v_client['name']." (". $key . ')';
            }
            $tpl->set('s', 'PULL_DOWN_MANDANTEN', $name);
         }

         $mycontenido_lastarticles = '<a href="'.$sess->url("frameset.php?area=mycontenido_overview&menuless=1&frame=4").'">'.i18n("Overview").'</a>';
         $mycontenido_settings = '<a href="'.$sess->url("frameset.php?area=mycontenido_settings&menuless=1&frame=4").'">'.i18n("Settings").'</a>';

         $tpl->set('s','MYCONTENIDO_LASTARTICLES',$mycontenido_lastarticles);
         $tpl->set('s','MYCONTENIDO_SETTINGS', $mycontenido_settings);
         $admins = $classuser->getSystemAdmins();

         foreach ($admins as $key => $value)
         {
            $adminstring .= '<a href="mailto:'.$value["email"].'">'.$value["email"].'</a>, ';
         }

         $adminstring = substr($adminstring, 0, strlen($adminstring) -2);

         $clientadmins = $classuser->getClientAdmins($client);

         foreach ($clientadmins as $key => $value)
         {
            $clientadminstring .= '<a href="mailto:'.$value["email"].'">'.$value["email"].'</a>, ';
         }

         $clientadminstring = substr($clientadminstring, 0, strlen($clientadminstring) -2);
//         $tpl->set('s','MAIL_ADMIN', '<a href="mailto:'.$cfg['mail']['admin'].'">'.$cfg['mail']['admin'].'</a>' );

        $tpl->set('s', 'MAIL_ADMIN', $adminstring);
        
        if (count($clientadmins) > 0)
        {

            $tpl->set('s', 'MAIL_CLIENT', $clientadminstring);
        } else {
            $tpl->set('s', 'MAIL_CLIENT', '');
        }

        $tpl->set('s', 'SYMBOLHELP', '<a href="'. $sess->url("frameset.php?area=symbolhelp&menuless=1&frame=4") .'">'.i18n("Symbol help").'</a>');
         
$tpl->generate( $cfg["path"]["templates"] . $cfg["templates"]["welcome"] );





?>
