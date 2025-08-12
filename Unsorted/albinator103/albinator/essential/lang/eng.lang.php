<?php

$byteUnits = array('bytes', 'KB', 'MB', 'GB');
$date_show = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

$strLoginWelcome		  = 'Thanks logging in %1, redirecting...';
$strLoginSysShut		  = 'System is currently Shutdown';
$strLoginError1		  = 'Invalid Username or Password.';
$strLoginError2		  = "Your Account is blocked by the adminstrator, contact at $Config_adminmail";
$strLoginError3		  = 'Your Account is not acctivated, follow instructions in the mail sent to you.';
$strLoginError4		  = 'Note: you need cookies enabled to login';
$strLoginError5		  = 'You have been Logged out';
$strLoginError6		  = "Your Account has expired, contact at $Config_adminmail";
$strDBIntegrateWelcome    = "Welcome to $Config_systemname. As, this is your first visit please fill the following form";
$strDBIntegrateSuccess    = 'you need to login to continue';
$strPhotoRandomName 	  = 'Random Photo';
$strPhotoRandomNone       = 'No Random photo found';
$strDBIntegrateUidError   = 'you need to register under different username.';

$strIndexWelcome   	  = 'Welcome';
$strIndexHome		  = 'home';
$strNotLogin		  = 'you are Not Logged in';
$strIndexAddpic		  = 'you have %1 album'; // e.g. you have 1 album
$strIndexBelow		  = 'below';
$strMusicNotice		  = 'Please wait till the song loads & plays...';
$strSelectAlbum	        = 'Select an album, which you want to %1'; // e.g. select an album, which you want to edit
$strSelectPhotoEcard      = 'Click on the photo you want to send';
$strBackAlbumSelect       = 'back to album select';
$strAllAlbumNotice        = 'All images will be deleted, confirm?';
$strFeedbackNotify        = 'Plese enter the type of feedback';
$strIndexAlbumNotify      = '* optional for private albums, as they are accessible using password only, else leave blank';
$strPhotoMoveNotify       = "You only have 1 album, you need <a href=index.php>create</a> more to move/copy";
$strAlbumPrivateNotice    = 'All the albums marked with Private need the password you had set, to view it. Therefore when you send the url to friends, also send the password along with it.';

$strInformPick	        = 'inform on picking';
$strAllowedTypes          = 'allowed types';
$strSendMultiple	        = 'send to multiple people';
$strFeedbackSent          = 'Your feedback has been sent.';
$strEcardNotice           = 'remember not to delete the photo you sent before the card is seen.';
$strEcardSubject	        = 'Special kind of eCard for you';
$strEcardContent1	        = "\nSending your photos as eCards is just another cool feature of %1,\n%2"; // e.g. %1,%2 = sitename
$strEcardContent2	        = "Hi,\n\nYou have just received a special kind of eCard from %1, this ecard is not just another free boring animation, its a special photo owned by %2, who then made a special photo eCard just for you...\n\nview the card at:\n%3\n\nThe card will be available only for next %4 days.\n%5";

$strProcessTime		  = 'It took %1 seconds to compelete the process'; // e.g. It took 20 seconds

$rating_names= array('', 'Ummm, sorry!', 'Not attractive', 'Not my type', 'well ok sorts', 'Average', 'Cute', 'Attractive', 'Beautiful', 'Very Beautiful', 'Gorgeous');
$strPicRate1 = 'you need to login to rate';
$strPicRate2 = 'Thanks, photo has been rated.';
$strPicRate3 = 'Sorry, you already rated this photo.';

// new added
$strCommentLn1  = 'add your comments';
$strCommentLn2  = 'Be the first one to write a comment for this photo.';
$strCommentLn3  = '(maximum %1 characters)'; // e.g. (maximum 12 characters)
$strCommentLn4  = 'you need to login to add comments';
$strCommentLn5  = 'No subject';
$strCommentLn6  = 'No comments';
$strCommentLn7  = 'Comments more than maximum character limit';
$strCommentLn8  = 'no name provided';
$strCommentLn9  = 'your comments have been added';
$strCommentLn10 = 'your comments will be checked and added later';
$strCommentLn11 = 'adminstrator has disabled dual comments for same photo';
$strCommentLn12 = 'moderated';
$strCommentLn13 = 'Moderate Comments';
$strCommentLn14 = 'Delete Comment ID';
$strCommentLn15 = 'Show all albums for comments';
$strCommentLn16 = 'Edit comment';
$strCommentLn17 = 'empty or = 0 means, anonymous comment writer';

$strAlbumCrErr1 = 'No Album name provided';
$strAlbumCrErr2 = 'Album exists';
$strAlbumCrErr3 = 'Album created';
$strAlbumCrErr4 = 'Album not owned by you';
$strAlbumCrErr5 = 'Album deleted';
$strAlbumCrErr6 = 'You have no albums';
$strAlbumCrErr7 = 'Album Name';
$strAlbumCrErr8 = 'Album not found';
$strAlbumCrErr9 = 'make public';
$strAlbumCrErr10 = 'make private';
$strAlbumCrErr11 = 'chg password'; // = change password
$strAlbumCrErr12 = 'delete album';
$strAlbumCrErr13 = 'edit photos';
$strAlbumCrErr14 = 'Album updated';
$strAlbumCrErr15 = 'Albums List';
$strAlbumCrErr16 = 'select album';
$strAlbumCrErr17 = 'No photos in album';
$strAlbumCrErr18 = 'No photo selected';
$strAlbumCrErr19 = 'photo not found';
$strAlbumCrErr20 = 'album not found';
$strAlbumCrErr21 = 'manipulate photos';
$strAlbumCrErr22 = 'Photo updated';
$strAlbumCrErr23 = 'Photo deleted';
$strAlbumCrErr24 = 'No user found';

$strEcardErr1    = 'No music selected';
$strEcardErr2    = 'send photo ecards';
$strEcardErr3    = 'Send date';
$strEcardErr4    = 'Recipient name';
$strEcardErr5    = 'Recipient email';

$strRemindErr1   = 'No event name';
$strRemindErr2   = 'Event exists';
$strRemindErr3   = 'Reminder deleted';

$strInvalidDate  = 'Invalid date';
// new added


// Menus
$strMenusAddMorePhotos = 'add more photos';
$strMenusAddphotos = 'Add photos';
$strMenusAddphoto  = 'add photo';

$strMenusMyAlbums  = 'My Albums';
$strMenusAddAlbum  = 'Add Album';
$strMenusMyprofile = 'my profile';
$strMenusChanges   = 'Make Changes';
$strMenusEcards	 = 'eCards';
$strMenusFeedback  = 'feedback';
$strMenusManipulate= 'manipulate';
$strMenusReminders = 'reminders';
$strMenusSettings  = 'settings';
$strMenusHelp	 = 'help';
$strMenusTell	 = 'tell a friend';
$strMenusTellb	 = 'Tell friends & family about';
$strMenusSignup    = 'signup';
$strMenusForgot    = 'forgot';
$strMenusLogout	 = 'Logout';

$strAdminOpen      = 'open a/c';
$strPage		 = 'Page';
$strSignup		 = 'signup';
$strDetail		 = 'details';
$strUsername	 = 'username';
$strAccess		 = 'access';
$strTotalInfo	 = 'You have %1 %2'; // e.g. You have 2 albums
$strSortBy		 = 'Sort by';
$strCreate         = 'create';
$strCreated        = 'created';
$strAll		 = 'all';
$strMore		 = 'more';
$strText		 = 'text';
$strColor		 = 'color';
$strBackground	 = 'background';
$strMissingField   = 'Missing field';
$strSend		 = 'send';
$strSent		 = 'sent';
$strRecipient	 = "Recipient";
$strTotal		 = 'Total';
$strThanks		 = 'Thanks';
$strMailUs		 = 'mail us';
$strMailStatus	 = 'mail status';
$strOf		 = 'of';
$strRed		 = 'Red';
$strGreen		 = 'Green';
$strBlue		 = 'Blue';
$strType      	 = 'type';
$strID		 = 'ID';
$strUser		 = 'User';
$strLogin		 = 'login';
$strOwner		 = 'Owner';
$strOrignal		 = 'Orignal';
$strThumbnail	 = 'Thumbnail';
$strLink		 = 'link';
$strSuggestion     = 'suggestion';
$strNone		 = 'none';
$strOrder		 = 'Order';
$strUsed		 = 'used';
$strCaption		 = 'caption';
$strMove		 = 'move';
$strCopy		 = 'copy';
$strDelete		 = 'delete';
$strDefault		 = 'default';
$strSubject		 = 'Subject';
$strGo		 = 'go';
$strInvalid		 = 'invalid';
$strFrom		 = 'from';
$strPreview		 = 'Preview';
$strProfile		 = 'Profile';
$strDate		 = 'date';
$strMonth		 = 'Month';
$strYear		 = 'Year';
$strBuySentence    = 'want more';
$strNoLimit        = 'no limit';
$strSelect		 = 'select';
$strSaved		 = 'Saved';
$strClose		 = 'close';
$strView		 = 'view';
$strListen		 = 'Listen';
$strMusic		 = 'music';
$strSelected	 = 'selected';
$strPhoto		 = 'photo';
$strApply		 = 'apply';
$strAttachment     = 'Attachment';
$strMake		 = 'make';
$strPuralS         = 's';
$strYour		 = 'Your';
$strTell		 = 'Tell';
$strAbout		 = 'about';
$strFriend		 = 'friend';
$strList		 = 'List';
$strIn		 = 'in';
$strYes		 = 'Yes';
$strNo		 = 'No';
$strAdd            = 'Add';
$strNew		 = 'new';
$strMessage        = 'message';
$strComment        = 'comment';
$strOtherWays      = 'other ways';
$strPassword       = 'password';
$strPrivate        = 'Private';
$strPublic		 = 'Public';
$strChange		 = 'change';
$strClear          = 'Clear';
$strBelow          = 'below';
$strNote           = 'Note';
$strAlbum          = 'Album';
$strSize		 = 'Size';
$strName           = 'Name';
$strEvent		 = 'Event';
$strEmail          = 'email';
$strAdded          = 'Added';
$strSearch		 = 'Search';
$strExists         = 'exists';
$strNo             = 'No';
$strYes            = 'Yes';
$strRetry          = 'retry';
$strBack		 = 'back';
$strRedirecting    = 'redirecting';
$strElse           = 'else';
$strDone		 = 'done';
$strAdmin	       = 'Adminstrator';
$strLessPass	 = 'Password should be of 6 in length and at max 15';
$strTo		 = 'to';
$strClickhere      = 'click here';
$strEdit		 = 'edit';
$strEditAll		 = 'edit all';
$strEditing        = 'Editing';
$strCategory	 = 'Category';
$strCategories     = 'Categories';
$strFound		 = 'found';
$strSubCategory	 = 'Sub Category';
$strData		 = 'data';
$strCrossLimit     = 'You have crossed the limit of %1 set by the admin, delete the old to add new.'; // e.g. %1 = 10 albums
$strNotOwned       = 'doesnt exists or you don\'t own it';
$strAllImageNotice = 'All images moved or deleted';
$strDelete		 = 'delete';
$strDeleted		 = 'deleted';
$strUpdate		 = 'update';
$strUpdated		 = 'updated';
$strConfirm        = 'confirm';
$strPrev		 = 'prev';
$strNext		 = 'next';
$strUpdated		 = 'updated';
$strDisplaying	 = 'Displaying';
$strShowFields     = 'show more fields';
$strAlbumDelConfirm= 'All your images will be deleted';
$strDelConfirm     = 'You are about to delete %1,<br>are you sure?'; // e.g. %1 = album
$strSorry          = 'Sorry';
$strError          = 'Error';
$strSuccess        = 'Success';
$strWarning        = 'Warning';
$strNotify         = 'Notify';
$strTellCmt1       = 'Tell about Your Albums';
$strTellCmt2       = 'view friends Album';
$strTellCmt3	 = 'Tell about %1 to friends'; // %1 = Album Name
$strMenusEdit      = array('', "$strChange $strCaption$strPuralS", "$strMove", "$strCopy", "$strDelete", "$strChange $strOrder");

$strIndexCreateHelp = '<span class="tn"><b>Simple Steps to create your own albums...</b></span><br><br><font color="#003366">
&gt; Add your album, name it...<span class=ts> or (<a href="#">click here</a>)</span><br>
&gt; Click on add photo on the topbar <span class=ts> or (<a href="upload.php">click here</a>)</span><br>
&gt; select album in which you want to add photo<br>
&gt; click on browse and simply select your photos<br>
&gt; add a optional description / message with it<br>
&gt; finally, click on "add" on the bottom of the page &amp; sit back and enjoy your album<br>
</font></span>';
$strRegisterName1  = 'Real name';
$strRegisterName2  = 'email';
$strRegisterName3  = 'country';
$strRegisterName4  = 'username';
$strRegisterName5  = 'mail options';
$strRegisterName5a = 'email details';
$strRegisterName5b = 'send confirmation';
$strRegisterName6  = 'Language';
$strRegisterName6b = 're-type password';
$strRegisterName7 = 'gender';
$strRegisterName7a= 'male';
$strRegisterName7b= 'female';

$strEdayRSub1   = 'Reminder';
$strEdayRSub2   = 'A day earlier';
$strEdayRMail1  = "Hi %1,\n\nThis is your%2 reminder mail for Event:\n%3 on %4\n\n%5\n\nYou can add or change your reminders anytime by logging into the site.\nThankyou for using %6 Reminders.\n\nYour suggestions are always welcome.";
$strAutoBlock1 = 'Blocked List';
$strAutoBlock2 = "Hi %1,\n\nTodays Warned blocked users list:\nThese accounts will be blocked after %2 days\n\n%3";
$strAutoBlock3 = "Hi %1,\n\nTodays blocked users list: \n\n%2";

$strAlbinatorBuyline = 'the next gen in eAlbums...';
$strAlbinatorBuy     = 'Website owner? <a href="http://www.albinator.com/product/" target=_blank class="nounderts">get albinator</a> for your site';

if(preg_match ("/(settings.php)|(register)|(dbintegrate)|(confirm.php)|(addac.php)|(unact.php)|(usrmngt.php)|(forgot.php)/i", $SCRIPT_NAME))
{
$strForgetMail1	 = "Your password has been changed.\n\n%1";
$strForgetMail2	 = "Hi,\n\n%1\nPlease change it asap.\n\n";
$strForgetMail3	 = 'You have been emailed the details';

$strRegisterError1  = 'Real Name not specified';
$strRegisterError1b = 'Real Name should be atleast 5 char long';
$strRegisterError2  = 'Invalid new email address';
$strRegisterError2b = 'Invalid email address';
$strRegisterError3  = 'Country Not Selected';
$strRegisterError4  = '2 new password don\'t match';
$strRegisterError5b = 'new password should be 6 - 15 char long';
$strRegisterError6  = 'email address exists with other account';
$strRegisterError6b = 'Current password doesn\'t match';
$strRegisterError7  = 'This username is not allowed';
$strRegisterError7b = 'Invalid username, can contain numbers and chars only';
$strRegisterError7c = 'Invalid Username, should be 4 - 15 char long';
$strRegisterError7d = 'Username exists';
$strRegisterError7e = 'Username doesn\'t exists';
$strRegisterError7f = 'You must agree to the Terms & Conditions to register an account';
$strRegisterError7g = "I have read & abide to the <a href={$dirpath}terms.html target=terms onclick='openwin()' class='noundertn'>terms & conditions</a>";
$strRegisterError7h = "By registering means you read & abide to the <a href={$dirpath}terms.html target=terms onclick='openwin()'>Terms & Conditions</a>";
$strRegisterError8  = 'Please specify your gender';

$strRegisterAdvice1 = '4 - 10 chars/digits only';
$strRegisterAdvice2 = '6 - 15 chars/digits only';
$strRegisterAdvice3 = '(needs to be correct, confirmation code will mailed here)';
$strRegisterAdvice4 = 'Your account has been setup, we have sent you a message to your email address. To activate your account, click the link included in the message.';

$strRegisterMail1a= 'Account Activated';
$strRegisterMail1b= 'Account Activation';
$strRegisterMail1 = 'Account Confirmation';

$strRegisterMail2 = "Hi,\n\nYou recently signed up for an account at %1,\nwhere you can create your own personalized photo albums. \n\nYou need to activate your account before you can use it \n\n%2 \n\nclick or copy paste the code in browser's address box to activate. \nThe account will be deleted after %3 days if not activated.\n%4";
$strRegisterMail3 = "Hi,\n Your account has been setup by the admin at %1,\n where you can create your own personalized photo albums. \n\n Your Details: \n username: %2 \n password: %3 \n\n you can login at %4/login.php\n\n%5";

$strConfirmError1 = 'Invalid info, please copy the data from the email properly.';
$strConfirmError2 = 'Your account is not present in the activation list, it may have been deleted because of time limit, <a href=register.php>register again</a>...';
$strConfirmError3 = 'Invalid info, please copy the data from the email properly.';
$strConfirmError4 = 'Your account has been successfully activated, you can';
}

if(preg_match ("/(settings.php)|(register)|(dbintegrate)/i", $SCRIPT_NAME))
{
$strSettingsSaving  = "updated settings, $strRedirecting";

$strSettingsName1  = 'Show albums on email search';
$strSettingsName1a = 'only public';
$strSettingsName1b = 'all';
$strSettingsName1c = 'none';
$strSettingsName2  = 'Give border images & thumbnails';
$strSettingsName2a = 'yes';
$strSettingsName2b = 'no';
$strSettingsName3  = 'photo limit';
$strSettingsName4  = 'album limit';
$strSettingsName5  = 'reminders limit';
$strSettingsName6  = 'space limit';
$strSettingsName7  = 'Password change (leave these empty if you wish not to change it)';
$strSettingsName7a = 'new password';
$strSettingsName7b = 're-type password';
$strSettingsName7c = 'To change settings please enter your current password';
$strSettingsName7d = 'current pass';
$strSettingsName8  = 'if you change your email address, your password will be changed automatically and will be sent on the new address for security purpose.';
$strSettingsName9  = 'Valid untill';
$strSettingsName10  = 'Log me in';
$strSettingsName10a = 'till i use';
$strSettingsName10b = '1 Day';
$strSettingsName10c = '1 Week';
$strSettingsName10d = '1 Month';
$strSettingsName10e = '1 Year';

$strSettingsChgAgain = 'change again';
$strSettingsSaved = 'Settings saved';
$strSettingsNotify= 'You changed your email address therefore you have been sent a mail with new password.<br>You have been logged out,';
$strSettingsChgMail  = "Hi %1,\n\nYou changed your email address, therefore for security purposes here is you new password. \npassword: %2\n\nPlease change it asap.\n\n";
}

else if(preg_match ("/tell.php/i", $SCRIPT_NAME))
{
$strTellError1    = 'Number of your friend names don\'t match no. of the emails';
$strTellError2    = 'Please specify a <b>Friends\'s Name</b>';
$strTellError3    = 'Your <b>Friend\'s Email Address</b> is in invalid format';
$strTellError4    = '<b>Email Address %1 in friends email list</b> is in invalid format'; // e.g. Email address 2
$strTellMultiple  = 'If you want to mail this album\'s link to multiple people just seperate the names & email by a comma (,). Don\'t give spaces between commas.';
$strTellSent      = 'Mails sent';
$strTellWrongPass = 'Wrong album password given';
$strTellWrongPassb= 'This Album is marked private, you need give the password for it.';
$strTellMailTemp1 = 'Following is the message %1 wrote for you'; // e.g. %1 = username
$strTellAlterMethod= "\nAlternate Method, visit:\n%1/show.php\n\nand put the following details when asked...\n\n%2: %3\n%4: %5\n%6\n";
$strTellAlterMethod2= "\nAlternate Method, visit:\n%1/show.php\n\nand put the following details when asked...\n\n%2: %3\n";
$strTellAdvertiseMsg = "\nTelling friends and family about albums is one of the cool features of %1...\n%2";
$strTellMail         = "Hi,\n\n%1 has an account with the %2,\nwhere you can create your own personalized photo albums.\n\nthe url to the album \"%3\" is at\n\n%4 %5\n\n%6\n%7\n%8";
$strTellAboutURsite  = "Hi,\n\n%1 has an account with the %2,\nwhere you can create your own personalized photo albums.\n\nThe site where everything is for free, mastered for eAlbums, why don't you give it a shot, \n\nheres the site address:\n%3\n\n%4\n%5";
}

else if(preg_match ("/remind/i", $SCRIPT_NAME))
{
$strReminderAdd          = 'Add a new Reminder';
$strReminderEventExample = 'e.g. Birthday, meeting';
$strReminderMessage      = 'Optional Message';
$strReminderMessageLength= '(maximum %1 chars)'; // e.g. maximum 12 characters
$strReminderMsgError     = 'Reminder Message has %1 characters, please reduce it to less than or equal to %2.'; // e.g. %1,%2 = 12,
$strReminderEveryYear    = 'every year';
$strReminderEveryMonth   = 'every month';
$strReminderWhen		 = 'When to remind';
$strReminderWhenOpt1	 = 'on the day';
$strReminderWhenOpt2	 = 'a day earlier';
$strReminderWhenOpt3	 = 'both days';
$strReminderList		 = '&lt; Your exisiting reminder list &gt;';
$strReminderList2		 = 'list of reminders';
$strReminderEverySym	 = 'EV';
$strReminderEveryInfo	 = 'Every (e.g. every year, month)';
$strReminderExpired	 = 'Reminder not found in database<br>possibly it has been expired';
$strReminderNo		 = 'You don\'t have any reminders setup, <a href=remind.php>setup now</a>';
$strReminderSameDate	 = 'Today\'s date is the same as of reminder date.';
$strReminderDayEarlier   = 'A day earlier reminder is of no use because todays date is a day earlier. Normal Reminder mail will be sent tommorow.<br><br>';
$strReminderAccess       = 'Sorry you don\'t have the privilege level to set monthly reminders, <a href="feedback.php">ask admin</a>';
}

else if(preg_match ("/upload/i", $SCRIPT_NAME))
{
$strUploadRelax    = '<font size=4 color=#006699>Sit back & Relax,<br>your photos are being added...</font><p><br>
The more photos you add at one go, the more time it will take, <b>please do not press anything till confirmation comes.</b>';

$strProfilePhotoAdd = 'Profile Photo Added';
$strUploadError1   = 'Photo %1 has file size more than allowed. '; // e.g. %1 = number
$strUploadError2   = 'Photo %1 has Invalid File Format. '; // e.g. %1 = number
$strUploadError3   = 'Photo %1, not added, Space Limit reached. '; // e.g. %1 = number
$strUploadError4   = 'Photo %1, not added, Photo Limit reached. '; // e.g. %1 = number
$strUploadError5   = 'Photo %1 has been added. '; // e.g. %1 = number
$strUploadError6   = '%1 photo(s) added sucessfully'; // e.g. %1 = number
$strUploadPosition = 'Put newest photo at the';
$strUploadPosition1= 'Start';
$strUploadPosition2= 'End';
$strUploadAdding   = 'Adding to Album';
$strUploadAdded    = 'Added Photos to Album';
$strUploadRulesAdd = '	<li><span class=tn>Maximum of a %1 size photo can be added</span></li>'; // e.g. %1 = number
$strUploadRules	 = 
	'<ul>
        <li> 
          <div align="left" class="tn"><span class="tn">A maximum space limit 
            for you is given (see in <a href=settings.php>settings</a>)</span></div>
        </li>
        %1
        <li><span class="tn">Thumbnails will be created automatically</span></li>
        <li><span class="tn">At the momment we allow only %2
          file formats. (.gif has been disabled due to copyright issues)</span></li>
      </ul>';
}

else if(preg_match ("/(manipulate.php)|(savman.php)/i", $SCRIPT_NAME))
{
$strManipulateNotice      = 'Choose from the following tools to manipulate your photo:<br><span class=ts><b>Note</b>: some options may take few seconds to give result, don\'t press anything and please wait for the results</span>';
$strManipulateSave	  = 'Save Changes';
$strSelectPhoteManipulate = 'Click on the photo you want to manipulate';
$strManipulateSaveLine	  = '<b>These changes can\'t be undone</b>, Are you sure you want to save changes?';
$strManipulateOpt1 = 'Add Noise';
$strManipulateOpt2 = 'Blur';
$strManipulateOpt3 = 'Sharpen';
$strManipulateOpt4 = 'Reduce Noise';
$strManipulateOpt5 = 'Shadow from a light source';
$strManipulateOpt6 = 'Border';
$strManipulateOpt7 = 'Contrast';
$strManipulateOpt8 = 'Gamma Correction';
$strManipulateOpt9 = 'Brightness Correction';
$strManipulateOpt10 = 'Other Filters';
$strManipulateError1 = 'Brightness Value should be between -100 & 100';
$strManipulateError2 = 'Saturation value should be between -100 & 100';
$strManipulateError3 = 'Hue value should be between -100 & 100';
$strManipulateNoAccess = 'Sorry, You don\'t have access to manipulate';
$strManipulateTerms    = '<b>Note</b>: you must read <a href=../terms2.html target=terms onclick="openwin()" >terms</a> before proceeding';
$strManipulateOpt8Notice = 'Values must not be negative and should be less 5';
$strManipulateOpt9Notice = 'Values can be negative but less 100 and are in percentage,<br>these are the new values assigned to the image';
}

else if(preg_match ("/(profile.php)/i", $SCRIPT_NAME))
{
$strProfileOpt1	  = 'Album Info.';
$strProfileOpt2	  = 'Show email in profile';
$strProfileOpt3	  = 'small headshot';
$strProfileWelcome  = '~ Enter your profile details ~';
$strProfileView     = 'view profile';
}

else if(preg_match ("/(show)|(search.php)|(albums.php)|(dlisting.php)/i", $SCRIPT_NAME))
{
$strReqPass1		= 'Plese enter your name';
$strReqPass2		= 'Invalid email address';
$strReqPass3		= 'enter your details for the Album owner to identify you';
$strReqPass4		= 'Password Request';
$strReqPass5		= "Hi %1,\n\nYou have just received Album Password Request for Album\n%2\n\nThe details of the user are:\nname: %3\nemail: %4\ncomments: %5\n\nIf you wish to give the password, you can email him/her on the give email address.\n\n";
$strReqPass6		= 'Private Album password needed to view the photos';
$strReqPass7		= 'enter password';
$strReqPass8		= 'This is a private album, please';

$strShow1		      = 'Album list';
$strShow2			= 'You can view your friends albums by either of the following ways';
$strShow3			= 'This user\'s Albums are currently blocked.';
$strShow4			= 'User has disabled public viewing of the albums';
$strShow5			= 'No Public Album for the user';
$strShow6			= 'Note: Private albums of the user are not listed here.';
$strShow7			= '(P): Private album, password needed to view';
$strShow8			= 'Search albums / photos using keywords';
$strShow9			= 'keywords';
$strShow10			= 'this category';
$strShow11			= 'Match exact';
$strShow12			= 'Match Partial';
$strShow13			= 'search again';
$strShow14			= 'show per page';
$strShow15			= 'seperate search keywords with spaces';
$strShow16              = '<b>Your search <i>%1</i>, did not match any %2.</b><p>
				    Suggestions:<br><ul>
					<li>Make sure all words are spelled correctly
					<li>Try different keywords
					<li>Try Paritial Matching
					<li>Try more general keywords</ul>';
$strShow17              = 'Views';
$strShow18              = 'Votes';
$strShow19              = 'Avg. Ratings';
$strShow20              = 'Considered';
$strShow21		      = 'photo not rated';
$strShow22		      = '--- rate now ---';
$strShow23		      = '% votes by gender'; // e.g. percentage votes by gender
$strShow24		      = 'recently rated by';

$strShowSlide1          = 'Stop';
$strShowSlide2          = 'Start';
$strShowSlide3          = 'Slide-Show';
$strShowSlide4          = 'ReStart Slide-Show';

$strShowError1		= 'Inadequate data';
$strShowError2		= 'Album not found';
$strShowError3		= 'No Photos in album,<br>view user\'s <a href="showlist.php?%1&dowhat=user">Albums List</a>';
$strShowError4		= 'Photo not found in album';
$strShowError5		= 'Full size of the Photo';
$strShowError6		= 'fullsize view';
$strShowError7		= 'This is a private album, please enter password';

$strShowAbuse		= '<div class=ts align=center>The photos/views expressed are entirely of the user, if you feel offended in any way or you find it has adult content, please <a href="%1">inform us</a></div>';

$strESHOWCmt1		= 'No card';
$strESHOWCmt2		= "$strShowError1, please copy exactly from your mail.";
$strESHOWCmt3		= 'or card has expired';
$strESHOWCmt4		= 'The user has deleted the image he/she used in the card. Sorry for the inconvience.';
$strESHOWCmt5		= 'No Photo';
$strESHOWCmt6		= 'eCard Notification';
$strESHOWCmt7           = "Hi %1,\n\nYour card sent to %2 has just been viewed.\n\nThankyou for using %3 eCards and we hope you will keep sending your ecards with us.\nYour suggestions are always welcome.";
$strESHOWCmt8		= 'Send your own free photo eCards now, <a href=register.php>signup</a>';
$strESHOWCmt9		= 'Have a nice day... :)';
$strESHOWCmt10		= 'Heres a greeting from our side,';
}

if(preg_match ("/(register)|(addac.php)|(settings.php)|(dbintegrate)|(usrmngt.php)/i", $SCRIPT_NAME))
{

$strCountryList = '
                <option value="Albania">Albania </option>
                <option value="Algeria">Algeria </option>
                <option value="American Samoa">American Samoa </option>
                <option value="Andorra">Andorra </option>
                <option value="Angola">Angola </option>
                <option value="Anguilla">Anguilla </option>
                <option value="Antarctica">Antarctica </option>
                <option value="Antigua and Barbuda">Antigua and Barbuda </option>
                <option value="Argentina">Argentina </option>
                <option value="Armenia">Armenia </option>
                <option value="Aruba">Aruba </option>
                <option value="Australia">Australia </option>
                <option value="Austria">Austria </option>
                <option value="Azerbaijan">Azerbaijan </option>
                <option value="Bahamas">Bahamas </option>
                <option value="Bahrain">Bahrain </option>
                <option value="Bangladesh">Bangladesh </option>
                <option value="Barbados">Barbados </option>
                <option value="Belarus">Belarus </option>
                <option value="Belgium">Belgium </option>
                <option value="Belize">Belize </option>
                <option value="Benin">Benin </option>
                <option value="Bermuda">Bermuda </option>
                <option value="Bhutan">Bhutan </option>
                <option value="Bolivia">Bolivia </option>
                <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                <option value="Botswana">Botswana </option>
                <option value="Bouvet Island">Bouvet Island </option>
                <option value="Brazil">Brazil </option>
                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                <option value="Brunei Darussalam">Brunei Darussalam </option>
                <option value="Bulgaria">Bulgaria </option>
                <option value="Burkina Faso">Burkina Faso </option>
                <option value="Burundi">Burundi </option>
                <option value="Cambodia">Cambodia </option>
                <option value="Cameroon">Cameroon </option>
                <option value="Canada">Canada </option>
                <option value="Cape Verde">Cape Verde </option>
                <option value="Cayman Islands">Cayman Islands </option>
                <option value="Chad">Chad </option>
                <option value="Chile">Chile </option>
                <option value="China">China </option>
                <option value="Christmas Island">Christmas Island </option>
                <option value="Colombia">Colombia </option>
                <option value="Comoros">Comoros </option>
                <option value="Congo">Congo </option>
                <option value="Cook Islands">Cook Islands </option>
                <option value="Costa Rica">Costa Rica </option>
                <option value="Cote Divoire">Cote Divoire </option>
                <option value="Cuba">Cuba </option>
                <option value="Cyprus">Cyprus </option>
                <option value="Czech Republic">Czech Republic </option>
                <option value="Denmark">Denmark </option>
                <option value="Djibouti">Djibouti </option>
                <option value="Dominica">Dominica </option>
                <option value="Dominican Republic">Dominican Republic </option>
                <option value="East Timor">East Timor </option>
                <option value="Ecuador">Ecuador </option>
                <option value="Egypt">Egypt </option>
                <option value="El Salvador">El Salvador </option>
                <option value="Equatorial Guinea">Equatorial Guinea </option>
                <option value="Eritrea">Eritrea </option>
                <option value="Estonia">Estonia </option>
                <option value="Ethiopia">Ethiopia </option>
                <option value="Faroe Islands">Faroe Islands </option>
                <option value="Fiji">Fiji </option>
                <option value="Finland">Finland </option>
                <option value="France">France </option>
                <option value="France, Metropolitan">France, Metropolitan </option>
                <option value="French Guiana">French Guiana </option>
                <option value="French Polynesia">French Polynesia </option>
                <option value="Gabon">Gabon </option>
                <option value="Gambia">Gambia </option>
                <option value="Georgia">Georgia </option>
                <option value="Germany">Germany </option>
                <option value="Ghana">Ghana </option>
                <option value="Gibraltar">Gibraltar </option>
                <option value="Greece">Greece </option>
                <option value="Greenland">Greenland </option>
                <option value="Grenada">Grenada </option>
                <option value="Guadeloupe">Guadeloupe </option>
                <option value="Guam">Guam </option>
                <option value="Guatemala">Guatemala </option>
                <option value="Guinea">Guinea </option>
                <option value="Guinea-Bissau">Guinea-Bissau </option>
                <option value="Guyana">Guyana </option>
                <option value="Haiti">Haiti </option>
                <option value="Honduras">Honduras </option>
                <option value="Hong Kong">Hong Kong </option>
                <option value="Hungary">Hungary </option>
                <option value="Iceland">Iceland </option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia </option>
                <option value="Iraq">Iraq </option>
                <option value="Ireland">Ireland </option>
                <option value="Israel">Israel </option>
                <option value="Italy">Italy </option>
                <option value="Jamaica">Jamaica </option>
                <option value="Japan">Japan </option>
                <option value="Jordan">Jordan </option>
                <option value="Kazakhstan">Kazakhstan </option>
                <option value="Kenya">Kenya </option>
                <option value="Kiribati">Kiribati </option>
                <option value="Korea, Republic of">Korea, Republic of </option>
                <option value="Kuwait">Kuwait </option>
                <option value="Kyrgyzstan">Kyrgyzstan </option>
                <option value="Latvia">Latvia </option>
                <option value="Lebanon">Lebanon </option>
                <option value="Lesotho">Lesotho </option>
                <option value="Liberia">Liberia </option>
                <option value="Liechtenstein">Liechtenstein </option>
                <option value="Lithuania">Lithuania </option>
                <option value="Luxembourg">Luxembourg </option>
                <option value="Macau">Macau </option>
                <option value="Madagascar">Madagascar </option>
                <option value="Malawi">Malawi </option>
                <option value="Malaysia">Malaysia </option>
                <option value="Maldives">Maldives </option>
                <option value="Mali">Mali </option>
                <option value="Malta">Malta </option>
                <option value="Marshall Islands">Marshall Islands </option>
                <option value="Martinique">Martinique </option>
                <option value="Mauritania">Mauritania </option>
                <option value="Mauritius">Mauritius </option>
                <option value="Mayotte">Mayotte </option>
                <option value="Mexico">Mexico </option>
                <option value="Monaco">Monaco </option>
                <option value="Mongolia">Mongolia </option>
                <option value="Montserrat">Montserrat </option>
                <option value="Morocco">Morocco </option>
                <option value="Mozambique">Mozambique </option>
                <option value="Myanmar">Myanmar </option>
                <option value="Namibia">Namibia </option>
                <option value="Nauru">Nauru </option>
                <option value="Nepal">Nepal </option>
                <option value="Netherlands">Netherlands </option>
                <option value="Netherlands Antilles">Netherlands Antilles </option>
                <option value="New Caledonia">New Caledonia </option>
                <option value="New Zealand">New Zealand </option>
                <option value="Nicaragua">Nicaragua </option>
                <option value="Niger">Niger </option>
                <option value="Nigeria">Nigeria </option>
                <option value="Niue">Niue </option>
                <option value="Norfolk Island">Norfolk Island </option>
                <option value="Norway">Norway </option>
                <option value="Oman">Oman </option>
                <option value="Pakistan">Pakistan </option>
                <option value="Palau">Palau </option>
                <option value="Panama">Panama </option>
                <option value="Papua New Guinea">Papua New Guinea </option>
                <option value="Paraguay">Paraguay </option>
                <option value="Peru">Peru </option>
                <option value="Philippines">Philippines </option>
                <option value="Pitcairn">Pitcairn </option>
                <option value="Poland">Poland </option>
                <option value="Portugal">Portugal </option>
                <option value="Puerto Rico">Puerto Rico </option>
                <option value="Qatar">Qatar </option>
                <option value="Reunion">Reunion </option>
                <option value="Romania">Romania </option>
                <option value="Russian Federation">Russian Federation </option>
                <option value="Rwanda">Rwanda </option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis </option>
                <option value="Saint Lucia">Saint Lucia </option>
                <option value="Samoa">Samoa </option>
                <option value="San Marino">San Marino </option>
                <option value="Sao Tome and Principe">Sao Tome and Principe </option>
                <option value="Saudi Arabia">Saudi Arabia </option>
                <option value="Senegal">Senegal </option>
                <option value="Seychelles">Seychelles </option>
                <option value="Sierra Leone">Sierra Leone </option>
                <option value="Singapore">Singapore </option>
                <option value="Slovenia">Slovenia </option>
                <option value="Solomon Islands">Solomon Islands </option>
                <option value="Somalia">Somalia </option>
                <option value="South Africa">South Africa </option>
                <option value="Spain">Spain </option>
                <option value="Sri Lanka">Sri Lanka </option>
                <option value="St. Helena">St. Helena </option>
                <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
                <option value="Sudan">Sudan </option>
                <option value="Suriname">Suriname </option>
                <option value="Swaziland">Swaziland </option>
                <option value="Sweden">Sweden </option>
                <option value="Switzerland">Switzerland </option>
                <option value="Syrian Arab Republic">Syrian Arab Republic </option>
                <option value="Taiwan">Taiwan </option>


                <option value="Tajikistan">Tajikistan </option>
                <option value="Thailand">Thailand </option>
                <option value="Togo">Togo </option>
                <option value="Tokelau">Tokelau </option>
                <option value="Tonga">Tonga </option>
                <option value="Trinidad and Tobago">Trinidad and Tobago </option>
                <option value="Tunisia">Tunisia </option>
                <option value="Turkey">Turkey </option>
                <option value="Turkmenistan">Turkmenistan </option>
                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                <option value="Tuvalu">Tuvalu </option>
                <option value="Uganda">Uganda </option>
                <option value="Ukraine">Ukraine </option>
                <option value="United Arab Emirates">United Arab Emirates </option>
                <option value="United Kingdom">United Kingdom </option>
                <option value="United States of America">United States of America</option>
                <option value="Uruguay">Uruguay </option>
                <option value="Uzbekistan">Uzbekistan </option>
                <option value="Vanuatu">Vanuatu </option>
                <option value="Venezuela">Venezuela </option>
                <option value="Viet Nam">Viet Nam </option>
                <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.) </option>
                <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                <option value="Western Sahara">Western Sahara </option>
                <option value="Yemen">Yemen </option>
                <option value="Yugoslavia">Yugoslavia </option>
                <option value="Zaire">Zaire </option>
                <option value="Zambia">Zambia </option>
                <option value="Zimbabwe">Zimbabwe </option>';
}

$var1 = "Recently Accessed";
$var2 = "Recent Day Setter";
$var3 = "Page Maker Limit<br><span class='ts'>how many entried to show in paged results</span>";
$var4 = "Display from %1 days back"; // e.g. Display from 2 days back

?>