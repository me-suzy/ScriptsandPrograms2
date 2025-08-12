<?
session_start();
require ('_.php');
require ('functions.php');

$dir=$_GET['dir'];
$usr_lvl = $_SESSION['user_level'];
$first_name = $_SESSION ['first_name'];
$last_name=$_SESSION['last_name'];
$userid=$_SESSION['userid'];
$page_id=$_GET['page_id'];

$auth_query="SELECT auth_id FROM ".$dir." WHERE page_id=".$page_id;
$auth_result=mysql_query($auth_query);
$auth=mysql_fetch_array($auth_result);
$auth_id=$auth[auth_id];

$id_query="SELECT last_name, first_name, userid FROM users WHERE userid=".$auth_id;
$id_result=mysql_query($id_query);
$id=mysql_fetch_array($id_result);

$id_l_name=$id[last_name];
$id_f_name=$id[first_name];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<?
$wysiwyg = $_GET[w];
 if ($wysiwyg==1){echo"  
	<script language=\"javascript\" type=\"text/javascript\" src=\"jscript/tiny_mce/tiny_mce.js\"></script>
<script language=\"javascript\" type=\"text/javascript\">
tinyMCE.init({
	theme : \"simple\",
	mode : \"exact\",
	elements : \"content\"
});
</script>
";
}
?>

		<title>Admin ~ <?=$title?></title>
<?
?></head><?	

if (!$usr_lvl)
{
header("Location: exit.php");

}
else 
{
// echo "<b>".$username."</b> is logged in as User level: ".$usr_lvl;

	if	($usr_lvl ==1){editorpageheader();}
	if	($usr_lvl ==2){Adminpageheader();}
	if	($usr_lvl ==3){Mastpageheader();}
	if 	($usr_lvl ==4){dietypageheader();}
	

//set this page's unique vars, it connetcts to two db tables, $dir and users
$users_query="SELECT email_address, first_name, last_name FROM  users WHERE userid=".$userid;
$users_result =mysql_query($users_query) or die (mysql_error());
$ae = mysql_fetch_array($users_result);
// check for added data

	if (!$_POST[edit] != 1) {
		$page_id=$_POST['page_id'];
		$pg_title = addslashes($_POST[pg_title]);
		$content = addslashes($_POST[content]);
		$plaintext = ($_POST[nl2br] == "yes") ? 1 : 0;
		if ($pg_title=="index"){$isactive=0;}
		else{
		$isactive = intval($_POST[isactive]);
		}
		$rec_edit= addslashes($_POST[rec_edit]);
		$day=$_POST[day];
		$month=$_POST[month];
		$year=$_POST[year];
		$exp=$_POST[expi];
	if ($expi == 1)
		{
		$rec_expire="0000-00-00";
		}else{		
		$rec_expire ="$year,$month,$day";
		}
	    $ins_query = "UPDATE ".$_POST['dir']." SET rec_edit='$rec_edit', isactive='$isactive', pg_title='$pg_title', content='$content', rec_expire='$rec_expire' WHERE page_id=".$page_id." LIMIT 1";
		mysql_query(stripslashes($ins_query)) or die (mysql_error());
		
		echo"<script>document.location.replace('admin_funtions.php?dir=".$_POST['dir']."&page_id=".$page_id."');</script>";
	}else{
//make update form get posted by from auth_id
		$cont_query="SELECT * FROM ".$dir." WHERE page_id=".$page_id;
		$cont_result=mysql_query($cont_query) or die (mysql_error());
		$cont=mysql_fetch_array($cont_result);
			echo "<form action=editpg.php method=POST>\n\n";
			echo"<table border=0 width=80% cellpadding=0 cellspacing=0 class='nav_header'><tr><td>";
			echo"Title=<textarea name=pg_title rows=1>".$cont['pg_title']."</textarea><br>posted: ".$cont[rec_crt]."</td>";
			echo"<td align=right valign=bottom>posted by: <a class=nav_links>".$id_f_name." ".$id_l_name."</a> </td></tr>";
			echo"</table><table border=0 align=center><tr><td colspan=2>";
			echo "<center><h2>Edit this page</h1>";
			echo"<input type=hidden name=dir value=".$dir.">\n";
			echo"<input type='hidden' name='edit' value='1'>\n";
			echo"<input type=hidden name=page_id value=".$page_id.">\n";
			echo"<input type='hidden' name='rec_edit' value='$datetime'>\n";
			echo "<tr><td colspan=2 align=center ><textarea cols=70 rows=10 name=content>".$cont[content]."</textarea></td></tr>";
			echo "<input type=hidden name=isactive value=1></td></tr>";
			//echo"<tr><td colspan=2>Date this info expires,<br>Default= 1 week<br>";
			//echo"<input type=checkbox name='expi' value=1 checked>never expires<br>";
			//echo"<br><table border=0> <tr><td>day&nbsp;&nbsp;&nbsp;".dayPullDown($day+7)." month".monthPullDown($month, $lang['months'])." ".yearPullDown($y)."</td></tr></table></td></tr>";
//			echo "<tr><td colspan=2 > <input type='hidden' name='MAX_FILE_SIZE' value='50000' /> Upload an image: <input name='userfile' type='file' /></td></tr>";
			echo"<tr><td colspan=2><br><input type=submit value=\"Post\"><input type='Reset'>";
			echo "</td></tr></table></td></tr></table></form><br><br>";
	}
	pagefooter();
}
//submit data

?>


