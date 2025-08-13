<?
session_start();
if(!session_is_registered("client_id"))
{
header("Location: index.htm");
exit;
}

include "inc/dbconnect.php"; 
$result = mysql_query("SELECT * FROM invoices WHERE id = '$id'",$db);
include ("inc/date.php");
while ($row = mysql_fetch_array($result)) 
{
	$invoiceid = $row["id"];
	$clientid = $row["clientid"];
	$date = $row["date"];
	$dateshow = fixdate($date);
	$details = $row["details"];
	$total = $row["total"];
	$status = $row["status"];
}

?>
<html>
<head>
<title>Invoice <? echo "$client_ref / $invoiceid"; ?></title>
<link rel="stylesheet" href="inc/style.css" type="text/css">
</head>
<? 
if ($clientid !== $client_id) {
echo "This invoice is not accessible by your user profile. Please <a href=menu.php>return to the main menu</a>";
}
elseif ($clientid == $client_id) {
	?>
<body bgcolor="#FFFFFF" text="#000000">
<center>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td>
        <h2 align="center">INVOICE</h2>
        <h1 align="center"><b><? echo $yourtitle ?></b></h1>
        <p align="right"><? echo $address ?></p>
        <table width="550" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
          <tr valign="top">
            <td width="100">Client</td>
            <td width="450">
              <div align="right"><? echo $client_title ?></div>
            </td>
          </tr>
          <tr valign="top">
            <td width="100">Reference</td>
            <td width="450">
              <div align="right"><? echo "<b>$client_ref / $invoiceid</b>"; ?></div>
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
