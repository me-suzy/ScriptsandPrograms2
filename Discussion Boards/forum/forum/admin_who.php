<?php


echo ("<form method=\"post\" action=\"\">");
table_header("Whos Online");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr class=\"background\">
     <td  colspan=\"1\" width=\"20%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Username</b></td>
     <td  colspan=\"1\" width=\"20%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>IP Address</b></td>
     <td  colspan=\"1\" width=\"5%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Time</b></td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Last Seen (URL referer)</b></td>
  </tr>");
  
for ($i = 2; $i < count($db->data["_DB"]["whos_online"]); $i++){
$result = $db->data["_DB"]["whos_online"][$i];
    echo ("<tr>
     <td  colspan=\"1\" width=\"20%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$result[0]</td>
     <td  colspan=\"1\" width=\"20%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$result[1]</td>
     <td  colspan=\"1\" width=\"5%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$result[3]</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"$result[2]\" target=\"_blank\">$result[2]</a></td>
  </tr>");
  
};


echo ("</table>");
table_footer();

echo ("</form>");

?>
