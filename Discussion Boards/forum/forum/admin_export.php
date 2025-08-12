<?php

if (isset($_POST["a"])){

   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Exported the database <b>".$_POST["export"]."</b> Sucessfuly!</font></div>\r\n");
   $db->export($_POST["export"], "port/".$_POST["export"].".def", "<~>");

}; //end isset $a

echo ("<form method=\"post\" action=\"\">");
table_header("Export a Database");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr>
     <td  colspan=\"1\" width=\"100%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">
      Please select the database you would like to export!<br /><br /><select name=\"export\">");

    foreach ($db->data["_DB"] as $option){
      echo ("<option value=\"".$option[0][0]."\">".$option[0][0]."</option>\r\n");
    }; //end foreach
      
    echo ("</select> <input type=\"submit\" name=\"a\" value=\"Export DB\"></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

?>
