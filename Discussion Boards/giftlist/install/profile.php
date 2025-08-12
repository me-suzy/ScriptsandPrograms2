<?php


$id=$_GET['id'];

$query=" SELECT * FROM users WHERE id='$id'";
$result=mysql_query($query);
$num=mysql_numrows($result);
mysql_close();

$i=0;
while ($i < $num) {
$username=mysql_result($result,$i,"username");
$email=mysql_result($result,$i,"email");
$movies=mysql_result($result,$i,"movies");
?>

<form action="updated.php" method="post">
<input type="hidden" name="ud_id" value="<? echo $id; ?>">
Username: <input type="text" name="ud_first" value="<? echo $username; ?>"><br>
email: <input type="text" name="ud_last" value="<? echo $email; ?>"><br>
movies: <input type="text" name="ud_phone" value="<? echo $movies; ?>"><br>
<input type="Submit" value="Update">
</form>

<?php
++$i;
}

?>

