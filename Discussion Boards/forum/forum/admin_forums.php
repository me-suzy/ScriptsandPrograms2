<?php
if (isset($_GET["add"])){

if (isset($_GET["do"])){

$db->data["_DB"]["key"]["2"][0]++;
$pkey = $db->data["_DB"]["key"]["2"][0];
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Added Forum Sucessfuly!.<br />Refreshing...</font></div>\r\n");
   echo ("<div align=\"center\"><meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=forums\"></div>\r\n");
$db->addRow("forums", array($_POST["name"], $_POST["sn"], "0", "0", $_POST["mo"], $_POST["cat"], $_POST["db"], "$pkey", $_POST["vg"], $_POST["pg"], $_POST["rg"]));
$db->reBuild();

}; //end $do

echo ("<form method=\"post\" action=\"admin.php?auth=forums&add=new&do=now\">");
table_header("Add Forums");
//name<~>sub name<~>topic count<~>post count<~>Moderators<~>Cat_ID<~>link_to_forum<~>primarykey


    echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Name</b><br />The head title of the forum.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"name\" size=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Sub Name</b><br />The sub-name of the forum, just extra information.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"sn\" size=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Moderators:</b><br />Each seperated by a comma like: John, Adam, Mark, Lucos</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"mo\" size=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Load in Catagory:</b><br />Select a catagory for the forum to load in.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"cat\">");



     for ($c = 2; $c < count($db->data["_DB"]["cats"]); $c++){
      $ca = $db->data["_DB"]["cats"][$c];
      echo ("<option value=\"$ca[2]\">$ca[0]</option>");
     }; //end foreach


      echo ("</select></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>View Group</b><br />Select a group that you want this forum viewable to.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"vg\"><option value=\"guests\">All Viewers</option><option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Topic Group?</b><br />Select a group which can create topics.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"pg\"><option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Reply Group?</b><br />Select a group which can reply to topics.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"rg\"><option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>External Database?</b><br />Want to link to a seperate/new forum...</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"db\" size=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"2\" align=\"right\"><input type=\"submit\" name=\"add\" value=\"Add Forum\"></td>
   </tr>
</table>");

table_footer();
echo ("</form>");

}; //end $add

if (isset($_GET["edit"])){
$e = $_GET["edit"];
$re = $db->data["_DB"]["forums"][$e];

if (isset($_POST["editnow"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Edited Forum Sucessfuly!.<br /></font></div>\r\n");
   $db->data["_DB"]["forums"]["$e"][0] = $_POST["name"];
   $db->data["_DB"]["forums"]["$e"][1] = $_POST["sn"];
   $db->data["_DB"]["forums"]["$e"][2] = $_POST["tc"];
   $db->data["_DB"]["forums"]["$e"][3] = $_POST["pc"];
   $db->data["_DB"]["forums"]["$e"][4] = $_POST["mo"];
   $db->data["_DB"]["forums"]["$e"][5] = $_POST["cat"];
   $db->data["_DB"]["forums"]["$e"][6] = $_POST["db"];

   $db->data["_DB"]["forums"]["$e"][8] = $_POST["vg"];
   $db->data["_DB"]["forums"]["$e"][9] = $_POST["pg"];
   $db->data["_DB"]["forums"]["$e"][10] = $_POST["rg"];

   $new_position = $_POST["position"];

   if ($_POST["position"] != $e){
$old = $db->data["_DB"]["forums"]["$e"]; //store the old one
$db->data["_DB"]["forums"]["$e"] = $db->data["_DB"]["forums"]["$new_position"]; //set the new one
$db->data["_DB"]["forums"]["$new_position"] = $old; //recall the old one
   };
   
   $db->reBuild();
   echo ("<meta http-equiv=\"refresh\" content=\"0;url=admin.php?auth=forums\">");
}; //end edit now


echo ("<form method=\"post\" action=\"admin.php?auth=forums&edit=$e\">");
$tot = count($db->data["_DB"]["forums"]) - 1;
         $up = $_GET["edit"]- 1;
         $dw = $_GET["edit"]+ 1;

table_header("Edit Forums");
//name<~>sub name<~>topic count<~>post count<~>Moderators<~>Cat_ID<~>link_to_forum<~>primarykey


    echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Name</b><br />The head title of the forum.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"name\" size=\"30\" value=\"$re[0]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Sub Name</b><br />The sub-name of the forum, just extra information.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"sn\" size=\"30\" value=\"$re[1]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Topic Count</b><br />Total topics created.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"tc\" size=\"30\" value=\"$re[2]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Post Count</b><br />Total posts created.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"pc\" size=\"30\" value=\"$re[3]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Moderators:</b><br />Each seperated by a comma like: John, Adam, Mark, Lucos</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"mo\" size=\"30\" value=\"$re[4]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Load in Catagory:</b><br />Select a catagory for the forum to load in.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"cat\"><option value=\"$re[5]\">Current</option>");



     for ($c = 2; $c < count($db->data["_DB"]["cats"]); $c++){
      $ca = $db->data["_DB"]["cats"][$c];
      echo ("<option value=\"$ca[2]\">$ca[0]</option>");
     }; //end foreach


      echo ("</select></td>
   </tr>
      <tr bgcolor=\"$tbackground1\">
      <td width=\"50%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Move Catagory</b><br />Move the catagory up or down</font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><select name=\"position\">
         <option value=\"".$_GET["edit"]."\">Current</option>");



         if ($_GET["edit"] > 2){
             echo ("<option value=\"$up\">Up</option>");
         };
         if ($_GET["edit"] < $tot){
             echo ("<option value=\"$dw\">Down</option>");
         };


      echo ("</select></td>
   </tr>
      <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>View Group</b><br />Select a group that you want this forum viewable to.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"vg\">

      <option value=\"$re[8]\">==$re[8]==</option>
      <option value=\"guests\">All Viewers</option><option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Topic Group?</b><br />Select a group which can create topics.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"pg\">
      <option value=\"$re[9]\">==$re[9]==</option>
      <option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Reply Group?</b><br />Select a group which can reply to topics.</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><select name=\"rg\">
      <option value=\"$re[10]\">==$re[10]==</option>
      <option value=\"members\">Registered Members</option><option value=\"moders\">Mods & Admins</option><option value=\"admins\">Admins only</option></select></td>
   </tr>
  <tr bgcolor=\"$tbackground1\">
      <td width=\"65%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>External Database?</b><br />Want to link to a seperate/new forum...</font></td>
      <td width=\"35%\" colspan=\"1\" align=\"center\"><input type=\"text\" name=\"db\" size=\"30\" value=\"$re[6]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"2\" align=\"right\"><input type=\"submit\" name=\"editnow\" value=\"Edit Now\"></font></td>
   </tr>
   </table>");
   
table_footer();
echo ("</form>");

}; //end $edit




echo ("<form method=\"post\" action=\"admin.php?auth=forums&add=new\">");
table_header("Manage Forums");

    echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr class=\"background\">
      <td width=\"10%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>ID</b></font></td>
      <td width=\"45%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Name</b></font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Moderators</b></font></td>
      <td width=\"5%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Edit</b></font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Delete</b></font></td>
   </tr>");
   
for ($f = 2; $f < count($db->data["_DB"]["forums"]); $f++){

$r = $db->data["_DB"]["forums"]["$f"];
   echo ("<tr>
      <td width=\"10%\" bgcolor=\"$tbackground1\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$r[7]</b></font></td>
      <td width=\"45%\" bgcolor=\"$tbackground2\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$r[0]</font></td>
      <td width=\"30%\" bgcolor=\"$tbackground1\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$r[4]</font></td>
      <td width=\"5%\" bgcolor=\"$tbackground2\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=forums&edit=$f\">Edit</a></font></td>
      <td width=\"10%\" bgcolor=\"$tbackground1\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=forums&dele=$f\">Delete</a></font></td>
   </tr>");

}; //end $f

echo ("<tr>
      <td width=\"10%\" bgcolor=\"$tbackground2\" colspan=\"5\" align=\"right\"><input type=\"submit\" name=\"add\" value=\"Create New\"></td>
      </tr>");
      
 echo ("
</table>");
table_footer();

echo ("</form>");

if (isset($_GET["dele"])){

   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Deleted</b> Forum Sucessfuly!.<br />Refreshing...</font></div>\r\n");
   echo ("<div align=\"center\"><meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=forums\"></div>\r\n");
   $df = $_GET["dele"];
   $db->deleteRow("forums", "$df");
   $db->reBuild();

}; //end $dele

?>
