<?
include("header.php");
?>
<?php
$month = date(m);
$pfix = $_POST['pfix'];
$qwertyuiop123 = $_POST['qwertyuiop123'];
 $prefixer = "" . $pfix . "_";
 $filename = "issue.html";
 include("config.php");
 
 if($qwertyuiop123 != "asdfghjkl321") {
 echo "You do not have permission to view this page.";
 exit;
 }
 
 if($pfix == $month) {
$fp = fopen($filename, 'w');
fwrite($fp, $prefixer);
fclose($fp);
}
 
 echo "The new issue has been published! (Prefix code $prefixer)\n\n";
 // $files = array();
$dir = "emails";
$dh = opendir($dir);
while (false !== ($filenamer = readdir($dh))) {
	    $files = "$dir/$filenamer";
		$email = file_get_contents("$files");

		/* recipients */
$to = $email;

/* subject */
$subject = "New Issue of $name: Code $prefixer";

/* message */
$message = "There is a new issue of $name out!<br><br>Issue code $prefixer is ready at your fingertips!<br>Go to <a href=http://www.$url/$prefixer.php>http://$url/$prefixer.php</a> to view this amazing issue, or view it down below if you have HTML support for your email client.<br><br><br>";

$currr = file_get_contents("issue.html");
$message .= "<font size=6><u>$name Issue Code $prefixer</u></font><br><br><br><br>";
$dirr = "perm";
$dhp = opendir($dirr);

while (false !== ($filenamer = readdir($dhp))) {

    if ($filenamer == '.' || $filenamer == '..') {
        continue; // skip these
    }
    if (0 === strpos($filenamer, $currr)) { // if the prefix exists at the very beggining of the filename
        $files2 = "$dirr/$filenamer";
	$strt = file_get_contents("$files2");
	$message .= "$strt <br><br><br>";
    }
}


$message .= "<br>Thank you for viewing Issue Code $prefixer of $name!!";

/* To send HTML mail, you can set the Content-type header. */
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

/* additional headers */
$headers .= "To: " . $email . "\r\n";
$headers .= "From: $name <newsletter@" . $url . ">\r\n";

/* and now mail it */
if($email != "") {
mail($to, $subject, $message, $headers);
}

    }
 ?>
 <?
include("footer.php");
?>
	 
	 
