Your account with the username<b> <?php print "$username"; ?> </b>has been created. Login information has been emailed to <b><?php print "$email"; ?></b>.
<?php 
$subj = "$sitename Account";
$message .= "Your account has been created, your address is $website/$username";
$message .= "\nYou may login by using the following:";
$message .= "\n\n\nUsername: $username";
$message .= "\nPassword: $password1";
$message .= "\nLogin at: $website/login.php";
$message .= "\nSite address being redirected: $address";
$message .= "\nRedirect Address: $website/$username";
$message .= "\n\n$sitename would like to thank you for your interest in our service, and we hope that you will be satisfied.  Please direct any questions to $adminmail";
$message .= "\n\nThank you,";
$message .= "\n$sitename Staff";
$message .= "\n\n----------------------------------";
$message .= "\nuDi - You Direct It written by Mike Cheesman.";
$headers .= "From: $sitename < $adminmail >\n";
$headers .= "X-Sender: < $adminmail >\n";
$headers .= "X-Mailer: $sitename Mailer\n";
$headers .= "X-Priority: 3\n";
$headers .= "Return-Path: < $adminmail >\n";
mail($email, $subj, $message, $headers);
?>
