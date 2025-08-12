<?
include("header.php");
?>
<?
 $filename = "emails/$email";
 include("config.php");
  $email = $_POST['email'];
 
$fp = fopen($filename, 'w');
fwrite($fp, $email);
fclose($fp);
echo "Thank you for subscribing to $name! (Email subscribed: $email)<br><br><font size=-1>Note: If you ever stop to receiving newsletter emails, you can type your email into the ID Search and it will tell you if it has been rejected or not.";
?>
<?
include("footer.php");
?>
