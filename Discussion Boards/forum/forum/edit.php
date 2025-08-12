<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; New Thread";


include("header.php");

$id = $_GET["fid"];
$tid = $_GET["tid"];

$line = $_GET["reply"];
$context = $db->data["_DB"]["posts"]["$line"][4];
$date_e = $db->data["_DB"]["posts"]["$line"][3];
$poster_o = $db->data["_DB"]["posts"]["$line"][2];
$rowid2 = $db->query("forums", "7", $id);
$mods = explode(", ", $db->data["_DB"]["forums"]["$rowid2"][4]); //explode the mods table

$context = str_replace("<br>", "\n", $context);
$context = str_replace("<", "[", $context);
$context = str_replace(">", "]", $context);



if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">In order to create topics, post replies or Edit you must be logged in!<br />Register now, <a href=\"register.php\">here</a>, its FREE! and easy.</font></div>\r\n");
} else {

//get the row number that the topic id is on


echo ("<form method=\"post\" action=\"\">");

$rowid = $db->query("topics", "7", $tid);
if ($db->data["_DB"]["topics"]["$rowid"][6] == "true"){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Editing Replies cannot be made to this thread because it is locked.<br />Only an Administrator or Moderator can unlock this!</font></div>\r\n");
} else {

if (isset($_POST["view"])){
     $db->createTable("VIEW");
     $db->addRow("VIEW", array($_POST["icon"], "", $_SESSION["user"], $date_e, $_POST["msg"], "$tid"));
     table_header("View Post");
     $context2 = $db->data["_DB"]["VIEW"]["1"][4];
     $context = stripslashes($_POST["msg"]);
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$context2 &nbsp;</font></td>
   </tr>
</table>");

table_footer();
}; //end if

if (isset($_POST["a"])){
  if ($_POST["a"]){


     //add this into the database
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[59]<br /><br /></font></div>\r\n");
     $db->editRow("posts", $line, array($_POST["icon"], "", "$poster_o", $date_e, $_POST["msg"], "$tid"));




     $db->reBuild();
     echo ("<meta http-equiv=\"refresh\" content=\"2;url=view_forum.php?fid=$id\">");


  }; //end $a
}; //end isset $a
$r = $_GET["reply"];
if ($db->data["_DB"]["posts"]["$r"]["2"] == @$_SESSION["user"] or $user_power == "1" or (in_array(@$_SESSION["user"], $mods))){

table_header("$_LANG[58]");

$align = "left";

echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\" align=\"$align\">
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$_LANG[60]:</b></font></td>
      <td width=\"70%\" colspan=\"1\">

         <table width=\"100%\" align=\"$align\" border=\"0\">
            <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon1.gif\" checked> <img src=\"icon/icon1.gif\" alt=\"icon1.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon2.gif\"> <img src=\"icon/icon2.gif\" alt=\"icon2.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon3.gif\"> <img src=\"icon/icon3.gif\" alt=\"icon3.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon4.gif\"> <img src=\"icon/icon4.gif\" alt=\"icon4.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon5.gif\"> <img src=\"icon/icon5.gif\" alt=\"icon5.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon6.gif\"> <img src=\"icon/icon6.gif\" alt=\"icon6.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon7.gif\"> <img src=\"icon/icon7.gif\" alt=\"icon7.gif\"></td>
            </tr>
            <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon8.gif\"> <img src=\"icon/icon8.gif\" alt=\"icon8.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon9.gif\"> <img src=\"icon/icon9.gif\" alt=\"icon9.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon10.gif\"> <img src=\"icon/icon10.gif\" alt=\"icon10.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon11.gif\"> <img src=\"icon/icon11.gif\" alt=\"icon11.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon12.gif\"> <img src=\"icon/icon12.gif\" alt=\"icon12.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon13.gif\"> <img src=\"icon/icon13.gif\" alt=\"icon13.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon14.gif\"> <img src=\"icon/icon14.gif\" alt=\"icon14.gif\"></td>
           </tr>
           <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon31.gif\"> <img src=\"icon/icon31.gif\" alt=\"icon31.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon16.gif\"> <img src=\"icon/icon16.gif\" alt=\"icon16.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon17.gif\"> <img src=\"icon/icon17.gif\" alt=\"icon17.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon18.gif\"> <img src=\"icon/icon18.gif\" alt=\"icon18.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon19.gif\"> <img src=\"icon/icon19.gif\" alt=\"icon19.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon20.gif\"> <img src=\"icon/icon20.gif\" alt=\"icon20.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon21.gif\"> <img src=\"icon/icon21.gif\" alt=\"icon21.gif\"></td>
           </tr>
           <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon22.gif\"> <img src=\"icon/icon22.gif\" alt=\"icon22.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon23.gif\"> <img src=\"icon/icon23.gif\" alt=\"icon23.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon24.gif\"> <img src=\"icon/icon24.gif\" alt=\"icon24.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon25.gif\"> <img src=\"icon/icon25.gif\" alt=\"icon25.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon26.gif\"> <img src=\"icon/icon26.gif\" alt=\"icon26.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon27.gif\"> <img src=\"icon/icon27.gif\" alt=\"icon27.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon28.gif\"> <img src=\"icon/icon28.gif\" alt=\"icon28.gif\"></td>
          </tr>
          <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon29.gif\"> <img src=\"icon/icon29.gif\" alt=\"icon29.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon30.gif\"> <img src=\"icon/icon30.gif\" alt=\"icon30.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon32.gif\"> <img src=\"icon/icon32.gif\" alt=\"icon32.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon33.gif\"> <img src=\"icon/icon33.gif\" alt=\"icon33.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon34.gif\"> <img src=\"icon/icon34.gif\" alt=\"icon34.gif\"></td>
               <td width=\"14%\" colspan=\"1\">&nbsp;</td>
               <td width=\"14%\" colspan=\"1\">&nbsp;</td>
          </td>
         </table>

      </td>
   </tr>
   <tr bgcolor=\"$tbackground2\" align=\"$align\">
      <td width=\"30%\" colspan=\"1\" valign=\"top\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$_LANG[63]:</b></font></td>
      <td width=\"70%\" colspan=\"1\"><textarea name=\"msg\" rows=\"8\" cols=\"60\">$context</textarea></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" align=\"$align\">
      <td width=\"100%\" colspan=\"2\" valign=\"top\"><input type=\"submit\" name=\"a\" value=\"$_LANG[61]\"> <input type=\"submit\" name=\"view\" value=\"$_LANG[62]\"></td>
   </tr>
</table>");

table_footer();

} else {

  echo ("<center><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[64]</font></center>");

  }; //end if
  
}; //end locked

echo ("</form>");



}; //end !is_logged_in

include("footer.php");

?>
