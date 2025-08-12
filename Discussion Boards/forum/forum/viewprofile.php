<?php

$where = "<a href=\"index.php\">Home</a> &gt; View Members Profile";

include("header.php"); //execute the header

$vuid = $_GET["uid"];
$vuser = $db->data["_DB"]["users"]["$vuid"];

if ($vuser[3] == "1"){
  $status = "[ <font color=\"$cadmin\">$_LANG[85]</font> ]";
} else {
  $status = "[ <font color=\"$cmember\">$_LANG[86]</font> ]";
};

table_header("Profile Page");
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\" class=\"background\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[87]</b></font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[88]</b></font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[89]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[0]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[90]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[6]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[91]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[2]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[92]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$status</font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[93]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[4]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[94]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><img src=\"$vuser[5]\" alt=\"$vuser[5]\" /></font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[95]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[7]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" class=\"height\">
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[96]</font></td>
      <td width=\"70%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$vuser[8]</font></td>
   </tr>
   <tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"1000%\" colspan=\"2\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"> <img src=\"icon/msn.gif\" alt=\"$vuser[12]\" /> <img src=\"icon/aim.gif\" alt=\"$vuser[10]\" /> <img src=\"icon/icq.gif\" alt=\"$vuser[11]\" /></font></td>
   </tr>
</table>");
table_footer();


include("footer.php");

?>
