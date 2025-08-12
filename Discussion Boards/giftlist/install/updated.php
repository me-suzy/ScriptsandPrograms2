<?
include "header.php";
// require('db_connect.php');	// database connect script.
$ud_id = $_POST['ud_id'];

$ud_email = $_POST['ud_email'];
$ud_movies = $_POST['ud_movies'];
$ud_music = $_POST['ud_music'];
$ud_books = $_POST['ud_books'];
$ud_vouchers = $_POST['ud_vouchers'];
$ud_misc = $_POST['ud_misc'];

$query="UPDATE users SET email='$ud_email', movies='$ud_movies', music='$ud_music', books='$ud_books', vouchers='$ud_vouchers', misc='$ud_misc' WHERE username='$ud_id'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
echo "Record Updated";
mysql_close();
?>
<br><br>

