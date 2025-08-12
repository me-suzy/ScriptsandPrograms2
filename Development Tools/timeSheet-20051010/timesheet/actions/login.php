<?php

$login = $_POST[login];

$Q="SELECT id FROM users WHERE email='".addslashes($login['email'])."' AND password='".addslashes($login['password'])."' LIMIT 1";
$ures = mysql_query($Q);

list($id) = mysql_fetch_row($ures);


if ($id > 0)
{
	$_SESSION['id'] = $id;
	header("Location: index.php?page=index");
}
else
{
	session_destroy();
	header("Location: index.php?page=login&msg=".base64_encode("red|Invalid login information."));
}

?>
