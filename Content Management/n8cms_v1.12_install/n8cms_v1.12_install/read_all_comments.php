<?
//session_start();  // Start Session
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
$title=$dir."/ ~comments";}
	else{$title=$dir."/".$title."/ ~comments";
}
$auth_id=$pgdata['auth_id'];

$uid= "SELECT userid, first_name, last_name FROM  users WHERE userid=".$auth_id;
$uid_result= mysql_query($uid) or die (mysql_error());
$userid= mysql_fetch_array($uid_result);

pageheader();


$title=$config[site_name]."/".$dir."/".$title;
?>
<link rel="stylesheet" href="<?=$config[css];?>" type="text/css">
</head>

<body OnLoad="window.focus();">

<table border=0 class="dir_nav" width=100%>
	<tr><td valign=top>
<?
$tables= mysql_list_tables(DB_NAME);
$tab=0;
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
		if ( ($tbl_name=="users")||($tbl_name=="comment")||($tbl_name=="banned_ip") ){echo '';}
		else {echo "[<a href=index.php?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]\n";}
	$i++;
	}
	?>
</td></tr></table>
<!---
<table border=0 class="dir_nav_com" width=100%>
	<tr><td valign=top ALIGN=center>read commments:
<?
$tables= mysql_list_tables(DB_NAME);
$tab=0;
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
		if ( ($tbl_name=="users")||($tbl_name=="comment")){echo '';}
		else {echo "[<a href=?dir=".$tbl_name." class='dir_nav_com' tabindex=5".$tab++.">".$tbl_name."</a>]\n";}
	$i++;
	}
	?>
</td></tr></table>
--->
<table border=0 width="100%">
	<tr><td valign=top width=10% nowrap class="toc">
<a class="content_bottom">&nbsp;</a><br>
<!---<?
	$toc ="SELECT pg_title,page_id FROM ".DB_TABLE." WHERE isactive=1 ORDER BY page_id ASC";
	$toc_result =mysql_query($toc);
	$tab=1;
	while($row=mysql_fetch_array($toc_result))
	{echo"<a class=toc href=?dir=".$dir."&page_id=$row[page_id] tabindex=".$tab++.">$row[pg_title]</a><br>\n";
	echo"<a class=read_com href=index.php?dir=".$dir."&page_id=$row[page_id] tabindex=".$tab++.">Read Original</a><br>\n";
	}

?>--->
	</td>
	<td valign=top width=100%>
		<table border=0 width=100% cellpadding=0 cellspacing=0>
	<tr><td>&nbsp;
		<!---page_head,posted/posted by--->
&nbsp;</td>	<td valign=top nowrap rowspan=3 WIDTH=20%>
<!---Right Colum info--->
&nbsp;
			</td></tr>
			<tr><td  class="content">
<!---page info---->
	<?
$comm_query="SELECT * FROM comment ORDER BY com_id DESC";
$comm_result= mysql_query($comm_query) or die(mysql_error());
$comm_num=mysql_num_rows($comm_result);
while($com=mysql_fetch_array($comm_result)){
?>
<table border=0 width=100% cellpadding=0 cellspacing=0 align='center' class='pg_head'>
	<tr><td colspan=2 class='posted'><a href='index.php?dir=<?=$com[dir];?>&page_id=<?=$com[page_id]?>' class="int_link">Read page</a> this comment originated from</a>
	<tr><td class='posted'>posted:<?=$com[datetime];?></td>
	<td align=right class='posted'>by: <?=$com[sender_name];?></td></tr>
<tr><td colspan=2 width=100%><?=$com[comment_text];?></td></tr>
</table>
<br>	
<?
}
?>	
<!--- end page info---->

			</td>
			</tr>
			<tr><td class="content_bottom" align="center">
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=0,width=300,height=300,left = 250,top = 200');");
}
// End -->
</script>


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
