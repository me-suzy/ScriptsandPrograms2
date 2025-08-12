<?php
# choose a banner

include_once('../includes/db_connect.php');

check_admin();

$mode=$HTTP_POST_VARS['mode'];
if (isset($mode)) {
	switch ($HTTP_POST_VARS['submit']) {
		case 'Edit article':
			header("Location: articles_operation.php?id=$mode&PHPSESSID=".session_id());
			break;
		case 'Add article':
			header("Location: articles_operation.php?PHPSESSID=".session_id());
			break;
		case 'Delete article':
			$result=mysql_query("DELETE FROM dir_articles WHERE articles_id=$mode",$link) or die(mysql_error());
			break;
	}
}

$result=mysql_query("SELECT * FROM dir_articles ORDER BY enterdate DESC",$link) or die(mysql_error());

include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
include('../includes/admin_header.php');
?>

        <table width="100%"  border="0" cellspacing="3" cellpadding="3" align="center">
          <tr>
            <td align="center">
			<form action="articles.php?PHPSESSID=<?php echo session_id() ?>" method="post" name="frmBanners">
			<div align="right"><input type='submit' value='Add article' name='submit' class='button'></div>
			<input type="hidden" name="mode" value="">
		<?php
			while($sql_article=mysql_fetch_object($result)) {
				print("<div><p>$sql_article->title</p>");
				print("<p><input type='submit' value='Edit article' name='submit' class='button' onClick='document.frmBanners.mode.value=$sql_article->articles_id'>&nbsp;<input type='submit' value='Delete article' name='submit' class='button' onClick='document.frmBanners.mode.value=$sql_article->articles_id'></p></div>");
			}
			mysql_close($link);
		?>
		</form></td>
          </tr>
        </table>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
