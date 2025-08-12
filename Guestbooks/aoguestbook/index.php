<!-- Guestbook script copyright of addicted one http://yourmomatron.com please leave this line in to help me find users of my script via google searching. Newest release can always be found at http://yourmomatron.com/guestbook/aoguestbook.zip -->
<?php
require "config.php";

echo ">><a href=post.php>Sign Guestbook</a><<<br /><br />";

$result = mysql_query("SELECT * FROM entries ORDER BY `id` DESC LIMIT 0 , 100");
while($row = mysql_fetch_array($result))
{

$id = $row[0];

echo stripslashes(nl2br("
<strong>Name:</strong> $row[1]
<strong>Website:</strong> <a href=\"$row[3]\">$row[2]</a>
<strong>Email:</strong> <a href=\"mailto:$row[3]\">$row[3]</a>
<strong>Location:</strong> $row[5]
<strong>Message:</strong> $row[4]
<strong>Time:</strong> $row[7]

---------------
"));

}

?>