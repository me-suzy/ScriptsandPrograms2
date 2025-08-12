<?php
require_once("config.inc.php");
if (strlen($tinybb_header) > 0) { require_once($tinybb_header); }

if (strlen($tinybb_email) > 0) {
$to = $tinybb_email;
$headers = "From: tinybb <$tinybb_email>\n";
$message = "tinybb was unable to connect to the MySQL database from the following page:\n".$_SERVER['HTTP_REFERER'];
$subject = "MySQL Connection Error";
mail($to, $subject, $message, $headers);
}

echo "<p><b>We were unable to connect to the MySQL datbase to process your request.</b></p>\n<p>The webmaster has been alerted to this problem.</p>\n";

if (strlen($tinybb_footer) > 0) { require_once($tinybb_footer); }
?>