<?php






if(!is_object($db2))
$db2 = new DB_Contenido;



if(!isset($rights_client)){
      $rights_client=$client;
      $rights_lang=$lang;
}

//set new right_list (=all possible rights)
if(!is_array($right_list)){
         //register these list fore following sites
         $sess->register("right_list");

         $plugxml=new XML_Doc();
        /*
        the right list:


        [1] => Array                                                     => main areaid
        (
            [1] => Array                                                 =>  areaid
                (
                    [perm] => con                                        => areaname  = permission
                    [location] => navigation/content/article             => location for the name in the languagefile only for the main areaid
                    [action] => Array
                        (
                            [0] => 10                                    =>actionnames
                            [1] => 33
                            [2] => 41
                            [3] => 50
                        )

                )

            [2] => Array                            =>  areaid
                (
                    [perm] => con_editcontent
                )

            [3] => Array                            =>  areaid
                (
                    [perm] => con_editart
                    [action] => Array
                        (
                            [0] => 30
                        )

                )


         */

         //select all rights , actions an theeir locations   without area login
        $sql="SELECT A.idarea, A.parent_id, B.location,A.name FROM ".$cfg["tab"]["area"]." as A LEFT JOIN ".$cfg["tab"]["nav_sub"]." as B ON  A.idarea = B.idarea WHERE A.name!='login' AND A.relevant='1' AND A.online='1' GROUP BY A.name ORDER BY A.idarea";
         $db->query($sql);

         while($db->next_record())
        {
                if($db->f("parent_id")=="0"){
                             $right_list[$db->f("name")][$db->f("name")]["perm"]=$db->f("name");

                             //if Area is an plugin
                             if(strstr($db->f('location'),$con_cfg['PathPlugins'])){
                                $plugxml->load($db->f('location').'lang_de.xml');
                                $location=$plugxml->valueOf("language/sub");
                                eval($plugxml->valueOf("language/action"));

                             }else{
                                $location = $xml->valueOf($cfgPath['xmlroot'] . $db->f('location'));
                             }

                             $right_list[$db->f("name")][$db->f("name")]["location"]=$db->f('location');


                }else{
                             $right_list[$db->f("parent_id")][$db->f("name")]["perm"]=$db->f("name");
                             $right_list[$db->f("parent_id")][$db->f("name")]["location"] = $db->f('location');
                }

                $sql="SELECT * FROM ".$cfg["tab"]["actions"]." WHERE idarea='".$db->f("idarea")."' AND relevant='1'";
                $db2->query($sql);
                while($db2->next_record())
                {

                      if($db->f("parent_id")=="0"){
                              $right_list[$db->f("name")][$db->f("name")]["action"][]=$db2->f("name");


                      }else{
                              $right_list[$db->f("parent_id")][$db->f("name")]["action"][]=$db2->f("name");

                      }



                }


         }

}





echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo "<html>";
echo "<head>";
echo '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">';
echo "<title></title>";
echo "<script type=\"text/javascript\" src=\"scripts/rowMark.js\"></script>";
echo "<script type=\"text/javascript\" src=\"scripts/infoBox.js\"></script>";
echo "<script type=\"text/javascript\" src=\"scripts/rights.js.php?contenido=".$sess->id."\"></script>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/contenido.css\" />";
echo "</head>";
echo "<body style=\"margin:10px\">";





if(!isset($actionarea)){
    $actionarea="area";
}
echo"<FORM name=\"rightsform\" method=post action=\"".$sess->url("main.php")."\">";
echo"<input type=\"hidden\" name=\"action\" value=\"\">";
echo"<input type=\"hidden\" name=\"userid\" value=\"$userid\">";
echo"<input type=\"hidden\" name=\"actionarea\" value=\"$actionarea\">";
echo"<input type=\"hidden\" name=\"frame\" value=\"4\">";
$muser = new User;
$muser->loadUserByUserID($userid);

$userperms = $muser->getField("perms");

ob_start();

echo"<table style=\"border:0px; border-left:1px; border-bottom: 1px;border-color: ". $cfg["color"]["table_border"] . "; border-style: solid;\" cellspacing=\"0\" cellpadding=\"2\" >";
echo"<tr class=\"text_medium\" style=\"background-color: ". $cfg["color"]["table_dark"] .";\">";
echo"<td valign=\"top\" style=\"border: 0px; border-top:1px; border-right:1px; border-color: " . $cfg["color"]["table_border"] . "; border-style: solid;\" align=\"left\">".i18n("Client / Language").":</td>";
echo"<td valign=\"top\" style=\"border: 0px; border-top:1px; border-right:1px; border-color: " . $cfg["color"]["table_border"] . "; border-style: solid;\" align=\"right\">";




echo"<input type=\"hidden\" name=\"rights_perms\" value=\"$rights_perms\">";






//selectbox for clients
echo "<SELECT class=\"text_medium\" name=\"rights_clientslang\" SIZE=1>";

	$clientclass = new Client;
   	$clientList = $clientclass->getAccessibleClients();

  	
  	$firstsel = false;
  	
   	foreach ($clientList as $key=>$value) {
   		
   		$sql="SELECT * FROM ".$cfg["tab"]["lang"]." as A, ".$cfg["tab"]["clients_lang"]." as B WHERE B.idclient=$key AND A.idlang=B.idlang";
		$db->query($sql);

		while($db->next_record())
		{

    		if((strpos($userperms, "client[$key]") !== false) && 
    		   (strpos($userperms, "lang[".$db->f("idlang")."]") !== false)
    		   && ($perm->have_perm("lang[".$db->f("idlang")."]"))){
    		   	
    		   	if ($firstsel == false)
    		   	{
    		   		$firstsel = true;
    		   		$firstclientslang = $db->f("idclientslang");
    		   	}
    		   	
		       if ($rights_clientslang == $db->f("idclientslang")) {
                       printf("<option value=\"%s\" selected>%s</option>",
                         $db->f("idclientslang"),
                         $value["name"] . " -> ".$db->f("name")
                       );
                       
                   if(!isset($rights_client))
                   {
                   	$firstclientslang = $db->f("idclientslang");
                   }
               } else {
                       printf("<option value=\"%s\">%s</option>",
                         $db->f("idclientslang"),
                         $value["name"] . " -> ".$db->f("name")
                       );
               }
    		}
		}
    }



echo $clientselect;
echo "</SELECT>";
echo '<input type="image" src="images/submit.gif">';

echo "</td></tr>";

//navigation
/*echo "<td>
        <a href=\"".$sess->url("main.php?area=user")."\" class=\"action\">User</a>
        <a href=\"javascript:submitrightsform('','area')\" class=\"action\">Areas</a>
        <a href=\"javascript:submitrightsform('','mod')\" class=\"action\">Module</a>
        <a href=\"javascript:submitrightsform('','lay')\" class=\"action\">Layout</a>
        <a href=\"javascript:submitrightsform('','tpl')\" class=\"action\">Templates</a>
        <a href=\"javascript:submitrightsform('','con')\" class=\"action\">Artikel</a>
        <a href=\"javascript:submitrightsform('','str')\" class=\"action\">Stuktur</a>
      </td>";

*/






echo"</table>";
if(!isset($rights_clientslang))
{
	$rights_clientslang = $firstclientslang;
}

$sql = "SELECT idclient, idlang FROM ".$cfg["tab"]["clients_lang"]." WHERE idclientslang = '$rights_clientslang'";
$db->query($sql);

if ($db->next_record())
{
	$rights_client = $db->f("idclient");
	$rights_lang = $db->f("idlang");
} else {
	ob_end_clean();
	$notification->displayNotification("error", i18n("Current user doesn't have any rights to any client/language."));
	die;
}
echo "<br>";
$tmp = ob_get_contents();
ob_end_clean();
echo $tmp;
//including the right_area
//include($con_cfg["PathInc"]."rights_$actionarea.inc.php");














function saverightsarea()
{
         global $db, $cfg,$userid,$rights_client,$rights_lang,$rights_admin,$rights_sysadmin,$rights_perms,$rights_list;

         if(!isset($rights_perms)){
             //search for the permissions of this user
             $sql="SELECT perms FROM ".$cfg["tab"]["phplib_auth_user_md5"]." WHERE user_id='$userid'";
             $db->query($sql);
             $db->next_record();
             $rights_perms=$db->f("perms");
         }


         //if there are no permissions,   delete permissions for lan and client
         if(!is_array($rights_list)){
            $rights_perms=preg_replace("/,+client\[$rights_client\]/","",$rights_perms);
            $rights_perms=preg_replace("/,+lang\[$rights_lang\]/","",$rights_perms);
         }else{
            if(!strstr($rights_perms,"client[$rights_client]"))
                 $rights_perms.=",client[$rights_client]";
            if(!strstr($rights_perms,"lang[$rights_lang]"))
                 $rights_perms.=",lang[$rights_lang]";
         }

         //if admin is checked
         if($rights_admin==1){
             //if admin is mot set
             if(!strstr($rights_perms,"admin[$rights_client]"))
                 $rights_perms.=",admin[$rights_client]";
         }else{
             //cut admin from the string
             $rights_perms=preg_replace("/,*admin\[$rights_client\]/","",$rights_perms);
         }

         //if sysadmin is checked
         if($rights_sysadmin==1){
             //if sysadmin is not set
             if(!strstr($rights_perms,"sysadmin"))
                 $rights_perms.=",sysadmin";
         }else{
             //cat sysadmin from string
             $rights_perms=preg_replace("/,*sysadmin/","",$rights_perms);
         }


         //cut ',' in front of the string
         $rights_perms=preg_replace("/^,/","",$rights_perms);

         //update table
         $sql="UPDATE ".$cfg["tab"]["phplib_auth_user_md5"]." SET perms='$rights_perms' WHERE user_id='$userid'";
                
         $db->query($sql);
         
         //save the other rights
         saverights();


}





function saverights()
{



        global $rights_list,$rights_list_old,$db;
        global $cfg,$userid,$rights_client,$rights_lang;
        global $notification;


        //if no checkboyx is checked
        if(!is_array($rights_list)){
            $rights_list=array();

        }

        //search oll checks which are not in the new Rights_list   for deleting
        $arraydel=array_diff(array_keys($rights_list_old),array_keys($rights_list));
        //search oll checks which are not in the Rights_list_old   for saving
        $arraysave=array_diff(array_keys($rights_list),array_keys($rights_list_old));






        if(is_array($arraydel)){



                  foreach($arraydel as $value){

                          $data=explode("|",$value);

                          if (!is_numeric($data[0]))
                          {
                              $sql = "SELECT idarea FROM " .$cfg["tab"]["area"] . " WHERE name = \"" . $data[0] . "\"";
                              $db->query($sql);
                              $db->next_record();
                              $data[0] = $db->f(0);
                          }

                          if (!is_numeric($data[1]))
                          {
                              $sql = "SELECT idaction FROM " . $cfg["tab"]["actions"] . " WHERE name = \"" . $data[1] . "\"";
                              $db->query($sql);
                              $db->next_record();
                              $data[1] = $db->f(0);
                          }
                          $sql="DELETE FROM ".$cfg["tab"]["rights"]." WHERE user_id='$userid' AND idclient='$rights_client' AND idlang='$rights_lang' AND idarea='$data[0]' AND idcat='$data[2]' AND idaction='$data[1]' AND type=0";
                          $db->query($sql);

                  }
        }







        //search for all mentioned checkboxes
        if(is_array($arraysave)){
                     foreach ($arraysave as $value){
                              //explodes the key    it consits     areait+actionid+itemid
                              $datas=explode("|",$value);

                                                            // Since areas are stored in a numeric form in the rights table, we have
                                                            // to convert them from strings into numbers
                                                            
                                                            // Main area

                                                            if(!is_numeric($datas[0]))
                                                            {
                                                   $sql=       "SELECT
                                                                      idarea
                                                                FROM
                                                                                   " .$cfg["tab"]["area"]."
                                                                WHERE
                                                                                    name = \"" . $datas[0]."\"";
                                                            $db->query($sql);
                                                            $db->next_record();
                                                            $datas[0] = $db->f("idarea");
                                                            }

                                                            if (!is_numeric($datas[1])){

                                                            // Action
                                                            $sql=       "SELECT
                                                                      idaction
                                                                FROM
                                                                                   " .$cfg["tab"]["actions"]."
                                                                WHERE
                                                                                    name = \"" . $datas[1]."\"";
                                                            $db->query($sql);
                                                            $db->next_record();
                                                            $datas[1] = $db->f("idaction");
                                                            }
                                                            
				if (!isset($datas[1]))
				{
				$datas[1] = 0;
				}
                                                            // Insert new right                                                        
                              $sql="INSERT INTO ".$cfg["tab"]["rights"]."
                                    (idright, user_id,idarea,idaction,idcat,idclient,idlang,type)
                                    VALUES ('".$db->nextid($cfg["tab"]["rights"])."', '$userid','$datas[0]','$datas[1]','$datas[2]','$rights_client','$rights_lang',0)";
		$db->query($sql);
                     }


        }
        $rights_list_old=$rights_list;

    $notification->displayNotification("info", i18n("Changes saved"));



}
















?>
