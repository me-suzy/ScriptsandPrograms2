<?php

$where = "<a href=\"index.php\">Home</a> &gt; Registration";
$reg = "valid"; //used for core_signup

include("header.php");

if ($allow_register == "false"){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">The administrator has <b>Disabled</b> registration requests<br />Sorry for any inconvenience, try again later.<br /></font></div>\r\n");
} else {

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">You are logged in as <b>".$_SESSION["user"]."</b><br />\r\n you may register as someone else if you wish...</font></div>\r\n");
 };

if (isset($_POST["a"])){
  if ($_POST["a"]){

//use a query here to check for the users existance in the users db.
$found = $db->query("users", "0", $_POST["user"]);

if ($_POST["user"] != null and $_POST["user"] != " "){
  if ($found != ""){
  echo ("<div align=\"center\"><font size=\"$fsmall\" face=\"$fface\">Failed Registration, Username already exists!</font></div>");
} else {
  echo ("<div align=\"center\"><font size=\"$fsmall\" face=\"$fface\">Registered Sucessfuly!!</font></div>");
  $db->addRow("users", array($_POST["user"], $_POST["pass"], "", "2", "0", "avatars/noavatar.gif", "", "", ""));
  $db->reBuild();
};

} else {
  echo ("<div align=\"center\"><font size=\"$fsmall\" face=\"$fface\">Enter a Username!</font></div>");
}; //end if

  }; //end if button press
}; //end if isset

echo ("<form method=\"post\" action=\"\">");

table_header("User Registration");
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Username:</font></td>
      <td width=\"70%\" colspan=\"1\"><input type=\"text\" name=\"user\" size=\"30\" maxlength=\"30\" /></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Password:</font></td>
      <td width=\"70%\" colspan=\"1\"><input type=\"password\" name=\"pass\" size=\"30\" maxlength=\"30\" /></td>
   </tr>
   <tr bgcolor=\"$tbackground2\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Again:</font></td>
      <td width=\"70%\" colspan=\"1\"><input type=\"password\" name=\"pass2\" size=\"30\" maxlength=\"30\" /></td>
   </tr>
   <tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"2\"><input type=\"submit\" name=\"a\" value=\"Register Now\" /></td>
   </tr>
</table>");
table_footer();

echo ("</form>");

} ; //end allow_register

include("footer.php");

?>
