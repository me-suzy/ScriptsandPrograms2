<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=300,height=300,left = 250,top = 200');");
}
// End -->
</script>
	<?
$comm_query="SELECT * FROM comment ORDER BY com_id";
$comm_result= mysql_query($comm_query) or die(mysql_error());
$comm_num=mysql_num_rows($comm_result);
while($com=mysql_fetch_array($comm_result)){
?>

<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'>
	<tr><td class='posted'>
	<a href="javascript:popUp('edit_comment.php?dir=<?=$com[dir]?>&page_id=<?=$com[page_id]?>&com_id=<?=$com[com_id]?>')" style="font-weight:600;color:maroon;">Edit</a>,
	<a href="javascript:popUp('delete_comment.php?dir=<?=$com[dir]?>&page_id=<?=$com[page_id]?>&com_id=<?=$com[com_id]?>')" style="font-weight:600;color:maroon;">Delete comment</a>
	or <a href="javascript:popUp('ban_ip.php?ip=<?=$com[comment_ip]?>&dir=<?=$com[dir]?>&page_id=<?=$com[page_id]?>&com_id=<?=$com[com_id]?>')" style="font-weight:600;color:maroon;")>Ban IP</a>&nbsp;posted:<?=$com[datetime];?>
	
	</td>
	<td align=right class='posted'>by: <?=$com[sender_name];?></td></tr>
<tr><td colspan=2 width=100%><?=$com[comment_text];?></td></tr>
</table>
<br>	
<?
}
?>	
