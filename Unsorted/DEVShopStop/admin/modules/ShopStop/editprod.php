<HTML>
<HEAD>
<TITLE>Admin Shop Stop Shopping Center</TITLE>
<HEAD>
<?php
/************************************************************************/
/*                                                                      */
/* Copyright (c) 2001-2002 by CrossWalkCentral                          */
/* http://www.crosswalkcentral.net                                      */
/*                                                                      */
/* CrossWalkCentral                                                     */
/* You Web Hosting Community!                                           */
/*                                                                      */
/* Let us customize this script for you.                                */
/*                                                                      */
/* Please let us know what you think of this script                     */
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/*                                                                      */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
include("header.php");
include("config.php");
require("VarCWC02.php");

if ($nuke_admin==on) {
GraphicAdmin();
}

if ($delete == "true") {
$sql="DELETE From CWC_shopstop where pid=$id";

OpenTable();
if (mysql_query($sql)) {
   echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
   Echo ("<BR><center>Product has been deleted.</center>");
   $page = "/admin.php?op=shoplst";
   Echo ("<BR><center>Click <a href=$page>here</a> or wait 3 seconds to go back to Admin Center</center>");
CloseTable();
?>

<script language="Javascript" type="text/javascript">
    <!--
    function gotoThread() {
    window.location.href="<?php print $page ?>";
    }
    window.setTimeout("gotoThread()", 3000);
    //-->
</script>
<?
include ("footer.php");
} else {
   echo("$sql");
   echo("<P>Error updating the $sname details: " .
   mysql_error() . "</P>");
}
exit();
}


if ($submit):
$sql1 = "UPDATE ".CWC."_shopstop SET name='$nsname', des='$description', price='$nsprice', catid='$nscat' WHERE pid=$id";


if (mysql_query($sql1)) {
OpenTable();
   echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
   Echo ("<BR><center>Product has been updated.</center>");
   $page = "admin.php?op=shoplst";
   Echo ("<BR><center>Click <a href=$page>here</a> or wait 3 seconds to go back to Admin Center</center>");
CloseTable();
?>
<script language="Javascript" type="text/javascript">
    <!--
    function gotoThread() {
    window.location.href="<?php print $page ?>";
    }
    window.setTimeout("gotoThread()", 3000);
    //-->
</script>
<?
} else {
    echo("$sql1");
    echo("<P>Error updating details: " .
    mysql_error() . "</P>");
}


else:

// SYSTEM MENU
Echo ("<B><a href='admin.php?op=shopstopcfg'>Shop Stop System Settings</a></b>");

// MAIN MENU
OpenTable();
Echo ("<h4><center>Shop Stop Shopping Center</center></h4>");
Echo ("<center><b>Add Product</b> | <a href='admin.php?op=shoplst'>Product List</a> | <a href='admin.php?op=shopcat'>Categories</a></center>");
CloseTable();
Echo ("<br>");
OpenTable();

// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstop WHERE $id = pid");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}

// Display the text
while ( $row = mysql_fetch_array($result) ) {
$sid=$row["pid"];
$sname=$row["name"];
$sdes=$row["des"];
$sprice=$row["price"];
$scatid=$row["catid"];

?>
<form name="form1" method="post" action="">
  <table width="95%" border="0" align="center">
    <tr>
      <td width="17%">Product Name:</td>
      <td width="83%">
        <input name="nsname" type="text" value="<? Echo($sname); ?>" size="40" maxlength="40">
      </td>
    </tr>
    <tr>
      <td width="17%" height="4">
        <p>Description:</p>
      </td>
      <td width="83%" height="4">
        <textarea name="description" cols="50" rows="5"><? Echo($sdes); ?></textarea>
      </td>
    </tr>
<tr>
<td width="17%" height="16">
<p>Category:</p>
<td>
        <select name="nscat">
<?

$sql = sql_query("select cid, cat from CWC_shopstopcat", $dbi);
      while(list($sid, $scat) = sql_fetch_row($sql, $dbi)) {
        if ($sid==$scatid) {
            $sel = "selected ";
        }
            echo "<option $sel value=\"$sid\">$scat</option>\n";
        $sel = "";
    }
?>
</select>
</td>
</tr>
    <tr>
      <td width="17%">Price:</td>
      <td width="83%">
        <input name="nsprice" type="text" value="<? Echo($sprice); ?>" size="10">
      </td>
    </tr>
    <tr>
      <td width="17%">&nbsp;</td>
      <td width="83%">
        <input type="hidden" name="id" value="<? Echo($id); ?>">
        <input type="submit" name="submit" value="Update">
      </td>
    </tr>
  </table>
  </form>

<p>
<?
}
CloseTable();
endif;
include("footer.php");
?>