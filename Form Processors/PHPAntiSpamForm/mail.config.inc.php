<?php
//////////////////////////////////////////////////////////////////////
//       PHPAntiSpamForm v1.0 - sebflipper Copyright 2002           //
//                   http://www.sebflipper.com                      //
//                         Config File                              //
//                                                                  //
//  Please edit this file with your information the best way to do  //
//  this is to read my comments on each section                     //
//                                                                  //
//        By using this script you agree to the Licence              //
//                                                                  //
//      !!!  Last updated by Seb Flipper on 01/03/2002 !!!          //
//////////////////////////////////////////////////////////////////////


//////////////////////////////////////
// Change $admin_email to your email adress and enter a custom subject:
$admin_email = '';
$subject = '';
//////////////////////////////////////
//
//////////////////////////////////////
// User input form
$input_form = <<<ENDH

Name: <input type="text" name="name"><br>
Email: <input type="text" name="email"><br>
Feedback: <input type="text" name="feedback"><br>

ENDH;
// End of page header
//////////////////////////////////////
//
//////////////////////////////////////
$welcome_message = <<<ENDH

Welcome! Please fill in the following information:

ENDH;
//////////////////////////////////////
//
//////////////////////////////////////
$error_message = <<<ENDH

Thank you for testing PHPAntiSpamForm!

ENDH;
//////////////////////////////////////
//
//////////////////////////////////////
// Change this to your send ok page
$sent_page = "sent.php";
//////////////////////////////////////
//
//////////////////////////////////////
// Define unique cookie prefix
$ID = "asf_demo";
// Cookie lifetime in seconds (in this example, three days)
$cookie_life = 3*24*3600;
//////////////////////////////////////
//
//////////////////////////////////////
// Saving the page header in the variable $head.
$head = <<<ENDH
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head>
    <title>PHPAntiSpamForm - Demo</title>
<SCRIPT LANGUAGE="JavaScript1.1">
<!-- Begin
function right(e) {
if (navigator.appName == 'Netscape' &&
(e.which == 3 || e.which == 2))
return false;
else if (navigator.appName == 'Microsoft Internet Explorer' &&
(event.button == 2 || event.button == 3)) {
alert("Sorry, you do not have permission to right click.");
return false;
}
return true;
}

document.onmousedown=right;
document.onmouseup=right;
if (document.layers) window.captureEvents(Event.MOUSEDOWN);
if (document.layers) window.captureEvents(Event.MOUSEUP);
window.onmousedown=right;
window.onmouseup=right;
//  End -->
</script>
</head>
<body>

<h1 align=center>PHPAntiSpamForm</h1>
ENDH;
// End of page header

// Saving the page footer in the variable $tail.
$tail = <<<ENDT
    <hr>
  </body>
</html>
ENDT;
// End of page footer
//////////////////////////////////////
//
//////////////////////////////////////
$javascript = <<<ENDH
<script language="javascript">
<!--
function ValidateForm() {

for (i = 0; i < document.forms[0].elements.length; i++) {
       if (document.forms[0].elements[i].value == "") {
         switch (document.forms[0].elements[i].type) {
           case "text":
             alert('Please complete all fields before submitting');
             document.all.submit.style.visibility='visible';
             return false;
             break;

           case "textarea":
             alert('Please complete all fields before submitting');
             document.all.submit.style.visibility='visible';
             return false;
             break;

           case "file":
             alert('Please complete all fields before submitting');
             document.all.submit.style.visibility='visible';
             return false;
             break;
         }
       }
     }
        return true;
    }
//-->
</script>
ENDH;
//////////////////////////////////////




?>
