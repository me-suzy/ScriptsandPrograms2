<?php
include "header.php";
// require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}




$query="SELECT * FROM users WHERE username = '$fileId'";
$result=mysql_query($query);
$num=mysql_numrows($result); 
mysql_close();

$i=0;
while ($i < $num) {

$email=mysql_result($result,$i,"email");
$movies=mysql_result($result,$i,"movies");
$music=mysql_result($result,$i,"music");
$books=mysql_result($result,$i,"books");
$vouchers=mysql_result($result,$i,"vouchers");
$misc=mysql_result($result,$i,"misc");


?>


<input type="hidden" name="ud_id" value="<? echo $id; ?>">



Profile for: <? echo "$fileId"; ?>
<br><br>
<tr>
Email</td><br>
<td><textarea readonly rows=1 cols=44 name="ud_email"><? echo $email ?></textarea></td>

</tr>
<br><br>
<tr>
Movies</td><br>
<td><textarea readonly rows=3 cols=44 name="ud_movies"><? echo $movies ?></textarea></td>
</tr>
</tr>
<br><br>
<tr>
Music</td><br>
<td><textarea readonly rows=3 cols=44 name="ud_music"><? echo $music ?></textarea></td>
</tr>
</tr>
<br><br>
<tr>
Books</td><br>
<td><textarea readonly rows=3 cols=44 name="ud_books"><? echo $books ?></textarea></td>
</tr>
</tr>
<br><br>
<tr>
Vouchers</td><br>
<td><textarea readonly rows=3 cols=44 name="ud_vouchers"><? echo $vouchers ?></textarea></td>
</tr>
</tr>
<br><br>
<tr>
Misc</td><br>
<td><textarea readonly rows=8 cols=44 name="ud_misc"><? echo $misc ?></textarea></td>
</tr>


<br><br>

</form>



<?
++$i;
} 
?>