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

if ($action == "client_new")
{
    $nextid = $db->nextid($cfg["tab"]["clients"]);
    $idclient = $nextid;
    $new = true; 
    
}
if(!$perm->have_perm_area_action($area))
{
  $notification->displayNotification("error", i18n("Permission denied"));
} else {

if ( !isset($idclient) )
{
  $notification->displayNotification("error", i18n("No client ID passed"));

} else {

//    if (ereg

    if (($action == "client_edit") && ($perm->have_perm_area_action($area, $action)))
    {
        if ($active != "1")
        {
            $active = "0";
        }
       
        if ($new == true)
        {

             if (substr($path, strlen($frontendpath)-1) != "/")
             {
                $frontendpath .= "/";
             }

             if (substr($path, strlen($htmlpath)-1) != "/")
             {
                $htmlpath .= "/";
             }
             
             $sql = "INSERT INTO
                ".$cfg["tab"]["clients"]."
                SET
                    name = '".$clientname."',
                    frontendpath = '".$frontendpath."',
                    htmlpath = '". $htmlpath."',
                    errsite_cat = '".$errsite_cat."',
                    errsite_art = '".$errsite_art."',
                    idclient = ".$idclient;
                 

             // Copy the client template to the real location
             $destPath = $frontendpath;
             $sourcePath = $cfg['path']['contenido'] . $cfg['path']['frontendtemplate'];

            if ($copytemplate)
            {
                if (!file_exists($destPath))
                {
                    recursive_copy($sourcePath, $destPath);
                    $res = fopen($destPath."config.php","r+");
                    $res2 = fopen($destPath."config.php.new", "a+");
                    
                    if ($res && $res2)
                    {
                    	while (!feof($res))
                    	{
	                        $buffer = fgets($res, 4096);
                        	$buffer = str_replace("!CLIENT!", $idclient, $buffer);
                        	$buffer = str_replace("!PATH!", $cfg["path"]["contenido"], $buffer);
                        	fwrite($res2, $buffer);
                    	}
                    	
                    } else {
                  		$notification->displayNotification("error",i18n("Couldn't write the file config.php."));
                    }
                    

                    fclose($res);
                    fclose($res2);

                    unlink($destPath."config.php");
                    rename($destPath."config.php.new", $destPath."config.php");
                } else {
                	$message = sprintf(i18n("The directory %s already exists. The client was created, but you have to copy the frontend-template yourself"),$destPath);
                $notification->displayNotification("warning", $message);
                }
            }
		rereadClients();
            


        } else {

            $pathwithoutslash = $frontendpath;
            if (substr($frontendpath, strlen($frontendpath)-1) != "/")
            {
               $frontendpath .= "/";
            }

            if (($oldpath != $frontendpath) && ($oldpath != $pathwithoutslash))
            {
                $notification->displayNotification("warning", i18n("You changed the client path. You might need to copy the frontend to the new location"));

            }
            $sql = "UPDATE 
                    ".$cfg["tab"]["clients"]."
                    SET
                        name = '".$clientname."',
                        frontendpath = '".$frontendpath."',
                        htmlpath = '".$htmlpath."',
                        errsite_cat = '".$errsite_cat."',
                        errsite_art = '".$errsite_art."'
                    WHERE
                        idclient = ".$idclient;
        }

        $db->query($sql);
        $new = false;
        rereadClients();
        $notification->displayNotification("info", i18n("Changes saved"));
        
    } 


    $tpl->reset();
    
    $sql = "SELECT
                idclient, name, frontendpath, htmlpath, errsite_cat, errsite_art
            FROM
                ".$cfg["tab"]["clients"]."
            WHERE
                idclient = '".$idclient."'";

    $db->query($sql);

    $db->next_record();

    $form = '<form name="client_properties" method="post" action="'.$sess->url("main.php?").'">
                 '.$sess->hidden_session().'
                 <input type="hidden" name="area" value="'.$area.'">
                 <input type="hidden" name="action" value="client_edit">
                 <input type="hidden" name="frame" value="'.$frame.'">
                 <input type="hidden" name="new" value="'.$new.'">
                 <input type="hidden" name="oldpath" value="'.$db->f("frontendpath").'">
                 <input type="hidden" name="idclient" value="'.$idclient.'">';
                 
    
    
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

    $tpl->set('d', 'CATNAME', i18n("Property"));
    $tpl->set('d', 'BGCOLOR',  $cfg["color"]["table_header"]);
    $tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', i18n("Value"));
    $tpl->next();
    
    $tpl->set('d', 'CATNAME', i18n("Client name"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "clientname", $db->f("name"), 50, 255));
    $tpl->next();

    $serverpath = $db->f("frontendpath");

    if ($serverpath == "")
    {
        $serverpath = $cfg['path']['frontend'];
    }
    
    $tpl->set('d', 'CATNAME', i18n("Server path"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD',  formGenerateField ("text", "frontendpath", $serverpath, 50, 255));
    $tpl->next();   

    $htmlpath = $db->f("htmlpath");

    if ($htmlpath == "")
    {
        $htmlpath = "http://";
    }
    
    $tpl->set('d', 'CATNAME', i18n("Web address"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "htmlpath", $htmlpath, 50, 255));
    $tpl->next();      

    $tpl->set('d', 'CATNAME', i18n("Error page category"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "errsite_cat", $db->f("errsite_cat"), 10, 10));
    $tpl->next();  

    $tpl->set('d', 'CATNAME', i18n("Error page article"));
    $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
    $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
    $tpl->set('d', 'CATFIELD', formGenerateField ("text", "errsite_art", $db->f("errsite_art"), 10, 10));
    $tpl->next();  

    if ($new == true)
    {
        $tpl->set('d', 'CATNAME', i18n("Copy frontend template"));
        $tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
        $tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
        $tpl->set('d', 'CATFIELD', formGenerateCheckbox ("copytemplate", "checked", 1));
        $tpl->next();
    }
    
    
    

    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['client_edit']);
}
}
?>
