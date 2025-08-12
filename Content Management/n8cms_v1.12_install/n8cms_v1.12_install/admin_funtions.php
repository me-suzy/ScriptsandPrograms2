<?
session_start();
require ('_.php');
require ('functions.php');

//echo "usr_lvl=".$usr_lvl."<br>";
// get page vars for all querys
// this includes id vars 
$dir=$_GET['dir'];
	if (!$dir){$dir="home";}
	else{$dir="$dir";}
	
define("DB_TABLE","$dir");			//db_table
if (!$page_id){
	$page_id=1;}
	
$pg_data_query="SELECT * FROM ".DB_TABLE." WHERE page_id=".$page_id;
$pg_data_result=mysql_query($pg_data_query);
$pgdata=mysql_fetch_array($pg_data_result);
$pg_title=$pgdata[pg_title];
$pg_content=$pgdata[content];
//echo$pg_data_query."<br>";
//echo "pg_title= ".$pg_title."<br>";
$title=$pgdata['pg_title'];
if ($title=="index"){
$title=$config[site_name]."/".$dir;}
	else{$title=$config[site_name]."/".$dir."/".$title;
}
?><link rel="stylesheet" href="<?=$config[css];?>" type="text/css">
<?$auth_id=$pgdata['auth_id'];
pageheader();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
		<title>Admin ~ <?=$title?></title>

<?
//admin headers, replace this with a better function
if (!$usr_lvl){
		echo "<h2>Access Denied!</h2>";
	 exit();
		}else {//echo "you user level is ".$usr_lvl;
			if	($usr_lvl < 1) {include ('notyet.php'); exit();}
			if	($usr_lvl ==1){editorpageheader();}
			if	($usr_lvl ==2){Adminpageheader();}
			if	($usr_lvl ==3){Mastpageheader();}
			if 	($usr_lvl ==4){dietypageheader();}
			 echo" <a class=nav_links href=index.php?dir=".$dir."&page_id=".$page_id." target=_new>Preview</a> |<br> ";
		}
?>	
</head>
<body>
<?	
echo "<b>Editing ".DB_TABLE."</b><br>";
if ( ($dir =="comment")||($dir=="banned_ip") ){echo'';}
else{
	if(($usr_lvl == 3)||($usr_lvl==4)) {
							echo"<a class=\"admin_toc\" href='editpg.php?dir=".$dir."&page_id=1'>Edit ".DB_TABLE." index</a> - <a class=\"admin_toc\" href='editpg.php?dir=".$dir."&page_id=1&w=1'>Wysiwyg</a><br> ";}
	if ($userid==$pg_data[auth_id]){
	echo"<a href='editpg.php?dir=".$dir."&page_id=1'>Edit ".DB_TABLE." index</a><br> ";}
}
?>
<table border=0 class="dir_nav" width=100%>
	<tr><td valign=top ALIGN=left>

<?
//start TOC
$tables= mysql_list_tables(DB_NAME);
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
if ($config[comments]==0){
						if ( ($tbl_name=="comment")||($tbl_name=="users")||($tbl_name=="banned_ip") ){echo '';}
						else {echo "<font class=dir_nav>[<a href=?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]</font>\n";}
						$i++;
						}
	if ($config[comments]==1){
							if ( ($tbl_name=="users") ||($tbl_name=="banned_ip") ){echo'';}	
							else {echo "<font class=dir_nav>[<a href=?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]</font>\n";}
	$i++;
	}
	}
	?>


<?
/*//start TOC
$tables= mysql_list_tables(DB_NAME);
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
		if ($tbl_name=="users"){echo '';}
		else {echo "<font class=dir_nav>[<a href=?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]</font>\n";}
	$i++;
	}
*/	?>
</td></tr></table>
<table border=0>
	<tr><td valign=top width=10% nowrap class="toc">
	<?

$toc_query="SELECT pg_title, auth_id, page_id FROM ".DB_TABLE." WHERE isactive=1 ORDER BY page_id ASC";
$toc_result=mysql_query($toc_query);
$toc_pgnum=mysql_num_rows($toc_result);
/*echo $toc_query."<br>";*/
/*echo"there are ".$toc_pgnum." items listed, here are the titles<br>";*/
//loop throught the titles
while ($toc_array=mysql_fetch_array($toc_result))
{

$pg_id=$toc_array['page_id'];
$pg_title=$toc_array['pg_title'];
$auth_id=$toc_array['auth_id'];
$userid=$_SESSION['userid'];
//make diety admin	
	if ($_SESSION[user_level] == 4){echo"<a class=admin_toc href=delete.php?dir=".$dir."&page_id=".$pg_id.">[delete]</a>\n<a class=admin_toc href=editpg.php?dir=".$dir."&page_id=".$pg_id.">HTML</a> <a href=editpg.php?dir=".$dir."&page_id=".$pg_id."&w=1 class='admin_toc' >Wysiwyg</a> <a class='toc' href=?dir=".$dir."&page_id=".$pg_id."> ".$pg_title."</a><br>\n";}
	else{
				if ($userid == $auth_id){echo"<a class=admin_toc href=delete.php?dir=".$dir."&page_id=".$pg_id.">[delete]</a>\n<a class=admin_toc href=editpg.php?dir=".$dir."&page_id=".$pg_id.">[edit]</a>\n<a class='toc' href=?page_id=".$pg_id."> ".$pg_title."</a><br>\n";}
				else{
				echo"<a class=toc href=?dir=".$dir."&page_id=".$pg_id.">".$toc_array[pg_title]."</a><br> \n"; 
					}
				}
}
//echo"<a class=nav_links href=exit.php>Login</a><br>";

//end TOC
?>
</td><td valign=top width=80%>

<table border=0 width=100% cellpadding=0 cellspacing=0 >
	<tr><td colspan=2>
		<!---page_head,posted/posted by--->

<?
//format content display
if ($dir =="comment"){include('edit_comments.php');}
else{
$user_query="SELECT userid, first_name, last_name FROM  users WHERE userid=".$auth_id;
//echo $user_query;
$user_result=mysql_query($user_query);
$user_array=mysql_fetch_array($user_result);
$user_id=$user_array['userid'];
$user_f_name=$user_array['first_name'];
$user_l_name=$user_array['last_name'];
echo"<table border=0 class=nav_header width=100%><tr><td><h2>Editing ".$pgdata[pg_title]."</h2>Posted: ".$pgdata[rec_crt]."</td>";
echo"<td align=right valign=bottom>Owner: <a class=nav_links href=mailto.php?userid=".$user_id.">".$user_f_name." ".$user_l_name."</a> </td></tr>";
echo"</table></td></tr><tr><td colspan=2 class=content>";
echo $pgdata[content];
}
?>
	</td></tr>
	<tr><td class="content_bottom" colspan=2><br><br><br><?disclaimer()?></td>
	</table>
	</td></tr></table>

</body>
</html>
