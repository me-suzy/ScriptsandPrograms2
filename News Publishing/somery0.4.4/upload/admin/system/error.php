<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SYSTEM/ERROR.PHP > 03-11-2005

// login errors
$error[0] 	= "You didn't fill in your username and/or password";
$error[1] 	= "Your password didn't match the username";
$error[2] 	= "That user does not exist in my database";
// registration errors
$error[3] 	= "You left the username field empty";
$error[4] 	= "You left both password fields empty";
$error[5] 	= "You left one password field empty";
$error[6] 	= "The email address is invalid";
$error[7] 	= "You left email address field empty";
$error[8] 	= "That username is already taken";
$error[9] 	= "The given passwords don't match";
// profile errors
$error[10]	= "You can't leave the email field blank";
// article posting errors
$error[11]	= "You left the title field empty";
$error[12]  = "You left the body field empty";
// category errors
$error[13]  = "You left the category name field empty";
$error[14]  = "Can't remove the category which has a cid of 0 (default name: general)";
// comment errors
$error[15]  = "You can't leave the name field empty";
$error[16]  = "You can't leave the comment field empty";
?>