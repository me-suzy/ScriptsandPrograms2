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

If ($submit):

$sql="INSERT INTO CWC_shopstop VALUES('','$sname','$sdes','$sprice','$scatergory')";
$result=mysql_query($sql,$dbi);

OpenTable();
echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
Echo ("<BR><center>Product has been added.</center>");
$page = "admin.php?op=shopstop";
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

// SYSTEM MENU
Echo ("<B><a href='admin.php?op=shopstopcfg'>Shop Stop System Settings</a></b>");

// MAIN MENU
OpenTable();
Echo ("<h4><center>Shop Stop Shopping Center</center></h4>");
Echo ("<center><b>Add Product</b> | <a href='admin.php?op=shoplst'>Product List</a> | <a href='admin.php?op=shopcat'>Categories</a></center>");
CloseTable();
Echo ("<br>");
OpenTable();

?>
<form name="productadd" method="post" action="">
  <table width="95%" border="0" align="center">
    <tr>
      <td width="17%">Product Name:</td>
      <td width="83%">
        <input type="text" name="sname" size="40" maxlength="40">
      </td>
    </tr>
    <tr>
      <td width="17%" height="4"> 
        <p>Description:</p>
      </td>
      <td width="83%" height="4"> 
        <textarea name="sdes" cols="50" rows="5"></textarea>
      </td>
    </tr>
<tr>
<td width="17%" height="16">
<p>Category:</p>
<td>
        <select name="scatergory">
<?

$sql = sql_query("select cid, cat from CWC_shopstopcat", $dbi);
      while(list($sid, $scat) = sql_fetch_row($sql, $dbi)) {
        if ($sid==$cat) {
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
        <input type="text" name="sprice" size="10">
      </td>
    </tr>
    <tr>
      <td width="17%">&nbsp;</td>
      <td width="83%">
        <input type="submit" name="submit" value="Add">
      </td>
    </tr>
  </table>
  </form>

<p>
<?
CloseTable();
endif;
include("footer.php");
?>
