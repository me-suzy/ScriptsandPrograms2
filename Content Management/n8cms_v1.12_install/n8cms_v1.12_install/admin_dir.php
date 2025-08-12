<?session_start();
require ('_.php');
require ('functions.php');
$delete=$_GET[delete];
$user_level=$_SESSION[user_level];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title><?="Admin ~ ".$config[site_name]."/drop dir" ?>/</title>
</head>

<body>
<?
//checks user level for authorization, redundant security but worth it
if (!$delete){
if ($user_level > 2){$auth=1;}
else{$auth=0;}
if ((!$auth)||($auth =0)){echo "<h3>ACCESS DENIED! insufficiant user level</h3> your user level =".$user_level;
exit(); }
if (($auth=='1')||(!$delete)){echo "this script deletes an entire directory, there is no undo, if you delete it, it's really gone<br>
the following directories are not shown,<br><b>Users</b><br><b>comment</b><br><b>home</b><br><br>these are the directories avalable to delete<br>";

//start TOC
$tables= mysql_list_tables(DB_NAME);
$i=0;
while ($i < mysql_num_rows($tables)){
	$tbl_name=mysql_tablename($tables,$i);
		if (($tbl_name=="users")||($tbl_name=="comment")||($tbl_name=="home")){echo '';}
		else {echo "delete <a href=admin_dir.php?delete=1&tbl_name=".$tbl_name.">".$tbl_name."</a><br></font>\n";}
	$i++;
	}
}
}
if ($delete=='1'){
$tbl_name=$_GET[tbl_name];
echo "Do you really want to drop ".$tbl_name."?<br><a href=?delete=2&tbl_name=".$tbl_name.">Heck Yeah!</a> | <a href=javascript:history.go(-1); >No!</a>";
}
if ($delete =='2'){

$tbl_name=$_GET[tbl_name];
$doit= 'DROP TABLE `'.$tbl_name.'` '; 
echo $doit;
mysql_query($doit) or exit (mysql_error());
echo "success <a href=admin_funtions.php>Admin Home</a>";
}
	?>


</body>
</html>
