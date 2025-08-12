<?php
require('db_connect.php');
$dbQuery = "SELECT id, username ";
$dbQuery .= "FROM users ";

$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);
if ($_POST['submit'] == 'submit') {
$name = $_POST['topic'];
echo "$name";
echo "test";
echo "$row[0]";
echo "$row[1]";

}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<SELECT NAME="name">
<?php
while($row = mysql_fetch_array($result))

print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";
?>
<input type="Submit" name="submit" value="submit">
</select> 
</form>