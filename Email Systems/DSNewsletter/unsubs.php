<?
include("header.php");
?>
<?
 $filename = "emails/$email";
 include("config.php");
  $email = $_POST['email'];
 
unlink($filename);
echo "Thank you for unsubscribing to $name! We hoped you liked it! (Email unsubscribed: $email)<br><br>";
?>
<?
include("footer.php");
?>
