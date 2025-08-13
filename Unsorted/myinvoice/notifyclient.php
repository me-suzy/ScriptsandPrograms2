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

include "inc/dbconnect.php"; 
$result = mysql_query("SELECT * FROM invoices WHERE id = '$id'",$db);
include ("inc/date.php");

while ($row = mysql_fetch_array($result)) 
{
	$invoiceid = $row["id"];
	$clientid = $row["clientid"];
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
<title>My Invoice</title>
<link rel="stylesheet" href="inc/style.css" type="text/css">
</head>
<body>

  <p><img src="inc/title.gif" width="308" height="82"></p>
  <blockquote> 
  
<h1>Notify client</h1>

<? 
if ($submit) {
include "sendnotification.php";
echo "The client notification has been sent";
}
else {
?>
<form name="form1" method="post" action="<? echo $PHP_SELF ?>">
Message: <br>
  <textarea name="message" cols="50" rows="10">This message is to notify you that an invoice is due from <? echo $yourtitle ?>.
  This invoice is dated <? echo $dateshow ?>.
 
This invoice can be downloaded from <? echo $site ?> with your username and password. If you have not received these yet, please email <? echo $youremail ?>.
  </textarea>
    <input type="hidden" name="clientemail" value="<? echo $clientemail ?>">
    <input type="hidden" name="date" value="<? echo $dateshow ?>">
    <br>
    <input type="submit" name="submit" value="Send notification">
  </form>
<?
}
include "inc/nav.inc";
include "inc/footer.inc";

?>
</blockquote>

</body>
</html>
