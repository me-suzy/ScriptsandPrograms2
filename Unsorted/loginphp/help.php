<?php
ob_start();
//include the header
require("top.php");
echo "<center>";
echo "If you have any questions, you can email the admin by filling out the fields below<br><br>";
echo "<form method=post action=help.php?action=email><table><tr><td>Email:</td><td><input type=text name=Email></td></tr><tr><td></td><td><textarea name=Body cols=40 rows=10></textarea></td></tr><tr><td></td><td><input type=submit value=Email></td></tr></table></form>";
echo "<br>";
if($_GET['action'] == 'email')
{
// The message
$message = "From: " . $_POST['Email'] . "...  " . $_POST['Body'];

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70);

// Send
mail($_POST['Email'], 'Loginphp', $message);
echo "<br><b>The message was sent...</b>";
}
?>