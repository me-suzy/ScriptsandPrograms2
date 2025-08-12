 <?
include("header.php");
?>
 <?php
 include("config.php");
 $name1 = $_POST['Name'];
 $cata1 = $_POST['Cata'];
 $stuff1 = $_POST['Stuff'];
 $alias1 = $_POST['Alias'];
 $name = stripslashes($name1);
 $cata = stripslashes($cata1);
 $stuff2 = stripslashes($stuff1);
 $alias = stripslashes($alias1);
$stuff = ereg_replace("\n", "<br>", $stuff2);
 
$stuffHTML = "<font size=5><u>$name</u></font><br><font size=4>$cata</font><br><br><font size=3>$stuff</font><hr align=left size=1 width=100%><font size=2>Submitted by: $alias</font>";
 
 $targetfilename = "pend/" . time() . $_SERVER['REMOTE_ADDR'] . ".html";
 $id = "" . time() . $_SERVER['REMOTE_ADDR'] . ".html"; 
 $tempfilename = "temp/" . time() . $_SERVER['REMOTE_ADDR'] . ".html"; 
 @unlink($tempfilename);
 $tempfile = fopen($tempfilename, 'w');
 if (!$tempfile) { die("Unable to open temporary file ($tempfilename) for writing. Page \"$fn\" not updated."); 
 }
 fwrite($tempfile, $stuffHTML);
 fclose($tempfile);
 $ok = copy($tempfilename, $targetfilename);
 unlink($tempfilename);
 $log = "Pending";
$logfile = "logs/$id";
$temperfile = fopen($logfile, 'w');
fwrite($temperfile, $log);
 fclose($temperfile);  
 echo "You have successfuly submitted your article. It is currently pending.\nYour article's ID is $id. Go <a href=logviewer.php>here</a> and search your ID to get status on your article.\n\n";
 ?>
 <?
include("footer.php");
?>
