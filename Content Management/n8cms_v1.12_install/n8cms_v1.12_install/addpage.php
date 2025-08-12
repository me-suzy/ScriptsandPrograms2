<?
session_start();
require ('_.php');
require ('functions.php');

$usr_lvl = $_SESSION['user_level'];
$first_name = $_SESSION ['first_name'];
$last_name=$_SESSION['last_name'];
$userid=$_SESSION['userid'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<script language="javascript" type="text/javascript" src="jscript/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	theme : "simple",
	mode : "exact",
	elements : "content"
});
</script>


		<title>Admin ~ <?=$title?></title>
<?
?></head><?	
if (!$usr_lvl)
{
header("Location: exit.php");
}
else {
// echo "<b>".$username."</b> is logged in as User level: ".$usr_lvl;

	if	($usr_lvl ==1){editorpageheader();}
	if	($usr_lvl ==2){Adminpageheader();}
	if	($usr_lvl ==3){Mastpageheader();}
	if 	($usr_lvl ==4){dietypageheader();}

$dir=$_GET['dir'];
$query="SELECT email_address,userid FROM  users WHERE userid=".$userid;
$result =mysql_query($query) or die (mysql_error());
$ae = mysql_fetch_array($result);
//posted values
			$addto_dir=$_POST['dir'];
			$add=$_POST['add'];
			$page_id=$_POST['page_id'];
			$rec_crt=$_POST['rec_crt'];
			$rec_edit=$_POST['rec_edit'];
			$auth_id=$_POST['auth_id'];
			$isactive=$_POST['isactive'];
			$pg_title=$_POST['pg_title'];
			$hits=$_POST['hits'];
			$admin_lvl=$usr_lvl;
			$content=$_POST['content'];
			$day=$_POST[day];
			$month=$_POST[month];
			$year=$_POST[year];
			$rec_expire ="$day,$month,$year";
			
			$db_fld="page_id, rec_crt, rec_edit, auth_id,  isactive, pg_title, hits, admin_lvl, content, rec_expire";
			$db_val="'$page_id','$rec_crt','$rec_edit','$auth_id','$isactive','$pg_title','$hits','$usr_lvl','$content','$rec_expire'";



//	echo date("l dS of F Y h:i:s A");

	if ($_POST[content] != "" && $_POST[auth_id] != "") {
			$page_id=$_POST['page_id'];
			$pg_title = addslashes($_POST[pg_title]);
			$auth_id = addslashes($_POST[auth_id]);
			$content = addslashes($_POST[content]);
			$plaintext = ($_POST[nl2br] == "yes") ? 1 : 0;
			$isactive = intval($_POST[isactive]);
		$db_fld="page_id, rec_crt, rec_edit, auth_id,  isactive, pg_title, hits, admin_lvl, content, rec_expire";
		$db_val="'$page_id','$date','$rec_edit','$auth_id','$isactive','$pg_title','$hits','$usr_lvl','".nl2br($content)."','$rec_expire'";

	    $ins_query = "INSERT INTO ".$_POST['addto_dir']." (".$db_fld.") VALUES (".($db_val).")";

		mysql_query(stripslashes($ins_query))  or die (mysql_error());
				echo"<script>document.location.replace('admin_funtions.php?dir=".$_POST['addto_dir']."&page_id=".$page_id."');</script>";
	} 
	else
	 {
	include("html/add_page.html");
	}
}

?>
