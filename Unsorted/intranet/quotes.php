<html>
<head></head>
<?php
/*  E5DDDD */
include("config.php");
include("identity.php");
$dn = getenv(REMOTE_HOST);
$hd = getenv(HOME);
$browser = getenv(HTTP_USER_AGENT);
$previous = getenv(HTTP_REFERER);
$computer_email = getenv(HTTP_FROM);
$server = getenv(HTTP_HOST);
$coloration = "#E5DDDD";
$altcolor = "#EEEEEE";
?>

<?php
include("header.php");
if ($action == 'add')
	{
	dbconnect($dbusername,$dbuserpasswd);
	$quotetext = addslashes($quotetext);
	$quoteauthor = addslashes($quoteauthor);
	mysql_query( "insert into quotes (quotetext, quoteauthor, quotetype) values ('$quotetext', '$quoteauthor', '$quotetype')");
	echo "<B>Added quote:</b><p>", $quotetext, "<br><i> - ", $quoteauthor, "</i><br>(", $quotetype, ")";
	} else {
echo "<blockquote><form method='post' action='quotes.php'>";
echo "Text of Quote:<br><textarea name='quotetext' cols='35' rows='5' wrap='virtual'></textarea><br>";
echo "Author of Quote: <input type='text' size='35' name='quoteauthor' value='Anonymous'><br>";
echo "Type of Quote: <select name='quotetype'>";
		echo "<option value='d'>Dark Quote";
		echo "<option value='h'>Happy Quote";
		echo "<option value='o'>Odd Quote";
		echo "<option value='j'>Joke";
		echo "<option value='f'>Fortune";
		echo "<option value='c'>Crude";
echo "</select><br><input type='hidden' value='add' name='action'><input type='submit' value='Add Quote'></blockquote>";
                }

?>
<blockquote>
<b>Show all</b><ul><li><a href='quotes.php?action=show&quotetype=d'>Dark Quotes</a>
<li><a href='quotes.php?action=show&quotetype=h'>Happy Quotes</a>
<li><a href='quotes.php?action=show&quotetype=o'>Odd Quotes</a>
<li><a href='quotes.php?action=show&quotetype=j'>Jokes</a>
<li><a href='quotes.php?action=show&quotetype=f'>Fortunes</a>
<li><a href='quotes.php?action=show&quotetype=c'>Crude</a>
<li><a href='quotes.php?action=show&quotetype=a'>All of Them</a>
</ul>
</blockquote>
<?php
if ($action == 'show')
	{
	echo "<table border='0' cellpadding='0' cellspacing='0'>";
dbconnect($dbusername,$dbuserpasswd);
if ($quotetype != 'a') { $result = mysql_query("select id, quotetext, quoteauthor, quotetype from quotes where quotetype = '$quotetype' order by quoteauthor"); }
else { $result = mysql_query("select id, quotetext, quoteauthor, quotetype from quotes order by quotetype, quoteauthor, id"); }
while ($row = mysql_fetch_row($result))
	{
	echo "<tr><td bgcolor='666666' align='center'><font color='white'>", $row[0], "(", $row[3], ")</font></td>";
	echo "<td rowspan='2'>", stripslashes($row[1]), "</td></tr>";
	echo "<td>", $row[2], "</td></tr>";
	echo "<tr><td colspan='2'><hr></td></tr>";
	}
	echo "</table>";
	}
?>
<p></center></body></html>
