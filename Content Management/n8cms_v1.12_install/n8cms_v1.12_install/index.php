<?
//n8cms
require ('_.php');
require ('functions.php');

if (!$page_id){$page_id=1;}
		else {$page_id=$_GET[page_id];}
	  
$dir=$_GET['dir'];
	if (!$dir){$dir="home";}
	else{$dir="$dir";}
define("DB_TABLE","$dir");

$query = "SELECT * FROM ".DB_TABLE." WHERE page_id=".$page_id;
$result =mysql_query($query) or die (mysql_error());
$pgdata=mysql_fetch_array($result);

$title=$pgdata['pg_title'];
if ($title=="index"){
$title=$config[site_name]."/".$dir;}
	else{$title=$config[site_name]."/".$dir."/".$title;
}
$auth_id=$pgdata['auth_id'];

$uid= "SELECT userid, first_name, last_name FROM  users WHERE userid=".$auth_id;
$uid_result= mysql_query($uid) or die (mysql_error());
$userid= mysql_fetch_array($uid_result);

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>

<link rel="stylesheet" href="<?=$config[css];?>" type="text/css">
<title><?=$config[site_name]."/".$title;?></title>
</head>
<body OnLoad="window.focus();">
<!---dir nave table start--->
<table border=0 class="dir_nav" width=100%>
	<tr><td valign=top ALIGN=left>
<?
$tables= mysql_list_tables(DB_NAME);
$tab=0;
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	
		if ( ($tbl_name=="users")||($tbl_name=="comment")||($tbl_name=="banned_ip") ){echo '';}
		else {echo "[<a href=?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]\n";}
	$i++;
	}
	?>
</td></tr></table>
<!---end dir nav table --->
<!--- start toc nav --->
<table border=0 width=100%>
	<tr><td valign=top width=10% nowrap class="toc">
<?
	$toc ="SELECT pg_title,page_id FROM ".DB_TABLE." WHERE isactive=1 ORDER BY page_id ASC";
	$toc_result =mysql_query($toc);
	$tab=1;
	while($row=mysql_fetch_array($toc_result))
	{echo"<a class=toc href=?dir=".$dir."&page_id=$row[page_id] tabindex=".$tab++.">$row[pg_title]</a><br>\n";
	}

?>
	</td><td valign=top width=100%>
<!--- end TOC nav --->	
		<table border=0 width=100% cellpadding=0 cellspacing=0>
	<tr><td>&nbsp;
		<!---page_head,posted/posted by--->
	<?
		if ($page_id!=1){
					echo"<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'><tr><td class='posted'>posted:".$pgdata[rec_crt]."</td><td align=right valign=bottom class='posted'>by: <a class=id href=mailto.php?userid=".$userid[userid].">".$userid[first_name]." ".$userid[last_name]."</a> </td></tr></table>";
				}
			else{echo "<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'><tr><td class='posted'>Last Updated:<b>".$pgdata[rec_edit]."</b></td><td align=right valign=bottom class='posted'>by: <a class=id href=mailto.php?userid=".$userid[userid].">".$userid[first_name]." ".$userid[last_name]."</a> </td></tr></table>";
				}?>
</td>	<td valign=top nowrap rowspan=3 WIDTH=20%>
<!---Right Colum info if any--->
&nbsp;
			</td></tr>
			<tr><td  class="content">
<!---page info---->
	<?=$pgdata[content];?>
<!--- end page info---->

			</td>
			</tr>
			<tr><td class="content_bottom">
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=0,width=300,height=300,left = 250,top = 200');");
}
// End -->
</script>
<a name="comment"></a>

	<?  if ($config[comments]=='1'){
	$com =$_GET['com'];
	$comm_query="SELECT * FROM comment WHERE dir='".$dir."' AND page_id='".$page_id."' ORDER BY com_id DESC";
				$comm_result= mysql_query($comm_query) or die(mysql_error());
				$comm_num=mysql_num_rows($comm_result);
		if (!$com){
				if ($comm_num==0){	echo "Readers have added ".$comm_num." comments. Be the first to  <a href=javascript:popUp('addcomment.php?dir=".$dir."&page_id=".$page_id."')  class=int_link>add a comment</a>";}
			if ($comm_num > 0) { echo "<br>Readers have added <a href=\"?dir=".$dir."&page_id=".$page_id."&com=1#comment\" class=int_link>".$comm_num." comments</a>. <a href=javascript:popUp('addcomment.php?dir=".$dir."&page_id=".$page_id."')  class=int_link name=\"comment\">Add Comment</a>";}
			}
		else if ($com=1){echo"<br>Readers have added ".$comm_num." comments <a href=javascript:popUp('addcomment.php?dir=".$dir."&page_id=".$page_id."')  class=int_link name=\"comment\">Add Comment</a><br>";
				while($com=mysql_fetch_array($comm_result)){
	?>
	
<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'>
	<tr><td class='posted'>posted:<?=$com[datetime];?> from IP:<?=$com[comment_ip]?></td>
	<td align=right class='posted'>by: <?=$com[sender_name];?></td></tr>
<tr><td colspan=2 width=100%><?=$com[comment_text];?></td></tr>
</table>
<br>	
<?
}
echo "<a href=javascript:popUp('addcomment.php?dir=".$dir."&page_id=".$page_id."')  class=int_link>Add Comment</a>";	}

		// <a href='read_all_comments.php' class='int_link'>Read all Comments</a>";
	}
	?>
			
			<br><br><br></td></tr>
		</table>
</td></tr></table>
</td></tr></table>
<?
disclaimer();
pagefooter();

?>
</body>
</html>
