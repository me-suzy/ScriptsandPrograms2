<?

/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/
error_reporting(0);
require_once("config.php");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");

$filename = safeFilename($filename);
$dir = stripslashes(safepath($dir));


//set path
$base_dir = $config[base_dir];
if ($dir) {
	$base_dir = $base_dir . $dir;
}


if ($submit) {
	if (strlen($toaddr) > 5) {
require_once("$config[root_dir]/class/email/htmlmimemail.php");

$mail = new htmlMimeMail();
//text part
$text = $mail->getFile("$config[root_dir]/class/email/mail.txt");
$mail->setText($text);
//attachment part
$attachment = $mail->getFile($base_dir . "/" . $filename);
$mail->addAttachment($attachment, $filename, 'application/octet-stream');
$mail->setFrom('Afian file manager');
$mail->setSubject('A file from my server');
$result = $mail->send(array($toaddr));

		if($result) {
		$alert = "File \\\"".safestr($filename, false)."\\\" sent to \\\"$toaddr\\\".";
		$closePopup = true;
		} else {
		$alert = "Failed to send file \\\"".safestr($filename, false)."\\\" by email.";
		}
	} else {
		$alert = "Please enter an email address.";
	}
}
?>
<html>
<head>
<title>Afian file manager - Send by e-mail</title>
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/style.css">
</head>
	<script language="JavaScript1.2" type="text/javascript">
	<?if($alert){?>alert('<?echo $alert?>');<?}?>
	<?if($closePopup){?>parent.closePopup();<?}?>
	</script>
<body bgcolor="white">
<div align="center" style="white-space:nowrap;">
<br>
<form action="byemail.php">
<strong>Send To:</strong> <input type="text" name="toaddr">
<br><br>
<input type="submit" value="send" name="submit" class="button">			
<input type="button" onClick="javascript:parent.closePopup()" value="cancel" class="button">
<input type="hidden" name="filename" value="<?echo $filename?>">		
<input type="hidden" name="dir" value="<?echo $dir?>">
	</form>
</div>	
</body>
</html>