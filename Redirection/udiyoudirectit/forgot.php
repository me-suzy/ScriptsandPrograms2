<?php 

// uDi - You Direct It, written by, and copyright Mike Cheesman.

require "config.php";
include "$header";
function frgt_pass () {
global $PHP_SELF;
print "<br>Enter your username below, to have your password emailed to you.<P>";
print "<form action=\"$PHP_SELF?do=sendpass\" method=\"post\">";
print "Username: <input type=\"text\" name=\"fuser\"><p><input type=\"submit\" value=\"Retrieve Password\">";
print "</form>";
}
if (!$do || $do == "") {
frgt_pass();
} else if (!is_dir("$credir/$fuser") && $do == sendpass) {
print "<font color=\"Red\">Please enter a proper user name.</font>";
frgt_pass();
} else if ($do == sendpass && is_dir("$credir/$fuser")) {
include "$credir/$fuser/config.php";
$subj = "$fuser Password Retrieval";
$message .= "Your account's password is: $password";
$message .= "\n\n\nUsername: $username";
$message .= "\nPassword: $password";
$message .= "\nLogin at: $website/login.php";
$message .= "\n\nWe suggest that you login to your account, and change your password to something you will remember and is hard to guess.  Please direct any questions to $adminmail";
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
print "Your password has been email to you at <b>$email</b>.";
frgt_pass();
} else {
frgt_pass();
}
include "$footer"; ?>