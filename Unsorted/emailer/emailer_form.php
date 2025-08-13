<?php
?>
<html>
<head>
<title>dB Masters FormMailer 3.0</title>
</head>
<body>
<form id="form" method="post" action="emailer.php"> 
<p>Your Name<br /><input type="text" name="Name" value="<?php echo $Name; ?>" /></p> 
<p>Your Email<br /><input type="text" name="Email" value="<?php echo $Email; ?>" /></p> 
<p>Comments and/or Questions<br /><textarea name="Comments" rows="5" cols="40"><?php echo $Comments; ?></textarea></p> 
<p>
<input type="submit" name="Submit" value="Submit" /> 
<input type="reset" name="Reset" value="Clear Form" />
<input type="hidden" name="subject" value="Subject of Email" />
<input type="hidden" name="reply_to_field" value="Email" />
<input type="hidden" name="required_fields" value="Name,Comments" />
<input type="hidden" name="required_email_fields" value="Email" />
<input type="hidden" name="recipient_group" value="0" />
<input type="hidden" name="error_page" value="" />
<input type="hidden" name="thanks_page" value="" />
<input type="hidden" name="send_copy" value="no" />
<input type="hidden" name="copy_subject" value="Subject of Copy Email" />
<input type="hidden" name="copy_tomail_field" value="Email" />
<input type="hidden" name="mail_type" value="vert_table" />
<input type="hidden" name="mail_priority" value="1" />
<input type="hidden" name="return_ip" value="1" />
</p>
</form> 
</body>
</html>