<?
include("header.php");
?>
<?
$edited1 = $_POST['edited'];
$filen = $_POST['filen'];
$folder = $_POST['folder'];
$filename = "$folder/$filen";
 $edited2 = stripslashes($edited1);
 $edited = ereg_replace("\n", "<br>", $edited2);

$fp = fopen($filename, 'w');
fwrite($fp, $edited);
fclose($fp);
 echo "You have successfuly edited the article/log $filen with the contents:\n$edited\n\n";
?>
<?
include("footer.php");
?>
