<?php

$where = "<a href=\"index.php\">Home</a> &gt; Profile";

include("header.php");

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">No such profile can be found in the database<br />Try logout and login again.</font></div>\r\n");
} else {

$id = $db->query("users", "0", $_SESSION["user"]);
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

    $db->editRow("users", "$id", array("$array[0]", "$pass", $_POST["mail"], "$array[3]", "$array[4]", $_POST["dpic"], $_POST["name"], $_POST["wurl"], $array[8], $_POST["steam"], $_POST["aim"], $_POST["icq"], $_POST["msn"], $_POST["yahoo"], $_POST["xfire"]
    ));
    $db->data["_DB"]["users"]["$id"]["8"] = $array["8"];
    $db->reBuild();
    echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly changed profile, Refresh to view changes...<br /></font></div>\r\n");

  }; //end $a
}; //end isset $a

echo ("<form method=\"post\" action=\"\">");

table_header("User Profile");
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

}; //end !is_logged_in

include("footer.php");

?>
