<?php

if (isset($_GET["dele"])){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly updated the filter list.<br />Refreshing Filter List!</font></div>\r\n");
   echo ("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?auth=filter\">");
}; //end isset $dele

if (isset($_POST["a"])){

   $db->addRow("filter", array($_POST["old"], $_POST["new"]), false);
   $db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Successfuly updated the filter list.</font></div>\r\n");

}; //end isset $a

echo ("<form method=\"post\" action=\"\">");
table_header("String Filter");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr class=\"background\" align=\"center\">
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Old String</b></td>
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>New String</b></td>
     <td  colspan=\"1\" width=\"33%\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Delete</b></td>
  </tr>");
  
for ($filter = 2; $filter < count($db->data["_DB"]["filter"]); $filter++){
  echo ("<tr align=\"center\">
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["filter"]["$filter"][0]."</td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["filter"]["$filter"][1]."</td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"admin.php?auth=filter&dele=$filter\" title=\"[ $filter ]\">Purge</a></td>
  </tr>");
}; //end for $filter
  
echo ("
  <tr align=\"center\">
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"old\" size=\"20\"></td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"text\" name=\"new\" size=\"20\"></td>
     <td  colspan=\"1\" width=\"33%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><input type=\"submit\" name=\"a\" value=\"Add to Filter\" /></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

if (isset($_GET["dele"])){
  $db->deleteRow("filter", $_GET["dele"]);
  $db->reBuild();
}; //end isset $dele

?>
