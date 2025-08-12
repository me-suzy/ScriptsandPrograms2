<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];




$query="SELECT * FROM users WHERE id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$user = $row["username"];
$displayname = $row["displayname"];

}


if ($_POST['submit'] == 'submit') {



mysql_query("DELETE FROM users WHERE id= $fileId ")
or die(mysql_error());



?>

<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberedit.php">
<?php
}
?>

<HTML>
<HEAD>
<TITLE>Delete Member</TITLE>
</HEAD>
<BODY>
<font color="#<?php echo $col_text ?>">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">

Confirm delete user <?php echo "$user" ?> ?
<br><br>

<input type="Submit" name="submit" value="submit">
</form>
</font>
</BODY> 
</HTML>

