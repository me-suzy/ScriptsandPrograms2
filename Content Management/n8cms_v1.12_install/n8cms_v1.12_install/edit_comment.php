<?
session_start();
require ('_.php');
require ('functions.php');
if (!$usr_lvl){

		header("Location: exit.php");
		}else {
	pageheader();
}
$com_id=$_GET['com_id'];
$insert=$_POST['insert'];

if (!$insert){
$comm_query="SELECT * FROM comment WHERE dir='".$dir."' AND page_id='".$page_id."' AND com_id='".$com_id."'";
$comm_result= mysql_query($comm_query) or die(mysql_error());
$comm_num=mysql_num_rows($comm_result);
$com=mysql_fetch_array($comm_result);

?>
<form action="edit_comment.php" Method="POST">
<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'>
<tr><td class='posted'>posted:<?=$com[datetime];?></td>
<td align=right class='posted'>by: <?=$com[sender_name];?></td></tr>
<tr><td colspan=2>
<textarea name="comment_text" cols=30 rows=10><?=$com['comment_text']?></textarea></td></tr>
<input type="hidden" name="insert" value=1>
<input type="hidden" name="dir" value="<?=$dir?>">
<input type="hidden" name="page_id" value="<?=$page_id?>">
<input type="hidden" name="com_id" value="<?=$com_id?>">
<tr><td align=center ><input class='button' type="submit" name="submit" value="Send"></td><td><input class='button' type="reset" name="reset" value="Clear All"> 
</td></tr></table>
</form>
<? }
else
{
$insert=$_POST['insert'];
$dir=$_POST['dir'];
$page_id=$_POST['page_id'];
$com_id=$_POST['com_id']; 
$comment_text=addslashes($_POST['comment_text']);
$ins_query="UPDATE comment SET comment_text='".(nl2br($comment_text))."' WHERE dir='".$dir."' AND page_id='".$page_id."' AND com_id='".$com_id."' LIMIT 1";
		mysql_query(stripslashes($ins_query)) or die (mysql_error());

echo"<script>window.opener.location.replace('admin_funtions.php?dir=comment&page_id=".$page_id."')</script>";
	echo "DONE <script>setTimeout (window.close(),900000)</SCRIPT>";

}
?>
