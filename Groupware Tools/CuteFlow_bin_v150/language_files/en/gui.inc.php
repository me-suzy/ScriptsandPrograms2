<?php
$TITLE_1 = "CuteFlow";
$TITLE_2 = "Document circulation system";

$BTN_OK = "OK";
$BTN_CANCEL = "Cancel";
$BTN_NEXT = "Next >";
$BTN_BACK = "< Back";
$BTN_LOGIN = "Login";
$BTN_SAVE = "Send";

$BTN_ADD = "< Add";

//--- menu.php
$GROUP_CIRCULATION = "Circulations";
$GROUP_ADMINISTRATION = "Administration";

$MENU_TEMPLATE = "Document templates";
$MENU_FIELDS = "Fields";
$MENU_ARCHIVE = "Circulation archive";
$MENU_USERMNG = "User";
$MENU_CIRCULATION = "Circulations";
$MENU_MAILINGLIST = "Mailing list";

//--- showuser.php
$USER_MNGT_SHOWRANGE = "Show user _%From-_%To";
$USER_MNGT_SORTBY = "Sort by:";
$USER_MNGT_SORTBY_NAME = "Name";

$USER_MNGT_LASTNAME = "Lastname";
$USER_MNGT_FIRSTNAME = "Firstname";
$USER_MNGT_EMAIL = "E-Mail";
$USER_MNGT_SUBSTITUDE = "Substitute";
$USER_MNGT_ADMINACCESS = "Administrator";
$USER_MNGT_ASKDELETE = "Do really want to delete this user?";
$USER_MNGT_ADDUSER = "New user";

$USER_EDIT_FORM_HEADER = "User data";
$USER_EDIT_FIRSTNAME = "Firstname:";
$USER_EDIT_LASTNAME = "Lastname:";
$USER_EDIT_EMAIL = "E-Mail:";
$USER_EDIT_ACCESSLEVEL = "Accesslevel:";
$USER_EDIT_USERID = "User ID:";
$USER_EDIT_PWD = "Password:";
$USER_EDIT_PWDCHECK = "Password <br/>(re-typed):";
$USER_EDIT_SUBSTITUDE = "Substitute:";
$USER_EDIT_ACTION = "Save";

$USER_ACCESSLEVEL_ADMIN = "Administration";
$USER_ACCESSLEVEL_RECEIVER = "Receiver";
$USER_ACCESSLEVEL_READONLY = "Read-Only";

$USER_SELECT_FORM_HEADER = "Select user";
$USER_SELECT_NO_SELECT = "No user selected!";

$USER_TIP_DELETE = "Delete User";
$USER_TIP_DETAIL = "Edit User details";

$EDIT_NEW_ERROR_FIRSTNAME = "You must enter a Firstname";
$EDIT_NEW_ERROR_LASTNAME = "You must enter a Lastname";
$EDIT_NEW_ERROR_EMAIL = "Invalid e-mail address";
$EDIT_NEW_ERROR_PASSWORD1 = "You must enter a password";
$EDIT_NEW_ERROR_PASSWORD2 = "You must retype the password";
$EDIT_NEW_ERROR_PASSWORD3 = "The passwords didn't match";

//--- showcirculation.php
$CIRCULATION_MNGT_ADDCIRCULATION = "New circulation";
$CIRCULATION_MNGT_FILTER = "Filter:";
$CIRCULATION_MNGT_NAME = "Name";
$CIRCULATION_MNGT_CURRENT_SLOT = "Current station";
$CIRCULATION_MNGT_SENDING_DATE = "Sending date";
$CIRCULATION_MNGT_WORK_IN_PROCESS = "Days in process";
$CIRCULATION_MNGT_SHOWRANGE = "Show circulations _%From-_%To off _%Off";
$CIRCULATION_MNGT_ASKDELETE = "Do you really want to delete this circulation?";
$CIRCULATION_MNGT_CIRC_DONE = "Circulation complete";
$CIRCULATION_MNGT_CIRC_BREAK = "Circulation declined";
$CIRCULATION_MNGT_CIRC_STOP = "Circulation stopped";
$CIRCULATION_TIP_STOP = "Stop circulation";
$CIRCULATION_TIP_RESTART = "Restart circulation";
$CIRCULATION_TIP_DELETE = "Delete Circulation";
$CIRCULATION_TIP_DETAIL = "Show circulation details";
$CIRCULATION_TIP_ARCHIVE = "Archive circulation";
$CIRCULATION_TIP_UNARCHIVE = "Move circulation from archive to \"regular\" List";


//--- circulation_detail.php
$CIRCDETAIL_TEMPLATE_TYPE = "Type:";
$CIRCDETAIL_SENDER = "Sender:";
$CIRCDETAIL_SENDREV = "Revision (Date):";
$CIRCDETAIL_SENDDATE = "Date:";
$CIRCDETAIL_ATTACHMENT = "Attachments";
$CIRCDETAIL_HISTORY = "Cycle history";
$CIRCDETAIL_VALUES = "Entered data";
$CIRCDETAIL_RECEIVE = "Received at:";
$CIRCDETAIL_PROCESS_DURATION = "Days in process:";
$CIRCDETAIL_DAYS = "Day(s)";
$CIRCDETAIL_STATE_OK = "done";
$CIRCDETAIL_STATE_WAITING = "in process";
$CIRCDETAIL_STATE_DENIED = "declined";
$CIRCDETAIL_STATE_SKIPPED = "skipped";
$CIRCDETAIL_STATE_STOP = "stopped";
$CIRCDETAIL_STATE_SUBSTITUTE = "send to substitute";
$CIRCDETAIL_STATE = "Status:";
$CIRCDETAIL_STATION = "Station:";
$CIRCDETAIL_COMMANDS = "Actions:";
$CIRCDETAIL_DESCRIPTION = "Description:";

$CIRCDETAIL_TIP_SKIP = "Skip station";
$CIRCDETAIL_TIP_RETRY = "Resend email to station";

$CIRCULATION_EDIT_FORM_HEADER = "New circulation";
$CIRCULATION_EDIT_NAME = "Circulation name:";
$CIRCULATION_EDIT_MAILINGLIST = "Mailing list:";
$CIRCULATION_EDIT_ATTACHMENTS = "Attachments:";
$CIRCULATION_EDIT_ADDITIONAL_TEXT = "Description text:";
$CIRCULATION_EDIT_SUCCESS_MAIL = "Success message to sender if circulation is complete";
$CIRCULATION_EDIT_SUCCESS_ARCHIVE = "Archive successful circulation automatically";

$CIRCULATION_NEW_ERROR_NAME = "You must enter a name for the circulation!";
$CIRCULATION_NEW_ERROR_MAILINGLIST = "A mailing list must be selected!";

$CIRCULATION_DONE_MESSSAGE_SUCCESS = "The circulation was completed successfully";
$CIRCULATION_DONE_MESSSAGE_REJECT = "The circulation was rejected through a receiver";


//--- printbar.php
$PRINTBAR_PRINT = "Print";
$PRINTBAR_CLOSE = "Close";


//--- showfield.php
$FIELD_MNGT_ADDFIELD = "New Field";
$FIELD_MNGT_SHOWRANGE = "Show Field _%From-_%To off _%Off";
$FIELD_MNGT_ASKDELETE = "Do you really want to delete this field? \\nATTENTION: The field will be deleted in all circulations\\n(incl. the entered data for this field)";
$FIELD_TBL_HDR_NAME = "Name";
$FIELD_TBL_HDR_TYPE = "Fieldtype";
$FIELD_TBL_HDR_STDVALUE = "Standardvalue";
$FIELD_TBL_HDR_READONLY = "Read-only";

$FIELD_TYPE_TEXT = "Text";
$FIELD_TYPE_DOUBLE = "Decimal";
$FIELD_TYPE_BOOLEAN = "True/False";
$FIELD_TYPE_DATE = "Date";

$FIELD_TIP_DELETE = "Delete field";
$FIELD_TIP_DETAILS = "Edit field details";

//--- editfield.php
$FIELD_EDIT_HEADLINE = "Inputfield";
$FIELD_EDIT_NAME = "Fieldname:";
$FIELD_EDIT_TYPE = "Fieldtype:";
$FIELD_EDIT_STDVALUE = "Standardvalue:";
$FIELD_EDIT_READONLY = "Read-Only:";
$FIELD_NEW_ERROR_NAME = "You must enter a name for the field!";

//--- showtemplates
$TEMPLATE_MNGT_ADDTEMPLATE = "New Document template";
$TEMPLATE_MNGT_SHOWRANGE = "Show template _%From-_%To off _%Off";
$TEMPLATE_TIP_DETAILS = "Edit template";
$TEMPLATE_TIP_DELETE = "Delete template";
$TEMPLATE_MNGT_ASKDELETE = "Do you really want to delete this template? \\nATTENTION: All circulations that use this template will be deleted too\\n(incl. all entered data)";

$TEMPLATE_EDIT1_HEADER = "Templatedetails (Step 1/3)";
$TEMPLATE_EDIT1_NAME = "Name of the document template:";

$TEMPLATE_EDIT2_HEADER = "Slots of the document template (Step 2/3):";
$TEMPLATE_EDIT2_NEWSLOT = "New Slot";
$TEMPLATE_EDIT2_ASKDELETE = "Do you really want to delete this slot\\nATTENTION: Circulations that use this slot will be lose all data assigned to this slot!";
$TEMPLATE_EDIT2_HEADER_NAME = "Name";
$TEMPLATE_EDIT2_TIP_DELETE = "Delete slot";
$TEMPLATE_EDIT2_TIP_DETAIL = "Edit slot";
$TEMPLATE_EDIT2_TIP_UP = "Move slot upwards";
$TEMPLATE_EDIT2_TIP_DOWN = "Move slot downwards";

$TEMPLATE_EDIT3_HEADER = "Assigning fields to slots (Step 3/3)";
$TEMPLATE_EDIT3_ASSIGNED_FIELDS = "Assigned fields:";
$TEMPLATE_EDIT3_AVAILABLE_FIELDS = "Available fields:";
$TEMPLATE_EDIT3_NAME = "Name";
$TEMPLATE_EDIT3_POS = "Pos.";

$TEMPLATE_NEW_ERROR_NAME = "You must enter a name for the document template!";

//--- editslot.php
$SLOT_EDIT_HEADLINE = "Slotdetails";
$SLOT_EDIT_NAME = "Slotname:";
$SLOT_NEW_ERROR_NAME = "You must enter a name for the slot!";


//--- showmaillist.php
$MAILLIST_MNGT_ADDMAILLIST = "New mailing-list";
$MAILLIST_MNGT_SHOWRANGE = "Show mailing-list _%From-_%To off _%Off";
$MAILLIST_MNGT_NAME = "Name";
$MAILLIST_MNGT_ASKDELETE = "Do you really want to delete this list?";

$MAILLIST_EDIT_ERROR = "The selected mailing-list is currently in use!<br>Changes in this mailing-list will affect the circulations that uses this mailing-list.<br>In worst case the circulation process will not proceed successfully!";

$MAILLIST_EDIT_FORM_HEADER = "Mailing-list details";
$MAILLIST_EDIT_FORM_HEADER_STEP2 = "Assigning recipients to slots";
$MAILLIST_EDIT_FORM_TEMPLATE = "Document template:";
$MAILLIST_EDIT_FORM_SLOT = "Slot";

$MAILLIST_NEW_ERROR_NAME = "You must enter a name for the mailing-list!";
$MAILLIST_NEW_ERROR_TEMPLATE = "You must select a document template!";

$MAILINGLIST_SELECT_NO_SELECT = "No mailing-list selected!";
$MAILINGLIST_SELECT_FORM_HEADER = "Select mailing-list";

$MAILINGLIST_TIP_DELETE = "Delete mailing-list";
$MAILINGLIST_TIP_DETAILS = "Edit mailing-list";

$MAILINGLIST_EDIT_ATTACHED_USER = "Assigned user:";
$MAILINGLIST_EDIT_POS = "Pos.";
$MAILINGLIST_EDIT_NAME = "Name";
$MAILINGLIST_EDIT_AVAILABLE_USER = "Available user:";

$TEMPLATE_SELECT_NO_SELECT = "No document template selected!";
$TEMPLATE_SELECT_FORM_HEADER = "Select document template";

$LOGIN_FAILURE = "The login failed. Please check your user id and password.";
$LOGIN_ERROR_PASSWORD = "Please enter a valid password!";
$LOGIN_ERROR_USERID = "Please enter a valid user id!";

$MAIL_HEADER_PRE = "Circulation: ";
$MAIL_VALUES_HEADER = "Addition informations";

$MAIL_ENDACTION_DONE_SUCCESS = " - successfully completed";
$MAIL_ENDACTION_DONE_REJECTED = " - successfully completed";

$MAIL_CLOSE_WINDOW = "Close window";

$MAIL_CONTENT_ATTETION = "Attention!";
$MAIL_CONTENT_ATTETION_TEXT = "You have edited this circulation already and sent your data. The contents of this mail could't be changed. The following values shows the current status of the circulation.";
$MAIL_CONTENT_STOPPED_TEXT = "The circulation was stopped by another user. You can't change the values any more.";
$MAIL_CONTENT_SENT_ALREADY = "You have edited this circulation already and sent your data.";

$MAIL_CONTENT_RADIO_NACK = "I decline the content of this circulation!";
$MAIL_CONTENT_RADIO_ACK = "I accept the content of this circulation!";

$MAIL_CONTENT_PRINTVIEW = "Print-view";

$MAIL_ACK = "Your data was transfered successfully and the circulation document was sent to the next user in the list.<br><br>Please close the e-mail.";
$MAIL_NACK = "Your answer was saved.<br><br>Please close the e-mail.";

//--- login
$LOGIN_HEADLINE = "Login to document circulation system";
$LOGIN_USERID = "Username";
$LOGIN_PWD = "Password";
$LOGIN_LOGIN = "Login";

//--- restarting circulation
$CIRCULATION_RESTART_FORM_HEADER = "Restart the circulation";
?>