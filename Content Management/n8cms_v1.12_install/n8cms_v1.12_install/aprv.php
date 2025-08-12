<?
session_start();
include('_.php');
include('functions.php');
// get page vars for all querys
// this includes id vars 
$dir=$_GET['dir'];
	if (!$dir){$dir="home";}
	else{$dir="$dir";}
	
define("DB_TABLE","$dir");			//db_table
if (!$page_id){
	$page_id=1;}
	
/*$pg_data_query="SELECT pg_title, rec_crt, content FROM ".DB_TABLE." WHERE page_id=".$page_id;
$pg_data_result=mysql_query($pg_data_query);
$pgdata=mysql_fetch_array($pg_data_result);
*/$pg_content=$pgdata[content];
$title="Pages waiting for approval";
if ($title=="index"){
$title=$config[site_name]."/".$dir;}
	else{$title=$config[site_name]."/".$dir."/".$title;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title><?=$title?></title>
</head>

<body>

<?
if (!$usr_lvl){
		echo "<H2>ACCESS DENIED!</H2>"; exit();
		}else {//echo "you user level is ".$usr_lvl;
			if	($usr_lvl < 1) {include ('notyet.php'); exit();}
			if	($usr_lvl ==1){editorpageheader();}
			if	($usr_lvl ==2){Adminpageheader();}
			if	($usr_lvl ==3){Mastpageheader();}
			if 	($usr_lvl ==4){dietypageheader();}
			 echo" <a class=nav_links href=index.php?dir=".$dir."&page_id=".$page_id." target=_new>Preview</a> |<br> ";
		}
echo "<br>current directory is <b>".DB_TABLE."</b><br>";
?><table border=0 class="dir_nav" width=100%>
	<tr><td valign=top ALIGN=center>
<?//start TOC
$tables= mysql_list_tables(DB_NAME);
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
	//lists all tables but 'users'
		if ((!$tbl_name)||($tbl_name=='users')||($tbl_name=='comment') ){echo '';}
		else {echo "<font class=dir_nav>[<a href=?dir=".$tbl_name." class='dir_nav' tabindex=5".$tab++.">".$tbl_name."</a>]</font>\n";}
	$i++;
	}
?></td></tr></table>
<?

$pg_data_query="SELECT pg_title, rec_crt, content FROM ".DB_TABLE." WHERE isactive=0";
//echo "<br>".$pg_data_query;
$pg_data_result=mysql_query($pg_data_query);
$pgdata=mysql_fetch_array($pg_data_result);
$pg_title=$pgdata[pg_title];
$pg_content=$pgdata[content];
$aprv_query="SELECT * FROM ".DB_TABLE." WHERE isactive=0";
$aprv_result=mysql_query($aprv_query);
$aprv_array=mysql_fetch_array($aprv_result);
$aprv_num=mysql_num_rows($aprv_result);

	if ($_GET[in]==1)
	{
		$in_query="UPDATE ".DB_TABLE." SET isactive=".$_GET[in]."  WHERE page_id=".$_GET[aprv]." LIMIT 1 ";
		$in_result=mysql_query($in_query) or die (mysql_error());
		echo "<script>document.location.replace('aprv.php?dir=".DB_TABLE."');</script>";		
	}		

	
	if (!$_GET[in]){	echo "<br><b>there are ".($aprv_num -1) ." pages wating for approval</b>";
			echo "<br>list pages <br>";
		while($aprv_array=mysql_fetch_array($aprv_result)){
		echo "\n<table border=1><tr bgcolor=#cacaca>\n<td>page_id= ".$aprv_array[page_id];
		echo "</td><td><h3>".$aprv_array[pg_title]."</h3></td></tr>\n";
		echo "<tr><td><a href=?dir=".$dir."&in=1&aprv=".$aprv_array[page_id].">Publish</a></td><td>".$aprv_array[content]."</td></tr></table>\n";
			}
		
	}


pagefooter();

?>