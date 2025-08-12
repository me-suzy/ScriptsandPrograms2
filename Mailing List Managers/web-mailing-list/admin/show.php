<?php
include("connect.php");
echo '
<table border="1" cellpadding="2">
<tr>
<td width="25%"><b>Name</b></td>
<td width="25%"><b>Edit</b></td>
<td width="25%"><b>Send</b></td>
<td width="25%"><b>Delete</b></td>
</tr>';

$link = "SELECT * FROM newsletters";
$res = mysql_query($link) or die(mysql_error());
while ($r = mysql_fetch_assoc($res))
{
	echo '<tr>
	<td nowrap>' . $r['name'] . '</td>
	<td><a href="editletter.php?id=' . $r['id'] . '">Edit</a></td>
	<td><a href="sendletter.php?id=' . $r['id'] . '">Send</a></td>
	<td><a href="deleteletter.php?id=' . $r['id'] . '">Delete</a></td>
	</tr>';
}
echo '</table>';
?>