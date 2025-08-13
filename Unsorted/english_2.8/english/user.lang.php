<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.8                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
$CharSet ='iso-8859-1'; // Language ISO Char Set code for users pages
$htmldir ='ltr'; /* ltr = Left to Right , rtl = Right to Left
		    text direction attribute is used to declare the direction 
		    that the text should run, either left to right (default) or right to left */

// Create, Preview and View Page
$MsgImage ='Image';
$MsgYourTitle ='Card Title';
$MsgMessage ='Message';
$MsgMessageHere ='- Your message will appear here -'; // Sample message that will apper if postcard use a template
$MsgFont ='Font';
$MsgNoFontFace ='No Font Face';
$MsgFontSizeSmall ='Small Type';
$MsgFontSizeMedium ='Medium Type';
$MsgFontSizeLarge ='Large Type';
$MsgFontSizeXLarge ='X-Large Type';
$MsgFontColorBlack ='Black';
$MsgFontColorWhite ='White';
$MsgSignature ='Signature';
$MsgRecpName ='Recipient name';
$MsgRecpEmail ='Recipient email';
$MsgAddRecp ='Add Recipients';
$MsgTotalRecp ='Total recipients';
$MsgPlay ='PLAY';
$MsgYourName ='Your name';
$MsgYourEmail ='Your email';
$MsgChoosePoem ='Select a poem';
$MsgView ='View';
$MsgChooseLayout ='Choose a Card Layout';
$MsgChooseDate ='Select Delivery Date?';
$MsgChooseDateImmediate ='Immediate';
$MsgDateFormat ='Choose current day, the date format is DD/MM/YYYY, to send your postcard now.';
$MsgChooseStamp ='Choose Stamp';
$MsgPostColor ='Card background color';
$MsgPageBackground ='Wallpaper';
$MsgNone ='None';
$MsgMusic ='Music';
$MsgPreviewButton ='Preview before sending';
$MsgNotify ='Notify me by email when the recipient reads this greeting.';
$MsgYes  ='Yes';
$MsgNo  ='No';
$MsgNoFlash ='You need the Flash player plug-in to see the Flash version of the postcard.';
$MsgClickHereToGet ='Click here to get it!';
$MsgHelp ='Help!';
$MsgCloseWindow ='Close Window';
$MsgPrintable ='Printable Version';

$MsgCreateCard ='Create postcard';


$MsgDateFormatDMY ='Day - Month - Year';
$MsgDateFormatMDY ='Month - Day - Year';

// Error Messages
$MsgActiveJS ='Please activate javascript!';
$MsgErrorMessage ='You must write a message for your postcard.';
$MsgErrorRecpName ='You must enter the recipient name.';
$MsgErrorRecpEmail ='You must enter the recipients e-mail address.';
$MsgErrorRecpEmail2 ='Recipients <B>email address</B> is invalid.';
$MsgErrorSenderName ='You must enter your name.';
$MsgErrorSenderEmail ='You must enter your email address.';
$MsgErrorSenderEmail2 ='Your <B>email address</B> is invalid.';
$MsgErrorNotFoundTxt ='Sorry, no postcard matches your postcard number. You may have mistyped the postcard ID, or your postcard may be too old and has already been erased from the system.';
$MsgErrorNoCardsEvents ='Sorry, there isnt cards about this event in the database.';
$MsgErrorInvalidePageNumber ='You have specified an invalid page number.';
$MsgErrorNoCardsinDB ='Sorry, there are no cards in the database.';

$MsgInvalidePageNumber ='You have specified an invalid page number';

$MsgBackEditButton ='Back to Edit';
$MsgSendButton ='Send Postcard!';

$MsgSendTo ='Send a postcard to';
$MsgClickHere ='click here';
$MsgAvoidDuplicat ='Click only once to avoid duplicates!';

// Info Windows
$MsgWinvCode ='vCode';
$MsgWinTextCode ='Text Code';
$MsgSomeText ='some text';
$MsgWinEmoticons ='Emoticons';
$MsgWinEmoticonsNote ='All characters are uppercased (O and P)!';
$MsgWinEmoticonsNoteFotter ='<B>If</B> you do NOT want the graphic to appear, but still want to use the original emoticons you will have to exclude the nose.';
$MsgWinBackground ='Wallpaper Image';
$MsgWinStamp ='Stamp Image';
$MsgWinColors ='Colors';
$MsgWinMusic ='Music';
$MsgWinMusicNote ='Choose an option.';
$MsgWinMusicNote2 ='Give the sound a few seconds to download to your PC';
$MsgWinPoem ='Poem';
$MsgWinPoemNote ='Choose a poem.';
$MsgWinNotify ='Do you want to receive an email notification when your postcard is viewed by the recipient?';
$MsgWinNotifyTitle ='Notify by email';
$MsgWinFonts ='Fonts';
$MsgWinFontsNote ='If you want to use this option, <FONT COLOR=red>please be aware</FONT> that not everyone will need to have these exact fonts installed on their computers. If not, they will see the default fonts, usually Times and Arial or Helvetica.'; 
$MsgWinName ='Name';
$MsgWinSample ='Sample';
$MsgWinSampleString ='abcdefghijklmnopqrstuvwxyz';

// Message in confirmation page
$MsgSendAnotherCard ='Send Another Virtual Card';

// Top X gallery
$MsgTop ='Top';

// Category Browser Pages
$MsgNext ='Next';
$MsgPrevious ='Prev';
$MsgBackCatMain ='Back To Category Main Page';
$MsgPageOf ='of'; // page xx OF yy
$MsgPage ='Page'; // PAGE xx of yy

$MsgCategories ='Categories';
$MsgCategory ='Category';
$MsgPostcards ='Postcards';
$MsgCards ='Cards';

// Back Link Messages
$MsgBack ='Back';
$MsgBackButton ='Back to Previous Page';
$MsgBacktoSection ='Back to previous section';

// Links
$MsgHome ='Home';
$MsgGoTo ='Go To';

// File Upload
$MsgUploadYourOwnFileTitle ='Use your Own Pic';
$MsgUploadYourOwnFileInfo ='Create a postcard using your own picture';
$MsgErrorFileExtension ='File extension not allowed. It must be .gif, .jpeg, .jpg or .swf!';
$MsgFileBiggerThan ='File size is bigger than'; // File size is bigger than XX Kilobytes
$MsgFileMaxSizeAllowed ='The max size of file allowed to be uploaded is'; // The max size of file is XX Kilobytes
$MsgFileAllowed ='You can upload your own image (.gif, .jpg) or flash animation (.swf) file to create a custom postcard. Select your file and click on the buttom.';
$MsgFileUploadNotAllowed ='File Uploading system is disabled in this site! Sorry';
$MsgFileSend ='Send File!';
$MsgFileSelect ='Select your file';
$MsgFileUseFile ='Create postcard';

$MsgCalendarMonth ='Month';
$MsgCalendarDayBegin ='Begin day';
$MsgCalendarDayEnd ='Last day';
$MsgCalendarEventName ='Event Name';
$MsgCalendar ='Calendar';
$MsgMonthNames = array('January','February','March','April','May','June','July','August','September','October','November','December');

/* ######################## added version 1.2 ######################## */
$MsgOptionsHelp ='Options and Help!';
$MsgTopCardInCat ='Top postcards in category';
$MsgCopyWant ='Do you want a copy of this card mailed to you?';
$MsgHome ='Home';

$MsgSearch_noresults ='Your search returned no results. Try other keyword(s).';
$MsgSearch_returned ='Your search returned'; // Your search returned XX results
$MsgSearch_results ='results'; // Your search returned XX results
$MsgSearch_relevance ='Listing them in order of relevance';
$MsgSearch_button ='Search postcard';

// address book
$MsgABook_tit_generaltitle ='My Address Book';
$MsgABook_tit_login ='My Address Book: Login';
$MsgABook_tit_editprofile ='Edit Personal Profile';
$MsgABook_tit_forgotpword ='Forgot Your Password?';
$MsgABook_tit_createabook ='Create Address Book';
$MsgABook_tit_addrecord ='Add E-mail Address';
$MsgABook_tit_editrecord ='Edit E-mail Address';
$MsgABook_tit_deleterecord ='Delete E-mail Address?';
$MsgABook_tit_updaterecord ='Update E-mail Address';
$MsgABook_tit_help ='My Address Book: Help';
$MsgABook_tit_error ='Error!';
$MsgABook_tit_cleancookie ='Cookies Cleaned!';
$MsgABook_email ='E-mail Address';
$MsgABook_realname ='Real Name';
$MsgABook_name ='Name';
$MsgABook_password ='Password';
$MsgABook_username ='Username';

$MsgABook_error ='One or more form fields are empty.<BR><BR> Please go back and complete the form fields before submit.';
$MsgABook_error_username ='The username is already taked.<br><br>Please go back and choose another username.';
$MsgABook_error_invalidlogin ='Invalid username or password.';
$MsgABook_error_emailformate ='Invalid e-mail address formate.<br><br>Please, back and check the e-mail address.';
$MsgABook_error_invalidloginnote='There is a error. Go back to make corrections and try again. Click New User to create a new address book.';
$MsgABook_helppassword ='Help! Retrieve your forgot password!';
$MsgABook_cleancookie ='Clean the username/password cookie info from computer!';
$MsgABook_cleancookie_note ='The username and password remember info was removed from your computer!';
$MsgABook_pwdremeber ='Remember my login/password';
$MsgABook_forgotpword_note ='Enter your username below and click Send to have your password sent to the email address that is stored in your personal address book. Click Cancel to go back to the login page.';
$MsgABook_forgotpword_note2 ='Enter your username/password to login into your address book. If you are a new user, click <b>New User</b> to create a new address book.';
$MsgABook_create_note ='Privacy Policy: The information you enter below is stored on our web server and only will be used for your private use to insert the infos into postcards you send from our site.';
$MsgABook_profile_note ='Make any modification and click <B>Save</B> to update your personal profile information.';
$MsgABook_topnote ='Select multiple contacts, holding down \'Ctrl\' while clicking';
$MsgABook_bottonnote ='Note: Remember to logout from your address book when you finished send postcards to protect your personal infos.';
$MsgABook_note1 ='You Address Book is close. Your address book can only write information to the window from which you opened it. Your address book will be closed.';

$MsgABook_help_add ='Adding a new email address: If you want add a new email address to your address book. Click in this option.';
$MsgABook_help_edit ='Editing a email address: Select only one record main page and click Edit button.';
$MsgABook_help_delete ='Deleting a email address: Select one record and click.';
$MsgABook_help_help ='Help page: You are here :)';
$MsgABook_help_logout ='Log-out from the address book to avoid someone see your personal and friends info.';
$MsgABook_help_close ='Close your address book window.';
$MsgABook_help_insert ='Insert the selected email address from address book to postcard.';
$MsgABook_help_profile ='Update your personal profile in your address book.';

$MsgReferFriend ='Refer this site to a Friend';
$MsgReferFriend_friendname ='Friend name';
$MsgReferFriend_friendemail ='Friend e-mail';
$MsgReferFriend_thanks ='Thank you';
$MsgReferFriend_end ='Thank you for refer this site to';
$MsgReferFriend_custommessage ='Add a custom message';
$MsgReferFriend_error ='One or more fields in the form were left empty.<BR><BR> Please fill in all of the requested information.';
$MsgReferFriend_error_emailformate ='Invalid e-mail formate.<br><br>Please go back and check the e-mail address.';

$MsgNewsletter_join ='I would like to join mailing list';

$Msg_error_emptyfield ='field is empty';

$Msg_label_username ='User Name';
$Msg_label_password ='Password';
$Msg_label_realname ='Real Name';
$Msg_label_email ='E-mail Address';
$Msg_label_addressbook ='Address Book';

$Msg_label_add ='Add';
$Msg_label_close ='Close';
$Msg_label_delete ='Delete';
$Msg_label_done ='Done';
$Msg_label_edit ='Edit';
$Msg_label_finish ='Finish';
$Msg_label_help ='Help';
$Msg_label_login ='Login';
$Msg_label_logout ='Logout';
$Msg_label_open ='Open';
$Msg_label_update ='Update';
$Msg_label_samplee ='Sample';
$Msg_label_image ='Image';
$Msg_label_view ='View';

/* ######################## added version 1.3 ######################## */
$MsgSubcategory ='Sub category';
$MsgRandomCards ='Random Cards';

/* ######################## added version 1.6 ######################## */
// updated!!!!
$MsgABook_bottonnote2 ='<font color=red><b>Attention:</b> To select multiple recipients use keyboard SHIFT/CTRL to choose more recipients</font>.';

/* ######################## added version 2.0 ######################## */
$Msg_rate ='rate this card';
$Msg_button_rate ='rate it!';

/* ######################## added version 2.2 ######################## */
$MsgABook_password2 ='Retype Password';
$MsgABook_error2 ='The 2 passwords don´t match. Back and try again.';

/* ######################## added version 2.3 ######################## */
$MsgABook_helppage ='<p><b>What is the My Address Book? </b></p><p>The My Address Book is a tool that was designed to make it easier for you to create and send cards. You can store names, email addresses all in one place. You can also quickly address your cards. Your Address Book is simple to use and incorporates many helpful features. </p><p><b>How do I add names and emails addresses to my postcard using my Address Book? </b></p><p>First select the number of recipients you want use and then go to your List, simply select on the name and then click \'Insert emails into card\'. The name and email address of your recipient will be added to your card. If you want select multiple contacts, jsut holding down \'Ctrl\' while clicking the names. These names will be added to your card if there is the correct number recipients fields. </p>';


?>