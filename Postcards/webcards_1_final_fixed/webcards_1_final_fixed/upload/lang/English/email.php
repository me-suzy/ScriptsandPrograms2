<?php
$lang['email_subject_recip'] = "Somebody has sent you a WebCard!";
$lang['email_body_recip'] = "Hey {{recip}},
<<>>{{sender_name}} ({{sender_email}}) has sent you a WebCard.
<<>>
<<>>To pick it up just point your Browser to the following address
<<>>{{url}}pickup.php?act=pickup&id={{card_id}}
<<>>
<<>>You may have to paste the address into your web browser.
<<>>
<<>>If the link does not work correctly please visit:
<<>>{{url}}pickup.php
<<>>and enter your pickup code:
<<>>
<<>>{{card_id}}
<<>>Don\'t forget to send {{sender_name}} a card back while you are there.";
$lang['email_subject_notification'] = "Notification: your WebCard has been picked up";
$lang['email_body_notification'] = "Hey {{sender_name}} ({{sender_email}}),
<<>>
<<>>Just a quick note to let you know that the WebCard you sent on {{date}} has been picked up.
<<>>
<<>>If you wish to send another one just visit:
<<>>{{url}}index.php";
$lang['email_subject_resend'] = "Notification: WebCard pickup code reminder";
$lang['email_body_resend'] = "Hey {{recip}},
<<>>
<<>>a request was recently made to send you your last WebCard pickup code.
<<>>
<<>>To pickup the card just click the following address
<<>>{{url}}pickup.php?act=pickup&id={{card_id}}
<<>>
<<>>If the link does not work correctly please visit:
<<>>{{url}}pickup.php
<<>>and enter your pickup code:
<<>>{{card_id}}
<<>>
<<>>If you did not request to have your code re-sent, please ignore this email.";
$lang['email_subject_test_email'] = "Testing WebCards email function";
$lang['email_body_test_email'] = "This email is to confirm that your WebCard email functions are working properly.
<<>>It was sent to the default administrator email address ({{recip}}).
<<>>
<<>>The following information is also included in this email:
<<>>Date sent: {{date}}
<<>>Administrator sending the email: {{sender_name}}
<<>>IP Address of sender: {{ip}}
<<>>
<<>>Since you have recieved this email, you can rest assured the email function of your WebCard system is working correctly.";
?>