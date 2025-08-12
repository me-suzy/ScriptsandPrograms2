 <?
include("header.php");
?>
 <?php
 include("config.php");
 $file = $_POST['file'];
 $folder = $_POST['folder'];
  $log = $_POST['log'];
 
 if($log == "1"){
 $temp = "$folder/$file";
 unlink($temp);
  echo "Successfuly rejected the article/log/email $file!\n\n";
 } else {
 
 $temp = "$folder/$file";
 
 unlink($temp);
 
 $log = "Rejected";
$logfile = "logs/$file";
$temperfile = fopen($logfile, 'w');
fwrite($temperfile, $log);
 fclose($temperfile);  
 echo "Successfuly rejected the article/log/email $file!\n\n";
 }
 ?>
 <?
include("footer.php");
?>

