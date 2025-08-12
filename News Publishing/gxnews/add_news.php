<?
include("config.php");
include("connect.php");
$title = $_POST['title'];
$body = $_POST['body'];
$date = $_POST['date'];
$password = $_POST['password'];
if ($submit == "") { ?>
<p>This is the form for adding site news. If you don't have permission to add news, just leave now!</p>
<form method="post" action="add_news.php" name="add_news">
<table width="100%" cellspacing="3" cellpadding="0">
<tr>
<td>
<b>Title:</b>
</td>
<td>
<input type="text" name="title" size="35">
</td>
</tr>
<tr>
<td>
<b>Date:</b>
</td>
<td>
<input type="text" name="date" size="35">
</td>
</tr>
<tr>
<td>
<b>Body:</b>
</td>
<td>
<textarea name="body" cols="40" rows="7"></textarea>
</td>
</tr>
<tr>
<td>
<input type="password" name="password" size="5">
</td>
<td>
<input type="submit" name="submit" value="Add News!">
</td>
</tr>
</table>
</form>
<?
}
if (($submit == "Add News!") && ($title != "") && ($date != "") && ($body != "") && ($password == "$admin_pass")) {
echo "Thank you for adding news!";
mysql_query("INSERT INTO news
(title, date, body) VALUES('$title', '$date', '$body') ") 
or die(mysql_error()); 
}
?>
