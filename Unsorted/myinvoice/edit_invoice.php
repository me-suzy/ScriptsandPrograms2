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
<body>
<img src="inc/title.gif" width="308" height="82">
  <blockquote>
 <? if ($id) {
 echo   "<h1>Edit invoice</h1>";
    }
  else {
   echo " <h1>Add invoice</h1>";
   }
  ?>

<?php
include("inc/dbconnect.php");
if($submit)
{
$sql = "INSERT INTO invoices (clientid, date, details, total) VALUES ('$clientid',NOW(),'$details','$total')";
$result = mysql_query($sql);
echo "<br>Thank you - the invoice has been added.\n<br><br>";
}
else if($update)
{
$sql = "UPDATE invoices SET clientid='$clientid',details='$details',total='$total',status='$status' WHERE id=$id";
$result = mysql_query($sql);
echo "<br>The invoice has been succesfully updated.<br><br>\n";
}
else if($id)
{
$result = mysql_query("SELECT * FROM invoices WHERE id=$id",$db);
$myrow = mysql_fetch_array($result);
$clientid = $myrow["clientid"];
$status = $myrow["status"];
$clientfind = mysql_query("SELECT title FROM clients WHERE clientid = '$clientid'",$db);
$clienttitle = mysql_result($clientfind,0);
?>
<form method="post" action="<?php echo $PHP_SELF?>">
<input type="hidden" name="id" value="<?php echo $id ?>">
  Client:<br>
  <select name="clientid">
  <option value="<? echo $clientid ?>" selected><? echo $clienttitle ?></option>
  <? 
  $clientall = mysql_query("SELECT clientid, title FROM clients",$db);
  while ($clientrow = mysql_fetch_array($clientall))
  {
  $clients = $clientrow["title"];
  $clientids = $clientrow["clientid"];
  echo "<option value=$clientids>$clients</option>";
  }
  ?>
  </select>
  <br>
  Details: <br>
  <textarea name="details" rows="12" cols="50"><?php echo $myrow["details"]?></textarea>
  <br>
  Total:<br>
  <input type="Text" name="total" value="<?php echo $myrow["total"]?>"><br>
  Status:<br>
  <select name="status">
    <option value="<? echo $status ?>" selected><? echo $status ?></option>  
    <option value="pending">pending</option>
    <option value="paid">paid</option>
  </select>
  <br>
  <br>
<input type="Submit" name="update" value="Update information"></form>
<?

}
else
{
?>
<form method="post" action="<?php echo $PHP_SELF?>">
Client:<br>
<select name="clientid">
<? 
$clientfind = mysql_query("SELECT * FROM clients",$db);
while ($clientrow = mysql_fetch_array($clientfind))
  {
  $clienttitle = $clientrow["title"];
  $clientid = $clientrow["clientid"];
  echo "<option value=$clientid>$clienttitle</option>";
}
?>
 </select>
<br>
Details:<br>
  <textarea name="details" cols="50" rows="12"></textarea>
  <br>
Total:<br><input type="Text" name="total" value="£"><br>
Status is automatically set to 'pending'<br><br>
<input type="Submit" name="submit" value="Add invoice"></form>
<?
}
include "inc/nav.inc";
include "inc/footer.inc";
?>

</blockquote>
</body>
</HTML>