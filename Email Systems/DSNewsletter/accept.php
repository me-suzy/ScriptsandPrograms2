 <?
include("header.php");
?>
 <?php
 include("config.php");
 $file = $_POST['file'];
 
 $temp = "pend/$file";
 $month = "" . date(m) . "_";
 $new = "perm/" . date(m) . "_$file";
 
copy($temp, $new);
 unlink($temp);
 
 $log = "Accepted";
$logfile = "logs/$file";
$temperfile = fopen($logfile, 'w');
fwrite($temperfile, $log);
 fclose($temperfile);  

 echo "Successfuly accepted the article $file!\n\n";
 ?>
 <?
include("footer.php");
?>
