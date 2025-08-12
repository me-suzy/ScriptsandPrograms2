<?php

$where = "<a href=\"index.php\">Home</a> > Administration";

if (isset($_GET["auth"])){
$where = "<a href=\"index.php\">Home</a> > <a href=\"admin.php\">Administration</a> > ".$_GET["auth"]."";
}; //end isset $auth

include("header.php");

if ($user_power == "1"){

if (!isset($_GET["auth"])){
//is an admin
table_header("Administration");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
   <tr>
      <td background=\"$skins/table/title_bg.gif\" colspan=\"3\" width=\"100%\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Administrative Options</b></font></td>
   </tr>
   <tr>
      <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"33%\" valign=\"top\" align=\"center\">
        <font size=\"$fsmall\" face=\"$fface\">
        <a href=\"admin.php?auth=config\"><img src=\"icon/config.gif\" alt=\"Forum Configuration\" border=\"0\" /><br />Forum Configuration</a><br /><br />
        <a href=\"admin.php?auth=prune\"><img src=\"icon/prune.gif\" alt=\"Prune Forum\" border=\"0\" /><br />Prune Forum</a><br /><br />
        <a href=\"admin.php?auth=password\"><img src=\"icon/password.gif\" alt=\"Password Forum\" border=\"0\" /><br />Password Forums</a><br /><br />
       <a href=\"admin.php?auth=who\"><img src=\"icon/who.gif\" alt=\"Whos online\" border=\"0\" /><br />Whos Online</a><br /><br />");
        // <a href=\"admin.php?auth=restore\">Restore Database</a><br />
        echo ("</font>
      </td>
      <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"33%\" valign=\"top\" align=\"center\">
        <font size=\"$fsmall\" face=\"$fface\">
        <a href=\"admin.php?auth=backup\"><img src=\"icon/db.gif\" alt=\"BackUP Database\" border=\"0\" /><br />BackUP Database</a><br /><br />
        <a href=\"admin.php?auth=export\"><img src=\"icon/export.gif\" alt=\"Export DB\" border=\"0\" /><br />Export DB</a><br /><br />
        <a href=\"admin.php?auth=import\"><img src=\"icon/import.gif\" alt=\"Import DB\" border=\"0\" /><br />Import DB</a><br /><br />
        <a href=\"admin.php?auth=alerts\"><img src=\"icon/button.jpg\" alt=\"Alerts\" border=\"0\" /><br />Send Alerts</a><br /><br />");
       // <a href=\"admin.php?auth=restore\">Restore Database</a><br />
        echo ("</font>
      </td>
      <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"33%\" valign=\"top\" align=\"center\">
        <font size=\"$fsmall\" face=\"$fface\">
        <a href=\"admin.php?auth=cats\"><img src=\"icon/cats.gif\" alt=\"Catagories\" border=\"0\" /><br />Manage Catagories</a><br /><br />
        <a href=\"admin.php?auth=forums\"><img src=\"icon/forums.gif\" alt=\"Forums\" border=\"0\" /><br />Manage Forums</a><br /><br />
        <a href=\"admin.php?auth=members\"><img src=\"icon/members.gif\" alt=\"\" border=\"0\" /><br />Manage Members</a><br /><br />
        <a href=\"admin.php?auth=filter\"><img src=\"icon/filter.gif\" alt=\"Word Filter\" border=\"0\" /><br />Manage String Filter</a><br /><br />
        <a href=\"admin.php?auth=poststatus\"><img src=\"icon/names.gif\" alt=\"Global Names\" border=\"0\" /><br />Manage Post count Names</a><br />
        </font>
      </td>
   </tr>
</table>");
table_footer();
}; //end isset $auth


if (isset($_GET["auth"])){
  if (file_exists("admin_".$_GET["auth"].".php")){
    include("admin_".$_GET["auth"].".php");
  } else {
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Requested authentication not valid!.<br /></font></div>\r\n");
  };
}; //end isset $auth

} else {

//not an admin
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Error: You do not have administration privlidges!.<br /></font></div>\r\n");

}; //end if

include("footer.php");

?>
