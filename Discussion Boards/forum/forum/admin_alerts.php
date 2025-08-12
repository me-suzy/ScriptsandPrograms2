<?php
echo ("<form method=\"post\" action=\"admin.php?auth=alerts&add=true\">");

if (isset($_GET["add"])){
table_header("Create Personal / General Alerts");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr>
     <td colspan=\"1\" width=\"40%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>To Username / IP:</b><br />Enter a username or an IP address in here</td>
     <td colspan=\"1\" width=\"60%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"touser\" size=\"40\"></td>
  </tr>
  <tr>
     <td colspan=\"1\" width=\"40%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Message / Alert:</b><br />Create the message which the user will see.<br /><br />You may use BBCode</td>
     <td colspan=\"1\" width=\"60%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><textarea name=\"msg\" rows=\"5\" cols=\"40\"></textarea></td>
  </tr>
  <tr>
     <td colspan=\"2\" width=\"100%\" bgcolor=\"$tbackground2\"><input type=\"submit\" name=\"do\" value=\"Create Alert\" /></td>
  </tr>
</table>");
table_footer();


if (isset($_POST["do"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Created Alert for <b>$_POST[touser]</b> Successfuly<br />Redirecting...</div>");
   echo ("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=alerts\">");
   $db->addRow("alerts", array($_POST["touser"], date($date), $_POST["msg"]));
   $db->reBuild();
}; //end posted
}; //end

echo ("</form>");

table_header("Personal / General Alerts");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr>
     <td  colspan=\"1\" width=\"100%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=alerts&add=true\">Create</a>
      <hr />");
      
      for ($i = 2; $i < count($db->data["_DB"]["alerts"]); $i++){
       $item = $db->data["_DB"]["alerts"]["$i"];

       echo ("<b>To:</b> $item[0]<br /> <b>on:</b> <i>$item[1]</i><br />$item[2]<br /><a href=\"admin.php?auth=alerts&dele=$i\">[Delete]</a><hr />");
      }; //end $i;
      
      
    echo ("</td>
  </tr>
</table>");
table_footer();


if (isset($_GET["dele"])){
  $db->deleteRow("alerts", $_GET["dele"]);
  $db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Deleted Alert Successfuly<br />Redirecting...</div>");
   echo ("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=alerts\">");

}; //end isset $dele

?>
