<?php

if (isset($_GET["cid"])){

$id = $_GET["cid"];  //get the cat id

if (isset($_POST["a"])){

$db->data["_DB"]["cats"]["$id"][0] = $_POST["nme"];
$new_position = $_POST["position"];

if ($_POST["position"] != $id){
$old = $db->data["_DB"]["cats"]["$id"]; //store the old one
$db->data["_DB"]["cats"]["$id"] = $db->data["_DB"]["cats"]["$new_position"]; //set the new one
$db->data["_DB"]["cats"]["$new_position"] = $old; //recall the old one
};


$db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly edited the catagory to <b>".$_POST["nme"]."</b>.<br /></font></div>\r\n");

}; //end isset $a


echo ("<form method=\"post\" action=\"\">");

#echo ("<pre>");
#print_r($db->data["_DB"]["cats"]);
$tot = count($db->data["_DB"]["cats"]) - 1;
         $up = $_GET["cid"]- 1;
         $dw = $_GET["cid"]+ 1;

table_header("Edit Catagories");
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\" onmouseover=\"this.bgColor='$tbackground1'\" onmouseout=\"this.bgColor='$tbackground2'\">
      <td width=\"50%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Catagory Name</b><br />Enter the new name for this catagory to be displayed.</font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"nme\" value=\"".$db->data["_DB"]["cats"]["$id"][0]."\" size=\"25\"></font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" onmouseover=\"this.bgColor='$tbackground1'\" onmouseout=\"this.bgColor='$tbackground2'\">
      <td width=\"50%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Move Catagory</b><br />Move the catagory up or down</font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><select name=\"position\">
         <option value=\"".$_GET["cid"]."\">Current</option>");


         
         if ($_GET["cid"] > 2){
             echo ("<option value=\"$up\">Up</option>");
         };
         if ($_GET["cid"] < $tot){
             echo ("<option value=\"$dw\">Down</option>");
         };


      echo ("</select></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" onmouseover=\"this.bgColor='$tbackground1'\" onmouseout=\"this.bgColor='$tbackground2'\">
      <td width=\"20%\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"a\" value=\"Update!\"></td>
   </tr>
</table>");
table_footer();

echo ("</form>");

}; //end isset $cid

if (isset($_GET["add"])){

$db->data["_DB"]["key"]["2"][0]++;
$primarykey = $db->data["_DB"]["key"]["2"][0];

$db->addRow("cats", array($_POST["aname"], date($date), $primarykey));
$db->reBuild();
     echo ("<meta http-equiv=\"refresh\" content=\"0;url=admin.php?auth=cats\">");
 echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly added the catagory <b>".$_POST["aname"]."</b>.<br /></font></div>\r\n");

}; //end isset $add


echo ("<form method=\"post\" action=\"admin.php?auth=cats&add=true\">");

table_header("Manage Catagories");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr class=\"background\">
      <td width=\"10%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>ID</b></font></td>
      <td width=\"45%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Name</b></font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Date Added</b></font></td>
      <td width=\"5%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Edit</b></font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Delete</b></font></td>
   </tr>");
   
for ($cats = 2; $cats < count($db->data["_DB"]["cats"]); $cats++){

   echo ("<tr bgcolor=\"$tbackground2\" onmouseover=\"this.bgColor='$tbackground1'\" onmouseout=\"this.bgColor='$tbackground2'\">
      <td width=\"10%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["cats"]["$cats"][2]."</font></td>
      <td width=\"45%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["cats"]["$cats"][0]."</font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["cats"]["$cats"][1]."</font></td>
      <td width=\"5%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=cats&cid=$cats\">Edit</a></font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=cats&dele=$cats\">Purge</a></font></td>
   </tr>");

}; //end for $cats

  echo(" <tr bgcolor=\"$tbackground2\" onmouseover=\"this.bgColor='$tbackground1'\" onmouseout=\"this.bgColor='$tbackground2'\">
      <td width=\"50%\" colspan=\"2\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>New Catagory Name</b>.</font></td>
      <td width=\"30%\" colspan=\"2\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"aname\" size=\"25\"></font></td>
      <td width=\"20%\" colspan=\"1\" align=\"center\"><input type=\"submit\" name=\"add\" value=\"add!\"></td>
   </tr>");

   
echo ("
</table>");
table_footer();

if (isset($_GET["dele"])){
  $id = $_GET["dele"];



//get the id's of the forums, topics and posts to be deleted
$forums_found = "false";
for ($f = 2; $f < count($db->data["_DB"]["forums"]); $f++){
  if ($db->data["_DB"]["forums"]["$f"][5] == $_GET["dele"]){
    $forums_found = "true";
  }; //end if
}; //end $f

if ($forums_found == "false"){
  echo ("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=cats\">");
$db->deleteRow("cats", $_GET["dele"]);
$db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Deleted the catagory successfuly!</b>.<br />Refreshing...</font></div>\r\n");
} else {
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Cannot delete catagory!</b>.<br />You must delete the forums inside the catagory first!...</font></div>\r\n");

}; //end if


}; //end isset $dele

echo ("</form>");

?>
