<?php

echo ("<style type=\"text/css\">
.pagenum A { BORDER-RIGHT: #000000 1px solid; PADDING-RIGHT: 4px; BORDER-TOP: #000000 1px solid; PADDING-LEFT: 4px; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: #000000 1px solid; WIDTH: 1em; COLOR: #000000; PADDING-TOP: 1px; BORDER-BOTTOM: #000000 1px solid; BACKGROUND-COLOR: #CCCCCC; TEXT-DECORATION: none }
</style>");

if (!isset($_GET["page"])){
  $_GET["page"] = "1";
};

if (isset($_GET["edit"])){

$id = $_GET["edit"];
$array = $db->data["_DB"]["users"]["$id"];


if (isset($_POST["a"])){
  if ($_POST["a"]){

  $pass = $array[1];
  if ($_POST["pass"] != ""){
    if ($_POST["pass2"] == $_POST["pass"]){
      $pass = $_POST["pass"];
       echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly Changed your password!<br /></font></div>\r\n");
  //password changed;
    } else {
      //passwords do not match
      //failed change
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Sorry!, could not change your password because they did not match!</font></div>\r\n");
    }; //end if
  }; //end if

    @$db->editRow("users", "$id", array("$array[0]", "$pass", $_POST["mail"], $_POST["power"], $_POST["posts"], $_POST["dpic"], $_POST["name"], $_POST["wurl"], $_POST["bonus"], $_POST["steam"], $_POST["aim"], $_POST["icq"], $_POST["msn"], $_POST["yahoo"], $_POST["xfire"]), false);
    $db->reBuild();
    echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly changed profile, Refresh to view changes...<br /></font></div>\r\n");

  }; //end $a
}; //end isset $a

echo ("<form method=\"post\" action=\"\">");

table_header("Edit user Profile");
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Password:<br /><i>Fill in only if you wish to change your password</i></font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"password\" name=\"pass\" size=\"30\" maxlength=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Again:<br />Must match the above for re-assurance.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"password\" name=\"pass2\" size=\"30\" maxlength=\"30\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"2\">&nbsp;</td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>E-Mail Address:</b><br />For others to be able to contact you privatly</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"mail\" size=\"30\" value=\"$array[2]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Display Picture:</b><br />Picture shown with every post you make</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"dpic\" size=\"30\" value=\"$array[5]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Alias Name:</b><br />If filled in, this will show, not your username.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"30\" value=\"$array[6]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Website URL:</b><br />For others to view your homepage and find more info about you</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"wurl\" size=\"30\" value=\"$array[7]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Post-Count:</b><br />Modify the amount of posts this user appears to have posted</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"posts\" size=\"30\" value=\"$array[4]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Bonus Status:</b><br />This will be a status which you can choose the member to have.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"bonus\" size=\"30\" value=\"".@$array[8]."\" /></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Power:</b><br />Allows you to make this user an admin or normal member</font></td>
      <td width=\"60%\" colspan=\"1\"><select name=\"power\"><option value=\"$array[3]\">Current</option><option value=\"2\">Member</option><option value=\"1\">Administrator</option></select></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"2\">&nbsp;</td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Steam:</b><br />Allow other HL2 members to know your Steam username.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"steam\" size=\"30\" maxlength=\"30\" value=\"$array[9]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>AIM:</b><br />Why not let others add you on AIM to chat.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"aim\" size=\"30\" value=\"$array[10]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>ICQ:</b><br />Chat instantly on ICQ with other members of the forum.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"icq\" size=\"30\" maxlength=\"30\" value=\"$array[11]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>MSN:</b><br />Allow other MSN users to add you onto MSN Messenger</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"msn\" size=\"30\" value=\"$array[12]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Yahoo!:</b><br />Show other users you have Yahoo!.</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"yahoo\" size=\"30\" maxlength=\"30\" value=\"$array[13]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>XFire:</b><br />Allow other gamers to know your username for XFire</font></td>
      <td width=\"60%\" colspan=\"1\"><input type=\"text\" name=\"xfire\" size=\"30\" value=\"$array[14]\"></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"2\"><input type=\"submit\" name=\"a\" value=\"Update Profile\"></td>
   </tr>
</table>");
table_footer();

echo ("</form>");



}; //end if isset $edit

$total = count($db->data["_DB"]["users"]);

$pp = "15";
$start = $pp * $_GET["page"] - $pp + 2;
$finish = $pp * $_GET["page"] + 2;

$pages = $total / $pp;
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" align=\"center\">
<tr bgcolor=\"$tbackground1\">
<td>");

for ($p = 1; $p < $pages + 1; $p++){
echo ("      <span class=\"pagenum\"><font size=\"$fmedium\" color=\"$fsubtitle\" face=\"$fface\"><b><a href=\"admin.php?auth=members&page=$p\">$p</a></b></font></span>   ");
}; //end for
echo ("</td></tr></table><br />");


table_header("Edit Members");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr class=\"background\">
      <td width=\"25%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Username</b></font></td>
      <td width=\"15%\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Status</b></font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Posts</b></font></td>
      <td width=\"20%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Alias</b></font></td>
      <td width=\"25%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Password</b></font></td>
      <td width=\"5%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Edit</b></font></td>
   </tr>");


for ($members = $start; $members < $finish; $members++){

if (@$db->data["_DB"]["users"]["$members"][3] == "1"){
  $status = "[ <font color=\"$cadmin\"><i>Administrator</i></font> ]";
$mpass = "<i>******</i>";
} else {
  $status = "<font color=\"$cmember\">Member</font>";
$mpass = @$db->data["_DB"]["users"]["$members"][1];
}; //end if


   if (isset($db->data["_DB"]["users"]["$members"])){
   echo ("<tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"25%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["users"]["$members"][0]."</font></td>
      <td width=\"15%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$status</font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["users"]["$members"][4]."</font></td>
      <td width=\"20%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["users"]["$members"][6]."</font></td>
      <td width=\"25%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$mpass</a></font></td>
      <td width=\"5%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=members&edit=$members\" title=\"Edit: $members\">Edit</a></font></td>
   </tr>");
   };
}; //end for $members



echo ("</table>");
table_footer();
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" align=\"center\">
<tr bgcolor=\"$tbackground1\">
<td>");

for ($p = 1; $p < $pages + 1; $p++){
echo ("      <span class=\"pagenum\"><font size=\"$fmedium\" color=\"$fsubtitle\" face=\"$fface\"><b><a href=\"admin.php?auth=members&page=$p\">$p</a></b></font></span>   ");
}; //end for
echo ("</td></tr></table><br />");



?>
