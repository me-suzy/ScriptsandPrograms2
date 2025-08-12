<?php

$where = "<a href=\"index.php\">Home</a> &gt; Logging On";
$reg = "valid"; //for core_signup

include("header.php");


if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">You are logged in as <b>".$_SESSION["user"]."</b><br />\r\n you may login as someone else immediatly if you wish...</font></div>\r\n");
 };


if (isset($_POST["a"])){
  if ($_POST["a"]){

$found = $db->query("users", "0", $_POST["user"]);

if ($_POST["user"] != null){
  if ($found != ""){

  $array = $db->data["_DB"]["users"]["$found"];
  if ($_POST["user"] == $array[0] and $_POST["pass"] == $array[1]){
    echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly logged on as <b>$array[0]</b>, Redirecting...</font></div>");
    echo ("<meta http-equiv=\"refresh\" content=\"2;url=index.php\">");

    $_SESSION["user"] = $_POST["user"]; //set the session for is_logged_in function
    $_SESSION["pass"] = $_POST["pass"]; //set the session for is_logged_in function

    } else {
    echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Invalid Username or Password</font></div>");
  }; //end else
  } else {
    echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Username (".$_POST["user"].") doesnt exist in the database!</font></div>");
    }; //end if
}; //end if

  }; //end $a
}; //end isset $a

echo ("<form method=\"post\" action=\"".$_SERVER["PHP_SELF"]."\">");

table_header("User Login");
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
      <td width=\"100%\" colspan=\"2\"><input type=\"submit\" name=\"a\" value=\"Login Now\" /></td>
   </tr>
</table>");
table_footer();

echo ("</form>");

include("footer.php");

?>
