<?php

$where = "<a href=\"index.php\">Home</a>";

include("header.php"); //execute the header



//forum display
for ($cats = 2; $cats < count(@$db->data["_DB"]["cats"]); $cats++){

table_header($db->data["_DB"]["cats"][$cats][0]."");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\" class=\"background\">
      <td width=\"10%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"></font></td>
      <td width=\"40%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[10]</b></font></td>
      <td width=\"10%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[11]</b></font></td>
      <td width=\"10%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[12]</b></font></td>
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[13]</b></font></td>
   </tr>");

   //show forum
   for ($forum = 2; $forum < count($db->data["_DB"]["forums"]); $forum++){

$musthave = trim($db->data["_DB"]["forums"]["$forum"][8]); //the forum requirement level for viewing
$mustid = array_search("$musthave", $status);

if (@$status["$mustid"] == "$musthave"){

     if ($db->data["_DB"]["forums"]["$forum"][5] == $db->data["_DB"]["cats"]["$cats"]["2"]){
     
        if ($db->data["_DB"]["forums"]["$forum"][4] == ""){
          $mods = "<i>$_LANG[14]</i>";
        } else {
          $mods = $db->data["_DB"]["forums"]["$forum"][4];
        }; //end if
        
        $nonew = "true";
        
        $newf = $db->data["_DB"]["forums"]["$forum"][7];

        for ($i = 2; $i < count($db->data["_DB"]["topics"]); $i++){
         $topic = $db->data["_DB"]["topics"]["$i"];
        if (@$topic["5"] == $newf){
          $nonew = "false";
        $last_topic = $i;
          };
        };
        

        $img = "$skins/other/old.gif";
        if ($nonew == "true"){
        $last_topic = "not found";
        };
        
        
                $lr_lasttopic = @$db->data["_DB"]["topics"]["$last_topic"];

 $lr_row = "";
        for ($lr = 2; $lr < count($db->data["_DB"]["posts"]); $lr++){
  if (@$db->data["_DB"]["posts"]["$lr"][5] == @$lr_lasttopic[7]){
            $lr_row = $lr;
          }; //end if
        }; //end for;

         $lr_info = @$db->data["_DB"]["posts"]["$lr_row"];

        if ($lr_info[3] == ""){
          $lr_info = @$db->data["_DB"]["topics"]["$last_topic"];
        };
        
        if (@$db->data["_DB"]["topics"]["$last_topic"] == ""){
          $lr_info = array("icon/icon1.gif", "<i>$_LANG[16]</i>", "</b><i>$_LANG[17]</i><b>", "<i>$_LANG[18]</i>", "$_LANG[19]");
        };



          #$last_month = explode(" ", $db->data["_DB"]["topics"]["$last_topic"][3]);
          $last_month = explode(" ", $lr_info[3]);
          $last_month = $last_month[1];
          $lastt = substr($lr_info[3], 0, 2);

        if (@$lastt >= $laston and $last_month == $month.","){
          $img = "$skins/other/new.gif";
        };
        

        if ($db->data["_DB"]["forums"]["$forum"][6] != null){  //link_to_forum != nothing
          $link = "index.php?forum=".$db->data["_DB"]["forums"]["$forum"][6];
          $img = "$skins/other/link_forum.gif";
        } else {
          $link = "view_forum.php?fid=".$db->data["_DB"]["forums"]["$forum"][7];
        }; //end f
        
        $forumpos = $db->data["_DB"]["forums"]["$forum"][7];

   echo ("<tr bgcolor=\"#FFFFFF\" class=\"height\">
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\"><img src=\"$img\" alt=\"Forum Status Image\" /></font></td>
      
      <td width=\"40%\" colspan=\"1\" bgcolor=\"$tbackground1\" onmouseover=\"this.bgColor='$tbackground2'\" onmouseout=\"this.bgColor='$tbackground1'\">
          <font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\"><a href=\"$link\">".$db->data["_DB"]["forums"]["$forum"][0]."</a><br />".$db->data["_DB"]["forums"]["$forum"][1]."</font> <font color=\"$fcfade\" size=\"$fsmall\" face=\"$fface\"><br />
          $_LANG[15]: $mods</font></td>

      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">".$db->data["_DB"]["forums"]["$forum"][2]."</font></td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">".$db->data["_DB"]["forums"]["$forum"][3]."</font></td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground1\" align=\"left\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">
      <table width=\"100%\" align=\"center\" cellspacing=\"5\">
<tr>
   <td width=\"1\"><img src=\"$lr_info[0]\" alt=\"shortcut\" /></td>
   <td width=\"100%\"><a href=\"view_thread.php?fid=$forumpos&tid=$lr_lasttopic[7]\">$lr_lasttopic[1]</a> $_LANG[20]: <b>$lr_info[2]</b><br />
    </font>  <font color=\"$fcfade\" size=\"$fsmall\" face=\"$fface\">$_LANG[21]: $lr_info[3]</font></td>
</tr>
</table>

      </td>
   </tr>");
   }; //end if
   
}; //end requirement check
   
   }; //end for $forum
   //end forum
   
   //continue catagory
   
echo ("</table>");
table_footer();

};  //end for $cats



//forum display


include("footer.php");  //execute the footer


?>
