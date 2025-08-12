<HTML>
<HEAD>
<TITLE>THANK YOU</TITLE>
</HEAD>
<BODY>

<!--- your html here --->

<?php
$file                          = "guestbook.txt";
$name		= $_POST['name'];
$email		= $_POST['email'];
$site   	                = $_POST['site'];
$msg		= $_POST['msg'];
$d  = date("l dS of F Y h:i:s A");
$site   = stripslashes($site);
$msg  = stripslashes($msg);
$email  = stripslashes($email);
$name = stripslashes($name);
$msg = str_replace ("<","&lt;",$msg);
$msg = str_replace ("\n","<br>",$msg);
$site = str_replace ("<","&lt;",$site);
$site = str_replace ("\n","<br>",$site);
$email = str_replace ("<","&lt;",$email);
$email = str_replace ("\n","<br>",$email);
$name = str_replace ("<","&lt;",$name);
$name = str_replace ("\n","<br>",$name);
if(empty($email) || empty($name) || empty($msg)) {
echo "<h3>Sorry all fields are required</h3>";
} else {
$fp = fopen($file,"a");
fwrite($fp, '
<font size="4">
<BR><BR><BR>
Name: '.$name.'<BR>
Email: <a href="mailto:'.$email.'">'.$email.'</a><BR>
Home Page: <a href="'.$site.'">'.$site.'</a><BR>Message: '.$msg.'<BR>
Date & Time: '.$d.'</font>
');
fclose($fp);
echo '<font size="3"><p align="center">Thank you '.$name.' for singing my guestbook</p></font>'; 
}
?>

<!--- your html here --->

</BODY>
</HTML>