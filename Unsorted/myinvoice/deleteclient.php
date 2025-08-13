<?
session_start();
if(!session_is_registered("client_id"))
{
header("Location: index.htm");
exit;
}
if ($client_name !== 'admin')
{
header("Location: index.htm");
exit;
}
?>
<HTML>
<link rel="stylesheet" href="inc/style.css" type="text/css">
<body bgcolor="#FFFFFF">
<p><img src="inc/title.gif" width="308" height="82"> </p>
<?php
include("inc/dbconnect.php");

mysql_query("DELETE FROM clients WHERE clientid=$id",$db);

echo "Client deleted<br><br>";
echo "<a href=clients.php>Return to clients list</a>";
include "inc/nav.inc";
include "inc/footer.inc";
?>

</body>
</HTML>