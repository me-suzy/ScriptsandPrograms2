<?php

if (($perm->have_perm_area_action($area, $action)) && ($action == "group_edit"))
{
    saverights();
}else {
    if (!$perm->have_perm_area_action($area, $action))
    {
    $notification->displayNotification("error", i18n("Permission denied"));
    }
}


        // declare new javascript variables;
        echo"<script type=\"text/javascript\">
              var itemids=new Array();
              var actareaids=new Array();
        </script>";

        $colspan=0;

        $table = new Table($cfg["color"]["table_border"], "solid", 0, 2, $cfg["color"]["table_header"], $cfg["color"]["table_light"], $cfg["color"]["table_dark"]);

        $table->start_table();
        $table->header_row();
        $table->header_cell(i18n("Category"),"left");
		$table->header_cell("&nbsp;","left");

        $possible_areas=array();




        // look for possible actions   in mainarea []   in str and con
        foreach($right_list["str"] as $value2)
        {
               //if there are some actions
               if(is_array($value2["action"]))
                 foreach($value2["action"] as $key3 => $value3)
                 {       //set the areas that are in use

                        # HACK!
                        if ($value3 != "str_newtree")
                        {
                         $possible_areas[$value2["perm"]]="";


                         $colspan++;
                         //set  the possible areas and actions for this areas
                         echo"<script type=\"text/javascript\">
                               actareaids[\"$value3|".$value2["perm"]."\"]=\"x\";
                              </script>";

                         $table->header_cell($lngAct[$value2["perm"]][$value3]."<br>
                         <input type=\"checkbox\" name=\"checkall_".$value2["perm"]."_$value3\" value=\"\" onClick=\"setRightsFor('".$value2["perm"]."','$value3','')\">");
                        }
                 }
        }





        //checkbox for all rights
        $table->header_cell(i18n("Check all")."<br><input type=\"checkbox\" name=\"checkall\" value=\"\" onClick=\"setRightsForAll()\">");
        $table->end_row();
        $colspan++;



        //If rights_list is not set or you browse from a other site    make a new rights_list from this user
        if(!isset($rights_list_old)||$action==""||!isset($action)){

                       $sess->register("rights_list_old");
                       //set the areas which are in use fore selecting these
                       $possible_area="'".implode("','",array_keys($possible_areas))."'";
                       //$sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE user_id='$rights_user' AND idclient='$client' AND idlang='$rights_lang' AND idarea IN ($possible_area) AND idcat!='0'";
                       $sql="SELECT A.idarea, A.idaction, A.idcat, B.name, C.name FROM ".$cfg["tab"]["rights"]." AS A, ".$cfg["tab"]["area"]." AS B, ".$cfg["tab"]["actions"]." AS C WHERE user_id='$groupid' AND idclient='$client' AND idlang='$rights_lang' AND B.name IN ($possible_area) AND idcat!='0' AND A.idaction = C.idaction AND A.idarea = C.idarea AND A.type = 1";
                       $db->query($sql);
                       $rights_list_old=array();
                       while($db->next_record())
                       {     //set a new rights list fore this user
                             $rights_list_old[$db->f(3)."|".$db->f(4)."|".$db->f("idcat")]="x";
                       }

        }





        $sql = "SELECT A.idcat, level, name,parentid FROM ".$cfg["tab"]["cat_tree"]." AS A, ".$cfg["tab"]["cat"]." AS B, ".$cfg["tab"]["cat_lang"]." AS C WHERE A.idcat=B.idcat AND B.idcat=C.idcat AND C.idlang='$rights_lang' AND B.idclient='$rights_client' ORDER BY idtree";
        $db->query($sql);
        $counter=array();
        $parentid="leer";
        while ($db->next_record()) {

                if ($db->f("level") == 0 && $db->f("preid") != 0) {
                        echo "<TR><TD colspan=13>&nbsp;</TD></TR>";
                }else {
                        //find out parentid for inheritance
                        //if parentid is the same increase the counter
                        if($parentid==$db->f("parentid")){

                           $counter[$parentid]++;
                        }else{
                           $parentid=$db->f("parentid");
                           // if these parentid is in use increase the counter
                           if(isset($counter[$parentid])){
                                 $counter[$parentid]++;
                           }else{
                                 $counter[$parentid]=0;
                           }


                        }
                        //set javscript array for itemids
                        echo"<script type=\"text/javascript\">
                               itemids[\"".$db->f("idcat")."\"]=\"x\";

                             </script>";


                        $spaces = "";

                        for ($i=0; $i<$db->f("level"); $i++) {
                             $spaces = $spaces . "&nbsp;&nbsp;&nbsp;&nbsp;";
                        }

                        $table->row();
                        $inheritUp = i18n("Apply rights for this category to all categories on the same level or above");
                        $inheritDown = i18n("Apply rights for this category to all categories below the current category");
                        $table->cell('<img src="images/spacer.gif" height="1" width="'.($db->f("level")*15).'">'.$db->f("name"),"left");
                        $table->cell("<a href=\"javascript:rightsInheritanceUp('$parentid','$counter[$parentid]')\" class=\"action\"><img border=\"0\" src=\"images/pfeil_links.gif\" alt=\"".$inheritUp."\" title=\"".$inheritUp."\"></a>    <a href=\"javascript:rightsInheritanceDown('".$db->f("idcat")."')\" class=\"action\"><img border=\"0\" src=\"images/pfeil_runter.gif\" alt=\"".$inheritDown."\" title=\"".$inheritDown."\"></a>","left","center","nowrap");



                        // look for possible actions in mainarea[]

                        foreach($right_list["str"] as $value2){

                                //if there area some
                                if(is_array($value2["action"]))
                                  foreach($value2["action"] as $key3 => $value3)
                                  {
                                        # HACK!
                                        if ($value3 != "str_newtree")
                                        {
                                           //does the user have the right
                                           if(in_array($value2["perm"]."|$value3|".$db->f("idcat"),array_keys($rights_list_old)))
                                               $checked="checked=\"checked\"";
                                           else
                                               $checked="";

                                           //set the checkbox    the name consits of      areaid+actionid+itemid        the    id  =  parebntid+couter for these parentid+areaid+actionid
                                           $table->cell("
                                           <input type=\"checkbox\" id=\"str_".$parentid."_".$counter[$parentid]."_".$value2["perm"]."_$value3\" name=\"rights_list[".$value2["perm"]."|$value3|".$db->f("idcat")."]\" value=\"x\" $checked>
                                           ");


                                  }
                                  }
                        }

                         //checkbox for checking all actions fore this itemid
                         $table->cell("
                               <input type=\"checkbox\" name=\"checkall_".$value2["perm"]."_".$value3."_".$db->f("idcat")."\" value=\"\" onClick=\"setRightsFor('".$value2["perm"]."','$value3','".$db->f("idcat")."')\">
                               ");



                }

}


$table->end_row();
$table->row();
$table->sumcell("<a href=javascript:submitrightsform('','area')><img src=\"".$cfg['path']['images']."but_cancel.gif\" border=0></a><img src=\"images/spacer.gif\" width=\"20\"> <a href=javascript:submitrightsform('group_edit','')><img src=\"".$cfg['path']['images']."but_ok.gif\" border=0></a>","right");
$table->end_row();
$table->end_table();







?>
