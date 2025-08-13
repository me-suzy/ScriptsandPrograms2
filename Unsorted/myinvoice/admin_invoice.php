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

if ($invsubmit) {
include("inc/dbconnect.php");
$sql = "UPDATE invoices SET status='$status' WHERE id='$invoiceid'";
$result = mysql_query($sql);
echo "<link rel=\"stylesheet\" href=\"inc/style.css\" type=\"text/css\">";
echo "<center>";
echo "<h2>INVOICE</h2>";
echo "<p>The page now has the status 'paid'. </p>";
if ($emailoption == 'yes') {
include "sendpaidemail.php";
echo "<p>The client has been sent a confirmation email.</p>";
}
include "inc/nav.inc";
echo "</center>";
}

else {

include "inc/dbconnect.php"; 
$result = mysql_query("SELECT * FROM invoices WHERE id = '$id'",$db);
include ("inc/date.php");
while ($row = mysql_fetch_array($result)) 
{
	$invoiceid = $row["id"];
	$clientid = $row["clientid"];
	$clientfind = mysql_query("SELECT title FROM clients WHERE clientid = '$clientid'",$db);
	$clienttitle = mysql_result($clientfind,0);
	$clientrefq = mysql_query("SELECT ref FROM clients WHERE clientid = '$clientid'",$db);
	$clientref = mysql_result($clientrefq,0);
	$clientemailfind = mysql_query("SELECT email FROM clients WHERE clientid = '$clientid'",$db);
	$clientemail = mysql_result($clientemailfind,0);
	$date = $row["date"];
	$dateshow = fixdate($date);
	$details = $row["details"];
	$total = $row["total"];
	$status = $row["status"];
}

?>
<html>
<head>
<title>Invoice <? echo "$clientref / $invoiceid"; ?></title>
<link rel="stylesheet" href="inc/style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000">
<center>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td>
        <h2 align="center">INVOICE</h2>
        <?
        if ($status == 'pending') {
	 	echo "If this invoice has been paid, please <form method=post action=$PHP_SELF><input type=hidden name=invoiceid value=$invoiceid><input type=hidden name=status value='paid'><input type=hidden name='clientemail' value='$clientemail'><input type=submit name=invsubmit value='update status to PAID'></form>";
	 	if ($emailoption == 'yes') {
		echo "<p>The client will been sent a confirmation email. (You can switch this feature off in the config.php file).</p>";
						}
 	}
 	if ($status == 'paid') {
 	echo "This invoice has been paid";
 	}
        ?>
        <h1 align="center"><b><? echo $yourtitle ?></b></h1>
        <p align="right"><? echo $address ?></p>
        <table width="550" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
          <tr valign="top">
            <td width="100">Client</td>
            <td width="450">
              <div align="right"><? echo $clienttitle ?></div>
            </td>
          </tr>
          <tr valign="top">
            <td width="100">Reference</td>
            <td width="450">
              <div align="right"><? echo "<b>$clientref / $invoiceid</b>"; ?></div>
            </td>
          </tr>
          <tr valign="top">
            <td width="100">Date</td>
            <td width="450">
              <div align="right"><? echo $dateshow ?></div>
            </td>
          </tr>
          <tr valign="top">
            <td width="100" height="300">Description</td>
            <td width="450" height="300">
              <p><? echo $details ?></p>
              <p>&nbsp;</p>
            </td>
          </tr>
          <tr valign="top">
            <td width="100">Total</td>
            <td width="450">
              <div align="right"><? echo $total ?></div>
            </td>
          </tr>
        </table>
        <p align="center"><? echo $payee ?><br>
          <? echo  $due ?></p>
      </td>
    </tr>
    <tr valign="top">
      <td>

<SCRIPT LANGUAGE="JavaScript">
if (window.print) {
document.write('<form><input type=button name=print value="Print invoice" onClick="javascript:window.print()"></form>');
}
</script>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</center>
<?
}
?>
</body>
</html>
