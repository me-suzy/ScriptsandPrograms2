<?php

if (isset($_GET["dele"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly updated the Password list.<br />Refreshing Status List!</font></div>\r\n");
   echo ("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=password\">");
}; //end isset $dele

if (isset($_POST["a"])){

   $db->addRow("password", array($_POST["old"], $_POST["new"]), false);
   $db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly updated the Password list.</font></div>\r\n");

}; //end isset $a

echo ("<form method=\"post\" action=\"\">");
table_header("Password Forums");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr class=\"background\" align=\"center\">
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Forum ID</b></td>
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Password</b></td>
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Delete</b></td>
  </tr>");
  
for ($password = 2; $password < count($db->data["_DB"]["password"]); $password++){
  echo ("<tr align=\"center\">
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["password"]["$password"][0]."</td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["password"]["$password"][1]."</td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=password&dele=$password\" title=\"[ $password ]\">Purge</a></td>
  </tr>");
}; //end for $password
  
echo ("
  <tr align=\"center\">
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><select name=\"old\">");

     for ($f = 2; $f < count($db->data["_DB"]["forums"]); $f++){
      $fo = $db->data["_DB"]["forums"]["$f"];
     echo ("<option value=\"$fo[7]\">($fo[7]) $fo[0]</option>");
     };


     echo ("</select></td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"new\" size=\"20\"></td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"submit\" name=\"a\" value=\"Add to password\" /></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

if (isset($_GET["dele"])){
  $db->deleteRow("password", $_GET["dele"]);
  $db->reBuild();
}; //end isset $dele

?>
