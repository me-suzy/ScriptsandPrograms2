<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################

$GLOBALS['strHome'] = "Home";
$GLOBALS['strBack'] = "Back";
$GLOBALS['date_format'] = "Y-m-d";
$GLOBALS['time_format'] = "H:i:s";
$GLOBALS['strAdd'] = "Add";
$GLOBALS['strUpdate'] = "Update";
$GLOBALS['strDelete'] = "Delete";
$GLOBALS['strName'] = "Name";
$GLOBALS['strSurname'] = "Surname";
$GLOBALS['strEmail'] = "Email";
$GLOBALS['strUsername'] = "Username";
$GLOBALS['strPassword'] = "Password";
$GLOBALS['strConfirm'] = "confirm";
$GLOBALS['strSignup'] = "Sign Up";
$GLOBALS['strLogin'] = "Login";
$GLOBALS['strSubmit'] = "submit";
 $GLOBALS['strRegFree'] = "If you are not registered yet with myAgenda, click here to register <a href=\"register.php\"><b>freely</b></a>!";
$GLOBALS['strJSUsername'] = "Enter your username!\\nYour username should be\\nmin 4, max 10 charecters";
$GLOBALS['strJSPassword'] = "Enter your password!\\nYour password should be\\nmin 4, max 10 charecters";
$GLOBALS['strErrorWronguser'] = "Wrong username/password!";
$GLOBALS['strErrorTimeout'] = "Timeout. Please sign in again!";
$GLOBALS['strErrorUnknown'] = "Unkown Error!";


# agenda_add.php
$GLOBALS['strHaveNotes'] = "<b><u>P.S:</u></b> You have note(s) on the day(s) marked <font color=\"#FF0000\">*</font>";
$GLOBALS['strAddReminder'] = "Add Reminder";
$GLOBALS['strEditReminder'] = "Edit Reminder";
$GLOBALS['strWriteNote'] = "Write your reminder note";
$GLOBALS['strMaxNoteChars'] = "Max 125 chars";
$GLOBALS['strRemindBefore'] = "in advance";
$GLOBALS['strFromMyDate'] = "remind me";
$GLOBALS['strMyThisReminder'] = "Just/Every";
$GLOBALS['strError'] = "Error";
$GLOBALS['strErrorWrongDate'] = "The date you selected is wrong!";
$GLOBALS['strErrorOldDate'] = "The date you selected is old date!";
$GLOBALS['strErrorLackDate'] = "The date you selected is lack!";
$GLOBALS['strJSNoNote'] = "Please add some notes";
$GLOBALS['strJSToomuchChars'] = "Max 125 chars";
$GLOBALS['strSaveRemindOk'] = "Your reminder saved successfully!";
$GLOBALS['strErrorSqlInsert'] = "An error occured while saving your data. Please inform this error to <a href=\"mailto:".$contact_mail."\">".$contact_mail."</a> address.";

# register.php
$GLOBALS['strJSNoNote'] = "Enter your name!";
$GLOBALS['strJSEnterSurname'] = "Enter surname!";
$GLOBALS['strJSEnterEmail'] = "Enter your email address correctly!";
$GLOBALS['strJSPasswordsNoMatch'] = "Your passwords doesn\'t match!";
$GLOBALS['strRepeate'] = "Confirm";

$GLOBALS['strRegisterOk'] = "<b>Congratulations!</b><p>You are registred successfully. <p>To login please click <a href=\"login.php\">here</a>.";
$GLOBALS['strGoLocation'] = "To go where you come please click <a href=\"login.php?location=$location\">here</a>.";
$GLOBALS['strExistMail'] = "The email address (".$HTTP_POST_VARS[email].") you submitted has been found on our database. Pleae try to get your password by clicking here.";
$GLOBALS['strExistUser'] = "The username you entered is registred with us. Please go back and try another username.";
$GLOBALS['strWrongMail'] = "The email address (".$HTTP_POST_VARS[email].") you submitted is wrong. Please go back and check it again.";
$GLOBALS['strReminderUpdated'] = "Your reminder updated!";
$GLOBALS['strReminderDeleted'] = "Your reminder deleted!";



$GLOBALS['strMonthnames'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$GLOBALS['strWeekdays'] = array("Sun", "Mon", "Thu", "Wed", "Thr", "Fri", "Sat", "Sun");

$GLOBALS['strGo'] = "Go";
$GLOBALS['strLogout'] = "Logout";
$GLOBALS['strPrevious'] = "Previous";
$GLOBALS['strNext'] = "Next";
$GLOBALS['strMailSubject'] = "Reminder";
$GLOBALS['strMailHeader'] = "Dear {name},\n";
$GLOBALS['strMailFooter'] = "Just Remind,\n{programname}\n" . $myAgenda_url;
$GLOBALS['strConfirm'] = "Are You Sure to Delete This Record?";
$GLOBALS['strSave'] = "Save";
$GLOBALS['strYes']						= "Yes";
$GLOBALS['strNo']						= "No";

$GLOBALS['strReminderDate'] = "Your Reminder Date";
$GLOBALS['strReminderNote'] = "Your Reminder Note";
$GLOBALS['strMailNextRemindDate'] = "Your Next Remind Date";
$GLOBALS['strMailReminderSent'] = "Reminders Sent";

$GLOBALS['strRemindTypes'] = array(1 => "Reminder", "Meeting", "Birth Day", "Anniversary", "Dinner", "Activity", "Payment", "Other");
$GLOBALS['strRemindRepeates'] = array(1 => "One Time", "Every Day", "Every Week", "Every Month", "Every Year");
$GLOBALS['strRemindDays'] = array(1 => "1 Day", "2 Days", "3 Days", "7 Days");

$GLOBALS['strForgotPass'] = "Forgot Your Password?";
$GLOBALS['strSendMyPassword'] = "Send My Password";
$GLOBALS['strJSEmail'] = "Check Your E-Mail!";
$GLOBALS['strForgotPassEmailSubj'] = "Your Password";
$GLOBALS['strForgotPassEmailBody'] = "Hi {name}\n\nHere is your password:\n\n";
$GLOBALS['strForgotPassEmailOk'] = "Your password sent to your email address.";
$GLOBALS['strForgotPassEmailError'] = "We have no email address such as you submit. Please check your e-mail address.";
?>