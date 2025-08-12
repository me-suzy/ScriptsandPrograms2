<?php

$where = "<a href=\"index.php\">Home</a> &gt; Members";

include("header.php");

if (!isset($_GET["page"])){
  $_GET["page"] = "1";
};

echo ("<style type=\"text/css\">
.pagenum A { BORDER-RIGHT: #000000 1px solid; PADDING-RIGHT: 4px; BORDER-TOP: #000000 1px solid; PADDING-LEFT: 4px; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: #000000 1px solid; WIDTH: 1em; COLOR: #000000; PADDING-TOP: 1px; BORDER-BOTTOM: #000000 1px solid; BACKGROUND-COLOR: #CCCCCC; TEXT-DECORATION: none }
</style>");

$total = count($db->data["_DB"]["users"]);

$pp = "15";
$start = $pp * $_GET["page"] - $pp + 2;
$finish = $pp * $_GET["page"] + 2;

$pages = $total / $pp;
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" align=\"center\">
<tr bgcolor=\"$tbackground1\">
<td>");

for ($p = 1; $p < $pages + 1; $p++){
echo ("      <span class=\"pagenum\"><font size=\"$fmedium\" color=\"$fsubtitle\" face=\"$fface\"><b><a href=\"members.php?page=$p\">$p</a></b></font></span>   ");
}; //end for
echo ("</td></tr></table><br />");


table_header("View Members");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr class=\"background\">
      <td width=\"25%\" colspan=\"1\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Username</b></font></td>
      <td width=\"15%\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Status</b></font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Posts</b></font></td>
      <td width=\"20%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Alias</b></font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Website</b></font></td>
   </tr>");


for ($members = $start; $members < $finish; $members++){

if (@$db->data["_DB"]["users"]["$members"][3] == "1"){
  $status = "[ <font color=\"$cadmin\"><i>Administrator</i></font> ]";
} else {
  $status = "<font color=\"$cmember\">Member</font>";
}; //end if
   if (isset($db->data["_DB"]["users"]["$members"])){
   echo ("<tr bgcolor=\"$tbackground2\" class=\"height\">
      <td width=\"25%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"viewprofile.php?uid=$members\">".$db->data["_DB"]["users"]["$members"][0]."</a></font></td>
      <td width=\"15%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$status</font></td>
      <td width=\"10%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["users"]["$members"][4]."</font></td>
      <td width=\"20%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$db->data["_DB"]["users"]["$members"][6]."</font></td>
      <td width=\"30%\" colspan=\"1\" align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><a href=\"".$db->data["_DB"]["users"]["$members"][7]."\" target=\"_blank\">".$db->data["_DB"]["users"]["$members"][7]."</a></font></td>
   </tr>");
   };
}; //end for $members
   
   
   
echo ("</table>");
table_footer();
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" align=\"center\">
<tr bgcolor=\"$tbackground1\">
<td>");

for ($p = 1; $p < $pages + 1; $p++){
echo ("      <span class=\"pagenum\"><font size=\"$fmedium\" color=\"$fsubtitle\" face=\"$fface\"><b><a href=\"members.php?page=$p\">$p</a></b></font></span>   ");
}; //end for
echo ("</td></tr></table><br />");


include("footer.php");

?>
