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
/* Please let us know what you think of this script                        */
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/*                                                                         */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
include("header.php");
include("config.php");
require("VarCWC02.php");

if ($nuke_admin==on) {
GraphicAdmin();
}


if ($delete == "true") {
$sql1 ="DELETE from CWC_shopstop where catid=$id";
mysql_query($sql1) or die("Error: ".mysql_error());

$sql="DELETE From CWC_shopstopcat where cid=$id";



OpenTable();
if (mysql_query($sql)) {
   echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
   Echo ("<BR><center>Category has been deleted.</center>");
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
include ("footer.php");
} else {
   echo("$sql");
   echo("<P>Error updating the category details: " .
   mysql_error() . "</P>");
}
exit();
}


if ($submit):

$sql="INSERT INTO CWC_shopstopcat VALUES('','$ccat','$cdes')";
$result=mysql_query($sql,$dbi);

OpenTable();
echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
Echo ("<BR><center>Category has been added.</center>");
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

else:

// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstopcat GROUP BY cat");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}

// SYSTEM MENU
Echo ("<B><a href='admin.php?op=shopstopcfg'>Shop Stop System Settings</a></b>");

// MAIN MENU
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
?>
<br><?php echo($ccat); ?> <a href='admin.php?op=shopcat&id=<?php echo($icat); ?>&delete=true'>Delete</a> |
<a href='admin.php?op=editcat&id=<?php echo($icat); ?>'>Modify</a>
<?php
}
CloseTable();

Echo ("<br>");

OpenTable();
?>
<form action="admin.php?op=shopcat" method="post">
<b>Add Category</b><br>
  Category:
  <input type="text" name="ccat" maxlength="20" size="20">
  <br>
  Description:
  <input type="text" name="cdes" maxlength="55" size="55">
  <br>
      <input type="submit" name="submit" value="Add">
</form>
<?
CloseTable();
Echo ("<br>");

endif;

include ("footer.php");
?>