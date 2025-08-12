<?
include_once('../includes/db_connect.php');
check_admin();

if (isset($HTTP_GET_VARS['id'])) {
	$id=$HTTP_GET_VARS['id'];
}

if (isset($HTTP_POST_VARS['submit'])) {

	$submit=$HTTP_POST_VARS['submit'];

	switch ($submit) {
		case 'Save Article':
			$title   =   mysql_escape_string(stripslashes($HTTP_POST_VARS['title']));
			$body    =   mysql_escape_string(stripslashes($HTTP_POST_VARS['body']));

			if (isset($id) && $id != "") {
				$query="UPDATE dir_articles SET body='$body', title='$title',enterdate=now() WHERE articles_id = $id";
				mysql_query($query,$link) or die(mysql_error());
			} else {
                $query="INSERT INTO dir_articles SET body='$body', title='$title',enterdate=now()";
				mysql_query($query,$link) or die(mysql_error());
			}
			header("Location: articles.php?id=$id&PHPSESSID=".session_id());
			break;
		case 'Articles list':
			header("Location: articles.php?PHPSESSID=".session_id());
			break;
	}
}

if (isset($id)) {

	$result=mysql_query("SELECT * FROM dir_articles WHERE articles_id=$id",$link);
	$sql_array=mysql_fetch_object($result);

	$title=$sql_array->title;
	$body=$sql_array->body;
}
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
?>

<?php include('../includes/admin_header.php'); ?>
        <table width="100%"  border="0" cellspacing="3" cellpadding="3" align="center">
          <tr>
            <td align="center">
			<form action="articles_operation.php?id=<?php echo $id ?>&PHPSESSID=<?php echo session_id() ?>" method="post" name="frmBanners">
			<div align="right"><input type='submit' value='Save Article' name='submit' class='button'>&nbsp;<input type='submit' value='Articles list' name='submit' class='button'></div>
			<input type="hidden" name="mode" value="">
    			<table width="85%"  border="0" cellspacing="6" cellpadding="3">
                  <tr align="left" valign="top">
                    <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr align="left" valign="top">
                    <td width="9%" align=right>Title </td>
                    <td width="91%">
                      <input type="text" name="title" value="<?=$title?>" size="40" maxlength="80"/>
                    </td>
                  </tr>
                  <tr align="left" valign="top">
                    <td width="9%" align=right>Text </td>
                    <td width="91%">
                      <textarea name="body" cols="50" rows="8"><?php echo $body ?></textarea>
                    </td>
              </tr>
			</table>
			</form></td>
          </tr>
        </table>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
