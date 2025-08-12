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
/* Please let us know what you think of this script			*/
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/* 									*/
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
include("header.php");
include("config.php");
require("VarCWC02.php");

if ($nuke_admin==on) {
GraphicAdmin();
}

if ($submit):
$sql = "UPDATE CWC_shopstopcat SET " .
       "cat='$custcat', " .
       "catdes='$custdes' " .
       "WHERE cid=$id";
if (mysql_query($sql)) {
OpenTable();
   echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
   Echo ("<BR><center>Category has been updated.</center>");
   $page = "admin.php?op=shopcat";
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
    echo("$sql");
    echo("<P>Error updating details: " .
    mysql_error() . "</P>");
}

else:
// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstopcat where cid =$id");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}

OpenTable();
echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
Echo ("<center><a href='admin.php?op=shopstop'>Add Product</A> | <a href='admin.php?op=shoplst'>Product List</a> | <b>Categories</b></center>");
CloseTable();

Echo ("<BR>");

OpenTable();
Echo ("<P><B><li>Deleting any category will DELETE all products under that category as well.</li></b><br>");

// Display the text
while ( $row = mysql_fetch_array($result) ) {
$icat=$row["cid"];
$ccat=$row["cat"];
$cdes=$row["catdes"];
?>
<form name="form1" method="post" action="">
  <p>Category: 
    <input type="text" name="custcat" size="20" maxlength="20" value="<? Echo($ccat); ?>">
    <br>
    Description: 
    <input type="text" name="custdes" maxlength="55" size="55" value="<? Echo($cdes); ?>">
    <input type="submit" name="submit" value="Update">
    <input type="hidden" name="id" value="<? Echo($icat); ?>">
  
</form>
  
<?php
}
CloseTable();

Echo ("<br>");
?>
<?
CloseTable();
Echo ("<br>");
endif;
include ("footer.php");
?>
