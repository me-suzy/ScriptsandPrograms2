<?
session_start();
if(!session_is_registered("client_id"))
{
header("Location: index.htm");
exit;
}
?>
<html>
<link rel="stylesheet" href="inc/style.css" type="text/css">

<body bgcolor="#FFFFFF">
<img src="inc/title.gif" width="308" height="82">
<?
if ($client_name !== 'admin')
{
?>
<h2>Hello <b> 
  <? echo $client_name ?>
  </b> </h2>
  Here are your invoices:

<?
include "inc/dbconnect.php";
include ("inc/date.php");
$result = mysql_query("SELECT * FROM invoices WHERE clientid = '$client_id' ORDER BY $param",$db);
if (!$param) {
$result = mysql_query("SELECT * FROM invoices WHERE clientid = '$client_id' ORDER BY id",$db);
}
echo "<p><table border=1 cellspacing=0 cellpadding=2 bordercolor=#eeeeee width=400>";
echo "<tr align=top><td><b><a href='menu.php?param=id'>Invoice number</a></b></td><td><b><a href='menu.php?param=date'>Date</a></b></td><td><b><a href='menu.php?param=total'>Total</a></b></td><td><b><a href='menu.php?param=status'>Status</a></b></td><td>&nbsp;</td></tr>";

while ($row = mysql_fetch_array($result))
{
	$id = $row["id"];
	$date = $row["date"];
	$dateshow = fixDate($date);
	$total = $row["total"];
	$status = $row["status"];

if ($alternate == "1") { 
	$color = "#ffffff"; 
	$alternate = "2"; 
	} 
	else { 
	$color = "#efefef"; 
	$alternate = "1"; 
	} 

echo "<tr valign=top bgcolor=$color><td>$id</td><td>$dateshow</td><td>$total</td><td>$status</td><td>[ <a href='invoice.php?id=$id'>view</a> ]</td></tr>";
}
echo "</table>";

}
elseif ($client_name == 'admin')
{

echo "<h2>admin options</h2>";

include "inc/dbconnect.php";
include ("inc/date.php");
$result = mysql_query("SELECT * FROM invoices ORDER BY $param",$db);
if (!$param) {
$result = mysql_query("SELECT * FROM invoices ORDER BY id",$db);
}
echo "<p><table border=1 cellspacing=0 cellpadding=2 bordercolor=#eeeeee width=600>";
echo "<tr align=top><td><b><a href='menu.php?param=id'>Invoice number</a></b></td><td><b><a href='menu.php?param=clientid'>Client</a></b></td><td><b><a href='menu.php?param=date'>Date</a></b></td><td><b><a href='menu.php?param=total'>Total</a></b></td><td><b><a href='menu.php?param=status'>Status</a></b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

while ($row = mysql_fetch_array($result))
{
	$id = $row["id"];
	$clientid = $row["clientid"];
	$clientfind = mysql_query("SELECT title FROM clients WHERE clientid = '$clientid'",$db);
	$clienttitle = mysql_result($clientfind,0);
	$date = $row["date"];
	$dateshow = fixDate($date);
	$total = $row["total"];
	$status = $row["status"];

if ($alternate == "1") { 
	$color = "#ffffff"; 
	$alternate = "2"; 
	} 
	else { 
	$color = "#efefef"; 
	$alternate = "1"; 
	} 

echo "<tr valign=top bgcolor=$color><td>$id</td><td>$clienttitle</td><td>$dateshow</td><td>$total</td><td>$status</td>";
if ($status == 'pending') {
echo "<td>[ <a href='admin_invoice.php?id=$id'>view / change status</a> ]</td>";
}
else {
echo "<td>[ <a href='admin_invoice.php?id=$id'>view</a> ]</td>";
}
echo "<td>[ <a href='notifyclient.php?id=$id'>notify client</a> ]</td><td>[ <a href='edit_invoice.php?id=$id'>edit</a> ]</td><td>[ <a href='delete_invoice.php?id=$id' onClick=\"return confirm('Are you sure?')\">delete</a> ]</td></tr>";
}
echo "</table>";

echo "<p><a href='edit_invoice.php'>add an invoice</a> | <a href='clients.php'>manage client profiles</a>";



}
?>

<p><a href="logout.php">Logout</a></p>

<?
include "inc/footer.inc";
?>

</body>
 </html>