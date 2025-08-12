<?php
# choose a banner

include_once('../includes/db_connect.php');
?>
<?php
/*****************************************************
* &copy; copyright 1999 - 2003 Interactive Arts Ltd.
*
* All materials and software are copyrighted by Interactive Arts Ltd.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
check_admin();

$mode=$HTTP_GET_VARS['mode'];
if (isset($mode)) {
	switch ($HTTP_GET_VARS['submit']) {
		case 'Editbanner':
			header("Location: banner_maint.php?id=$mode&PHPSESSID=".session_id());
			break;
		case 'AddBanner':
			header("Location: banner_maint.php?PHPSESSID=".session_id());
			break;
		case 'Deletebanner':
			$result=mysql_query("DELETE FROM dir_banners WHERE ban_id=$mode",$link) or die(mysql_error());
			break;
		case 'Menu':
			header("Location: menu.php?PHPSESSID=".session_id());
			break;
		case 'Logoff':
			session_unset();
			session_destroy();
			header("Location: admin.php");
			exit; break;
	}
}

$result=mysql_query("SELECT * FROM dir_banners",$link) or die(mysql_error());
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");

?>

<?php include('../includes/admin_header.php'); ?>
        <table width="100%"  border="0" cellspacing="3" cellpadding="3" align="center">
          <tr>
            <td align="center">
			<form action="banners.php?PHPSESSID=<?php echo session_id() ?>" method="get" name="frmBanners">
			<input type="hidden" name="submit" value="AddBanner">
			<input type="hidden" name="mode" value="1">
			<div align="right"><input type='submit' value='Add Banner' name='add' class='button'></div>
			</form>
		<?php
			while($sql_banners=mysql_fetch_object($result)) {
				print("<div style='background-color:#DBEDFF; border-top: 1px dotted #cccccc; border-bottom: 1px dotted #cccccc; '><p>".stripslashes($sql_banners->ban_text)."</p>");
				print("<p><input type='button' name='submit' value='Edit Banner' class='button' onClick='document.location=\"$CONST_LINK_ROOT/admin/banners.php?mode=$sql_banners->ban_id&submit=Editbanner\"'>&nbsp;<input type='button' value='Delete banner' name='submit' class='button' onClick='document.location=\"$CONST_LINK_ROOT/admin/banners.php?mode=$sql_banners->ban_id&submit=Deletebanner\"'></p></div>");
			}
			mysql_close($link);
		?>
		</td>
          </tr>
        </table>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
