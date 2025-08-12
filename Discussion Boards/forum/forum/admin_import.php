<?php

if (isset($_POST["a"])){

   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Imported the database <b>".$_POST["import"]."</b> Sucessfuly!</font></div>\r\n");
   $db->import("port/".$_POST["import"], $_POST["name"], "<~>");
   $db->reBuild();

}; //end isset $a

echo ("<form method=\"post\" action=\"\">");
table_header("Import a Database");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr>
     <td  colspan=\"1\" width=\"100%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">
      Please select the database you would like to import!<br /><br /><select name=\"import\">");

      $portable = $db->readDir("port", array(".htaccess"));

    foreach ($portable as $option){
      echo ("<option value=\"".$option."\">".$option."</option>\r\n");
    }; //end foreach

    echo ("</select> Name as: <input type=\"text\" name=\"name\"> <input type=\"submit\" name=\"a\" value=\"Import DB\"></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

?>
