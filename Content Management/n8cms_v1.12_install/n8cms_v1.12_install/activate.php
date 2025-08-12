<?
/* Account activation script */

// Get database connection
include('_.php');


?>
<body onload="javascript:window.focus();">
<?
// Create variables from URL.

$userid = $_REQUEST['id'];
$code = $_REQUEST['code'];

$sql = mysql_query("UPDATE users SET activated='1' WHERE userid='$userid' AND password='$code'")or die (mysql_error());

$sql_doublecheck = mysql_query("SELECT * FROM users WHERE userid='$userid' AND password='$code' AND activated='1'") or die (mysql_error());
$doublecheck = mysql_num_rows($sql_doublecheck);

if($doublecheck == 0){
	echo "<strong><font color=red>Your account could not be activated!</font></strong>";
} elseif ($doublecheck > 0) {
	echo "<a class='db_nav1'/>Your account has been activated!</a/> You may login <br /> <p>you MUST change your password ASAP!</p>
	your editor status will not be granted until aproval by a higher level admin
	";
include 'html/login_form.html';
}

?>
<script>

onload=window.focus
</script>