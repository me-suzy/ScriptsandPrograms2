<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
// language file: English  2.0

define ("YES","yes");
define ("NO","no");
define ("WAITING","waiting"); // as in images waiting for approval
define ("POWEREDBY","Powered by"); // as in Powered By Image Vote in copyright notice

function langerrors(){
global $admin;
define ("NOIMAGES","No images in database.  Add some images and have the moderator approve them.");
define ("NOACCT","Account does not exist for that username");
define ("ACCTNOMAIL","Username exists, but no e-mail address is associated with this account.  Please send an e-mail to $admin and request your password.");
}

function langindex(){
define ("VIEWALL","View All");
define ("DISPLAY","Display:");
define ("YOURVOTE","Your Vote:");
define ("OVERALL","Overall:");
define ("VOTES","Votes:");
define ("NOCOMMENTS","No comments have been posted about this picture...");
define ("POSTCOMMENT","Post A Public Comment");
define ("VIEWERCOMMENTS","Viewer comments");
define ("BY","By");
define ("RATING","Rating");
define ("REMOVE","remove");
define ("VIEWALLCOMS","View All Comments");
define ("LOGGEDOUT","You have been logged out.");
define ("LOGGEDIN","Logged in:");
define ("YOURACCT","Your Account");
define ("LOGOUT","Logout");
define ("SUBMITPIC","Submit your picture");
define ("LOGIN","Member Login");
define ("ADMINLOGGED","Admin logged in");
define ("ONLY","only"); // for category pulldown (ie: women only)
}

function langlogin() {
define ("SENDMSGTO","Send a message to");
define ("LOGINTOUSE","Log In To Use Your Account");
define ("ACCOUNTID","Account ID:");
define ("PASSWORD","Password:");
define ("LOGINNOW","Login");
define ("CLOSEWINDOW","Close this window");
define ("ANDJOIN","and submit your picture to join this site.");
define ("INVALIDUSER","Invalid user name");
define ("INVALIDPASS","Invalid Password");
define ("NOWLOGGEDIN","You are now logged in.");
define ("WELCOMEBACK","Welcome back");  // As in "Welcome Back Username!"
define ("LOGGINGIN","Logging in. Please wait...");
define ("DONTHAVEACCT","Don't have an account?");
}
function langlostpass() {
global $sitename,$username,$pw,$siteurl,$userphp,$name,$email,$subject,$newmail;
define ("LOSTPASS","Lost Password");
define ("ENTEREMAIL","Enter Your E-Mail Address:");
define ("ORUSERNAME","Or Username:");
define ("BEENEMAILED","Your password has been e-mailed to you.");
$subject = "Your $sitename Password";
$newmail = "Your username is $username\n";
$newmail .= "Your password is $pw\n\n";
$newmail .= "Check your stats and manage your account at:\n";
$newmail .= "$siteurl$userphp\n";
}

function languser() {
define ("CANTCHANGE","Cannot change URL of uploaded image.  Use remove image.");
define ("MAXIMGS","You may only have five images active at a time.  Please remove one if you would like to add another.");
define ("RETURNTOMEM","Return To Members Area");
define ("RETURNHOME","Return To Home Page");
define ("PRIVMSG","Private Messages:");
define ("MEMBERSAREAFOR","Members Area For");
define ("EMAIL","E-mail:");
define ("FROM","From");
define ("HOMEPAGE","Homepage (if any):");
define ("ABOUTYOU","Something about you:");
define ("STATUS","Status"); // message status (ie: new, read, deleted)
define ("MSGDATE","Date");
define ("NOMSG","You have no new messages");
define ("REFRESHMAIL","Refresh mailbox");
define ("NOCURPIC","You have no current pictures.  Please add a picture.");
define ("DESCRIBE","Describe this photo:");
define ("MODNOTE","Note: any modification of this image will set the status to inactive until moderator review.");
define ("UPTOFIVE","You May Have Up To Five Pictures On Your Account");
define ("ADDANOTHER","Add Another Picture");
define ("ORUPLOAD","Or Upload A Picture:");
define ("SAMPLEDES","Just me at home");  // sample description
define ("WHATRATE","What would you rate this photo?");
define ("ADDPIC","Add This Picture");
define ("ORGOVOTE","Or click here to return to vote on other photos");
// next two items new for 1.6.0
define ("NOTIFYPRIV","Send notification email when another user sends a private message");
define ("NOTIFYPUB","Send notification email when public comment is posted on this image");
// next item new for 2.0.0
define ("DELMAIL","Delete All Mail");
define ("ACCTVALD","<h3>Your account has been activated.  Thank you</h3>");
}

function langsignup(){
define ("CREATEACCT","Create Your Account");
define ("SUBMITPIC","Submit Your Picture");
define ("ACCTINFO","Account Information");
define ("CHOOSEUSERNAME","Choose A Username:");
define ("CHOOSEPASSWORD","Choose A Password:");
define ("PLZSEL","Please select...");
define ("MAXCHAR","(20 character max.)");
define ("MUSTBEVALID","(must be valid)");
define ("AREYOU","Are You Submitting Your Picture Now?");
define ("PROFILEINFO","Profile Information");
define ("IMGINFO","Image Information");
define ("YOURRATE","Your Rating");
define ("ANYQS","Any other questions?");
define ("EMAILAT","E-mail us at:");
define ("WHATNOT","What pictures are not accepted?");
define ("WHATNOTA","Pictures are inapropriate for this site if they contain nudity, celebrities, jokes, URLs, or if the picture is not of a person.");
define ("WHATURL","What is my Picture URL?");
define ("WHATURLA","<p>Your URL is the location on the internet where your picture is stored. If you need to upload your picture somewhere, here are a two places you can do so: <a href=\"http://www.photopoint.com\">Photopoint</a> - <a href=\"http://www.facelink.com\">Facelink</a></p>");
define ("NOTCOMPAT","<p>The following free services are not compatable: Geocities, Angelfire, Yahoo </p>");
}

function langmod() {
define ("ENTERMOD","Click here to enter the moderators area");
define ("INVALIDMOD","Invalid moderator login");
define ("MODAREA","Moderators Area");
define ("VALPICS","Validate Pictures");
define ("IMGREJECTED","Image rejected (remains in database)");
define ("IMGREMOVED","Image deleted from database");
define ("IMGAPPROVED","Image approved and made active");
define ("INFOUPDATED","Info updated");
define ("INVALIDPAGE","Invalid Page Number");
define ("ENDOFIMGS","End of Images");
define ("STARTOVER","Back To First Image");
define ("GOVOTE","Go Vote");
define ("RESIZEIMG","Resize Image");
define ("SKIPIMG","Skip Image");
define ("REJECTIMG","Reject Image");
define ("APPROVEIMG","Approve Image");
define ("REMOVEIMG","Remove Image");
define ("UPDATEIMG","Update Image");
define ("UPDATEDETAILS","Update Details");
define ("DOIT","Do It"); // for submit button
define ("REASONREP","Reason Image Was Reported (if any):");
define ("IFLARGER","If Image Is Larger Than This Arrow, Use The Resize Option To Fit Image For Your Site");
define ("IMGID","Img ID:");
define ("USERNAME","Username:");
define ("CATEGORY","Category:");
define ("IMGURL","Image URL:");
define ("DESCRIPTION","Description:");
define ("CURSTAT","Current Status:");
}

function langprocess() {
global $uploadsize,$sitename,$siteurl,$userphp,$password,$username,$notifmail,$subjectmail;
define ("THANKU2","Thank you for joining and adding your picture to be rated.");
define ("HEREINFO","Here is your user information.");
define ("IMGEXISTS","That image is already in our database.  Please choose another.");
define ("USEREXISTS","That username already exists.  Please choose another.");
define ("NODESCRIP","There was no photo description.");
define ("NOCAT","No category was selected.");
define ("NOURL","No photo URL was entered.");
define ("ENTEREMAIL","Please enter a valid email address.");
define ("NOPASS","You must enter a password");
define ("NOUSERNAME","A username was not entered.");
define ("FILENOTSTORED","File could not be stored.");
define ("FILETOOBIG","File not supplied, or file too big.<BR>Maximum image size is $uploadsize k<br>");
define ("INVALIDIMG","Not a valid image upload.<br>Image must be in JPG, PNG or GIF format. Please try again.<BR>");
define ("PICSREV","All pictures are reviewed before being added to the $sitename site.");
define ("WELCOMETO","Welcome to $sitename!");

// new to 1.6
$subjectmail = "Your $sitename Registration";
$notifmail = "Congratulations! You have been successfully registered at $sitename!\n\n";
$notifmail .= "Here is your user information:\n";
$notifmail .= "Your username is $username\n";
$notifmail .= "Your password is $password\n";
$notifmail .= "Use it to access your members area, make changes or add/delete images.\n";
$notifmail .= "Check your stats and manage your account at:\n";
$notifmail .= "$siteurl$userphp\n\n";
$notifmail .= "Sincerely, $sitename administration\n";
}

function langmail() {
global $from,$fromuser,$body,$sitename,$siteurl,$userphp,$subjectmsg,$subjectcmmnt,$notifmsg,$notifcmmnt,$vcode,$username,$vcodemail,$vcodesubject;
define ("MSGSENT","Your message has been sent");
define ("CANVIEW","The recipient will be able to view the message when they next login to this site.");
define ("COMMENTPOSTED","Your comment has been posted");
define ("THANKYOU","Thank you $from");
define ("IMAGENO","Image #");
define ("SUBJECT","Subject");
define ("PLEASERATE","Please rate this picture");
define ("MESSAGE","Message:");
define ("NOTYOURS","Error, this e-mail does not belong to you or no longer exists");
define ("MSGDEL","Your message has been deleted");
define ("READMSG","Read message");
define ("MSGFROM","A message from $fromuser");
define ("RE","re:");  // for reply subjects (re: your email)
define ("REPLY","Reply:");
define ("SENDREPLY","Send Reply");
define ("DELMSG","Delete Message");
define ("SUBJECTREQ","You must enter a subject.");
define ("MSGREQ","You must enter a message.");
define ("NOMSGS","This user does not accept private messages.");
define ("MUSTLOGIN","You Must Log In To Use This Feature.");
define ("SENDMSGTO","Send a message to");
define ("POSTCOMON","Post a public comment on");

// next section (to curly brace) new for 1.6.0
$subjectmsg = "New Message Notification";
$notifmsg = "Hello!\n\n";
$notifmsg .= "You received a new message to your private box!\n";
$notifmsg .= "To read this and other messages log in to your $sitename account at: \n"; 
$notifmsg .= "$siteurl$userphp.\n\n"; 
$notifmsg .= "Sincerely, $sitename administration\n\n"; 
$notifmsg .= "P.S. You receive these notifications as you selected new message notify option at your account. You can change it at any time from your members area.\n";

$subjectcmmnt = "New Comment Notification";
$notifcmmnt = "Hello!\n\n";
$notifcmmnt .= "A new public comment to your image has been posted:\n";
$notifcmmnt .= "$body\n\n";
$notifcmmnt .= "To read other comments log in to your $sitename account at: \n"; 
$notifcmmnt .= "$siteurl$userphp.\n\n"; 
$notifcmmnt .= "Sincerely, $sitename administration\n\n"; 
$notifcmmnt .= "P.S. You receive these notifications as you selected new comments notify option at your account. You can change it at any time from your members area.\n";

// new for 2.0.0
define ("BADLANG","Your message contained words that are not allowed on this site");
define ("MSGNOTSENT","Your message has not been posted.");
define ("MUSTVAL","You must enter your validation code to activate your account.  This was e-mailed to you when you signed up.");
define ("RESENDVAL","Click here to have the activation e-mail resent to your e-mail address.");
define ("VALCODESENT","The validation code to activate you account has been sent to you by e-mail.  Please check your e-mail now to activate your account");
define ("VALCODE","Validation Code");

$vcodesubject .= "$sitename Validation Code";  // E-mail subject for validation e-mail
$vcodemail .= "Welcome to $sitename.  Your action is required to activate your account.\n\n";
$vcodemail .= "Your username is: USERNAME\n";
$vcodemail .= "Your validation code is: VCODE\n\n";
$vcodemail .= "Activate your account by visiting the following URL:\n";
$vcodemail .= "$siteurl$userphp?un=USERNAME&svc=VCODE&action=vm\n\n";
$vcodemail .= "\n";

define ("EMAILFRIEND","Send Picture to a Friend");
define ("FRIENDMSG1","Hello! \n\nI found a great photo that, I believe, will make you happy :) See it at ");
define ("FRIENDMSG2","\n\nBye!");
define ("FRIENDMSGBOTTOM","\n\nP.S. This message was sent to you from $sitename website ( $siteurl ). Come visit us to see and rate hundreds of user submitted photos!");
define ("FRIENDMSGTITLE","Great photo will make you happy :)");
define ("FRIENDSNAME","Friend's Name:");
define ("FRIENDSEMAIL","Friend's Email:");
define ("FROMNAME","Your Name:");
define ("ENTERFROMNAME","Please enter your name.");
define ("ENTERFRIENDSNAME","Please enter your friend's name.");
define ("ENTERFRIENDSEMAIL","Please enter a valid email address of your friend.");
define ("MOREFRIENDS","Send this image to another friend");
}


function langprofile(){
global $u;
define ("NOPROFILE","No profile available for this user.");
define ("NOPIC","No Picture");
define ("PROFILE","Profile for $u");
define ("SENDPRIV","Send a private message to $u");
define ("CATEGORY","Category");
define ("AGE","Age");
define ("MARITAL","Marital");
define ("ABOUTME","About Me");
define ("ABOUTIMG","About This Picture");
define ("REMOVE","Remove");
}

function langtop() {
global $top10votes;
define ("V","votes");
define ("TOP","The Top 10");
define ("BOTTOM","The Bottom 10");
define ("WITHVOTES","(With at least $top10votes votes)");
define ("CLICKIMG","Click on any picture to vote on it.");
define ("RETURNTO","Return to main page");
define ("ALSOVIEW","Also view:");

// new for 2.0.0
define ("NEXT","Next 10");
define ("PREVIOUS","Previous 10");
define ("LAST","The Last 10 Images");
define ("OF"," of ");
define ("ALL","All Categories");
}

function langreport(){
define ("COMRMV","Comment has been removed.");
define ("CONTIN","Click here to continue");
define ("RPTIMG","Report this image to a moderator.");
define ("IMGNO","Image Number");
define ("WHYRPT","Why are you are reporting this image?");
define ("THANKS","Thank you.");
define ("REPORTED","Thank you<br>for helping us keep this site clean of broken images.<br> Your request to remove this photo has been submitted and the photo will be reviewed by a moderator.");
}


function errormsg ($message) {
print "<html>\n";
print "<head>\n";
print "<title>Error</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"98%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"99%\" height=\"395\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"> \n";
print "<p>Error:<br>";
print $message;  // leave this line in to insert error message
print "</p><p>Please <a href=\"javascript:history.go(-1)\" onMouseOver=\"self.status=document.referrer;return true\">Go Back </a>to fix these errors on your form.</p>";
print "<p>Problems? E-mail $GLOBALS[admin] </p>";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;
}