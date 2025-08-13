<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

$LANGUAGE['strHome'] = "Home";
$LANGUAGE['strBack'] = "Back";
$LANGUAGE['date_format'] = "Y-m-d";
$LANGUAGE['time_format'] = "H:i";
$LANGUAGE['strAdd'] = "Add";
$LANGUAGE['strUpdate'] = "Update";
$LANGUAGE['strDelete'] = "Delete";
$LANGUAGE['strName'] = "Name";
$LANGUAGE['strSurname'] = "Surname";
$LANGUAGE['strEmail'] = "Email";
$LANGUAGE['strUsername'] = "Username";
$LANGUAGE['strPassword'] = "Password";
$LANGUAGE['strSignup'] = "Sign Up";
$LANGUAGE['strLogin'] = "Login";
$LANGUAGE['strSubmit'] = "submit";
$LANGUAGE['strRegFree'] = "If you are not registered, click here to register <a href=\"register.php\"><b>freely</b></a>!";
$LANGUAGE['strJSUsername'] = "Enter your username!\\nYour username should be\\nmin 4, max 10 charecters";
$LANGUAGE['strJSPassword'] = "Enter your password!\\nYour password should be\\nmin 4, max 10 charecters";
$LANGUAGE['strErrorWronguser'] = "Wrong username/password!";
$LANGUAGE['strErrorTimeout'] = "Timeout. Please sign in again!";
$LANGUAGE['strErrorUnknown'] = "Unkown Error!";


# agenda_add.php
$LANGUAGE['strHaveNotes'] = "<b><u>P.S:</u></b> You have note(s) on the day(s) marked <font color=\"#FF0000\">*</font>";
$LANGUAGE['strAddReminder'] = "Add Reminder";
$LANGUAGE['strEditReminder'] = "Edit Reminder";
$LANGUAGE['str_At'] = "At";
$LANGUAGE['str_Oclock'] = "o'clock";
$LANGUAGE['strWriteNote'] = "Write your reminder note";
$LANGUAGE['strMaxNoteChars'] = "Max 125 chars";
$LANGUAGE['strThisReminder'] = "This reminder";
$LANGUAGE['strFromMyDate'] = "remind me";
$LANGUAGE['strMyThisReminder'] = "Just/Every";
$LANGUAGE['strError'] = "Error";
$LANGUAGE['strErrorWrongDate'] = "The date you selected is wrong!";
$LANGUAGE['strErrorOldDate'] = "The date you selected is old date!";
$LANGUAGE['strErrorLackDate'] = "The date you selected is lack!";
$LANGUAGE['strJSNoNote'] = "Please add some notes";
$LANGUAGE['strJSToomuchChars'] = "Max 125 chars";
$LANGUAGE['strSaveRemindOk'] = "Your reminder saved successfully!";
$LANGUAGE['strErrorSqlInsert'] = "An error occured while saving your data. Please inform this error to <a href=\"mailto:".$CFG->PROG_EMAIL."\">".$CFG->PROG_EMAIL."</a> address.";

# register.php
$LANGUAGE['strJSEnterName'] = "Enter your name!";
$LANGUAGE['strJSEnterSurname'] = "Enter surname!";
$LANGUAGE['strJSEnterEmail'] = "Enter your email address correctly!";
$LANGUAGE['strJSPasswordsNoMatch'] = "Your passwords doesn\'t match!";
$LANGUAGE['strRepeate'] = "Confirm";

$LANGUAGE['strRegisterOk'] = "<b>Congratulations!</b><p>You are registred successfully. <p>To login please click <a href=\"login.php\">here</a>.";
$LANGUAGE['strGoLocation'] = "To go where you come please click <a href=\"login.php?location=$location\">here</a>.";
$LANGUAGE['strExistMail'] = "The email address (//email//) you submitted has been found on our database. Pleae try to get your password by clicking here.";
$LANGUAGE['strExistUser'] = "The username you entered is registred with us. Please go back and try another username.";
$LANGUAGE['strWrongMail'] = "The email address (//email//) you submitted is wrong. Please go back and check it again.";
$LANGUAGE['strReminderUpdated'] = "Your reminder updated!";
$LANGUAGE['strReminderDeleted'] = "Your reminder deleted!";

$LANGUAGE['strMonthnames'] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$LANGUAGE['strWeekdays'] = array("Sun", "Mon", "Thu", "Wed", "Thr", "Fri", "Sat", "Sun");

$LANGUAGE['strGo'] = "Go";
$LANGUAGE['strLogout'] = "Logout";
$LANGUAGE['strPrevious'] = "Previous";
$LANGUAGE['strNext'] = "Next";
$LANGUAGE['strJSConfirm'] = "Are you sure to delete?";
$LANGUAGE['strSave'] = "Save";
$LANGUAGE['strYes'] = "Yes";
$LANGUAGE['strNo'] = "No";

$LANGUAGE['strReminderDate'] = "Your Reminder Date";
$LANGUAGE['strReminderNote'] = "Your Reminder Note";
$LANGUAGE['strMailNextRemindDate'] = "Your Next Remind Date";
$LANGUAGE['strMailReminderSent'] = "Reminders Sent";

$LANGUAGE['strRemindTypes'] = array(1 => "Reminder", "Meeting", "Birth Day", "Anniversary", "Dinner", "Activity", "Payment", "Other");
$LANGUAGE['strRemindRepeates'] = array(1 => "One Time", "Every Day", "Every Week", "Every Month", "Every Year");
$LANGUAGE['strRemindDays'] = array("On the Day Of", "1 Day Before", "2 Days Before", "3 Days Before", "7 Days Before");

$LANGUAGE['strForgotLoginInfo'] = "Forgot Login Info?";
$LANGUAGE['strSendMyPassword'] = "Send My Password";
$LANGUAGE['strJSEmail'] = "Check Your E-Mail!";
$LANGUAGE['strForgotPassEmailSubj'] = "Your Password";
$LANGUAGE['strForgotPassEmailBody'] = "Hi {name}\n\nHere is your password: {password}\n\nClick the link below to login:\n\n{link}\n\n" . $CFG->PROG_NAME . "\n" . $CFG->PROG_URL;
$LANGUAGE['strForgotPassEmailOk'] = "Your password has been sent to your email address.";
$LANGUAGE['strForgotPassEmailError'] = "We have no email address such as you submit. Please check your e-mail address.";

# Administrative LANGUAGE

$LANGUAGE['str_AdministrativeArea'] = $CFG->PROG_NAME . " Administrative Area";
$LANGUAGE['str_ListUsers'] = "List Users";
$LANGUAGE['str_ListReminders'] = "List Reminders";

$LANGUAGE['str_myAgendaUsers'] = $CFG->PROG_NAME . " Users";
$LANGUAGE['str_myAgendaUsersReminders'] = $CFG->PROG_NAME . " Users' Reminders";

$LANGUAGE['str_RegDate'] = "Registered";
$LANGUAGE['str_RegUsers'] = "You have <b>{TOTAL}</b> registered users.";
$LANGUAGE['str_RegReminders'] = "<b>{TOTAL}</b> reminder(s) in our database.";

$LANGUAGE['strEdit'] = "Edit";
$LANGUAGE['strDelete'] = "Delete";
$LANGUAGE['strAction'] = "Action";
$LANGUAGE['strOtherPages'] = "Other Pages";
$LANGUAGE['strPrevPage'] = "Previous Page";
$LANGUAGE['strNextPage'] = "Next Page";
$LANGUAGE['strRecordUpdated'] = "Record Updated";
$LANGUAGE['strRecordDeleted'] = "Record Deleted";
$LANGUAGE['strReminders'] = "Reminders";
$LANGUAGE['strType'] = "Type";
$LANGUAGE['strDate'] = "Date";
$LANGUAGE['strRepeat'] = "Repeat";
$LANGUAGE['strDuration'] = "Duration";
$LANGUAGE['strAdvance'] = "Advance";
$LANGUAGE['str_ReminderNote'] = "Reminder Note";
$LANGUAGE['str_ReminderAdded'] = "Reminder Added";
$LANGUAGE['str_UsersStats'] = "Users Stats";
$LANGUAGE['str_RemindersStats'] = "Reminder Stats";

$LANGUAGE['strLastAccess'] = "Last Access";

$LANGUAGE['strDelSelected'] = "Delete Selected Items";
$LANGUAGE['strSelectOne'] = "Please Select At Least One Item";
$LANGUAGE['strItemsDeleted'] = "{TOTAL} Items Deleted";
$LANGUAGE['strsendPassword'] = "Send His/Her Password";

$LANGUAGE['str_NoReminders'] = "There is no reminders";
$LANGUAGE['str_NoUsers'] = "There is no registred users";

$LANGUAGE['str_ChangeUser'] = "Change User/Pass";
$LANGUAGE['str_OldUsername'] = "Old Username";
$LANGUAGE['str_OldPass'] = "Old Password";
$LANGUAGE['str_NewUsername'] = "New Username";
$LANGUAGE['str_NewPass'] = "New Password";
$LANGUAGE['str_UserChanged'] = "Username/Password Changed";

$LANGUAGE['str_JSRequiredFields'] = "Please fill all required fields";
$LANGUAGE['str_Config'] = "Configuration";
$LANGUAGE['str_ConfigUpdated'] = "Configuration Updated";
$LANGUAGE['str_ConfigNotUpdated'] = "There is no work to be done!";
$LANGUAGE['str_UserPassInfo'] = "If you don't want to change your username or/and password, please leave blank their fields.";

$LANGUAGE['str_confirmRegistration'] = "Thanks!<p>One more step to complete your registration. Just check your email and complete registration";
$LANGUAGE['str_confirmEmailSubject'] = "myAgenda Registration";
$LANGUAGE['str_NoEmail'] = "We have no email like you entered.";
$LANGUAGE['str_PasswordSent'] = "Your password was sent to your email address";
$LANGUAGE['str_LimitedPasswordRequest'] = "Too many password request!";
$LANGUAGE['str_ForgotPwEmailSubject'] = "Your myAgenda Password";
$LANGUAGE['str_YourRemindersOnToday'] = "Todays' Reminder(s)";
$LANGUAGE['str_OK'] = "OK";

$LANGUAGE['strModifyInfo'] = "Update";
$LANGUAGE['strOldPassword'] = "Old Password";
$LANGUAGE['strJSOldPassword'] = "Check Old Password";
$LANGUAGE['strOldPasswordWrong'] = "Old Password is Wrong.";
$LANGUAGE['strForSecurityPass'] = "Enter your Old Password for Your Security";
$LANGUAGE['strUserInfoModified'] = "Updated";
$LANGUAGE['strNothingUpdated'] = "Nothing Updated";
?>