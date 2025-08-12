<?php /* ***** Orca Forum - English Language File ************* */

/* ***************************************************************
* Orca Forum v4.x
*  A simple threaded forum for a small community
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*
* If you translate this file into your native language, please
* send me a copy so I can include it in the forum package.  Your
* name will appear in the header of the file you translate :)
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("en_EN", "enc"));
$pageEncoding = 1;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$dateFormat = "%b %d, %Y  %X";  // see http://www.php.net/strftime


/* ***** Success/Error Messages ******************************* */
$lang['avatar1'] = "Avatar size limit: %1\$d x %1\$d pixels";
$lang['avatar2'] = "Could not read Avatar file";
$lang['avatar3'] = "Avatar URI not valid";

$lang['emaila'] = "Incorrect email format";

$lang['post1'] = "Post %d and all its replies have been deleted";
$lang['post2'] = "Post %d does not exist or has already been deleted";
$lang['post3'] = "No post ID selected for deletion";
$lang['post4'] = "Posts must include at least a subject or message";
$lang['post5'] = "You must wait 30 seconds between posts";
$lang['post6'] = "You have already posted this message";
$lang['post7'] = "Your message was posted";

$lang['get1'] = "Message number %d does not exist or has been deleted";

/* ***** Email Message **************************************** */
$lang['subject1'] = "Your message was replied to!";
$lang['email1'] = "%1\$s,

Your post entitled: \"%2\$s\" was replied to.
You are receiving this email as requested when the original message was posted on: %3\$s %4\$s

The reply is shown below:

%5\$s - Posted by: %6\$s, on: %7\$s %4\$s

%8\$s

____________________________________________________________
%9\$s
*** Replying to this message will not reach %6\$s ***";

$lang['subject2'] = "New forum post!";
$lang['email2'] = "A new thread has been started at your forum.

The message is as follows:

%5\$s - Posted by: %6\$s, on: %7\$s %4\$s

%8\$s

____________________________________________________________
%9\$s";


/* ***** Body ************************************************* */
$lang['parse1'] = "Quote";
$lang['parse2'] = "said";

$lang['panel1'] = "Search";
$lang['panel2'] = "Younger than:";
$lang['panel3'] = "Unmark";
$lang['panel4'] = "1 Hour";
$lang['panel5'] = "3 Hours";
$lang['panel6'] = "6 Hours";
$lang['panel7'] = "12 Hours";
$lang['panel8'] = "1 Day";
$lang['panel9'] = "3 Days";
$lang['panela'] = "1 Week";
$lang['panelb'] = "2 Weeks";
$lang['panelc'] = "1 Month";
$lang['paneld'] = "Mark";
$lang['panele'] = "Expand All";
$lang['panelf'] = "Collapse All";
$lang['panelg'] = "Forum Home";
$lang['panelh'] = "Refresh";
$lang['paneli'] = "Post Reply";
$lang['panelj'] = "Post New";

$lang['search'] = "Search results for <strong>%s</strong>";

$lang['welcome'] = "<h2>Welcome to the Orca Forum v4.x</h2>
      <p>Completely rebuilt from scratch, the Orca Forum v4.x is a great and seamless addition to any website.
         Total stylesheet control makes it easy for you to make this forum look exactly the way *you* want.</p>
      <p>If you're seeing this message, it means that the database was set up correctly and your forum is now open for business!
         Post a welcome message using the form below and let's start a community! :)</p>";

$lang['message1'] = "Currently browsing thread:";
$lang['message2'] = " - <em>PREVIEW</em>";
$lang['message3'] = "Send email to %s";
$lang['message4'] = "email";
$lang['message5'] = "Avatar";
$lang['message6'] = "Posted on:";
$lang['message7'] = "This message is a reply to:";
$lang['message8'] = "posted by <em>%s</em>.";
$lang['message9'] = "No Subject";
$lang['messagea'] = "No Text";
$lang['messageb'] = "Anonymous";

$lang['list1'] = "<em>Replies...</em>";
$lang['list2'] = "<em>No results</em>";
$lang['list3'] = "<em>No replies</em>";

$lang['time1'] = "All times listed in";

$lang['pagin1'] = "Previous page";
$lang['pagin2'] = "Previous";
$lang['pagin3'] = "Go to page %d";
$lang['pagin4'] = "Next page";
$lang['pagin5'] = "Next";

$lang['form1'] = "You must have at least a subject or message!";
$lang['form2'] = "There is no message text!  Post anyway?";
$lang['form3'] = "Post message without a name?";
$lang['form4'] = "Post message without a subject?";
$lang['form5'] = "Post Reply";
$lang['form6'] = "Post New Message";
$lang['form7'] = "Name";
$lang['form8'] = "Email";
$lang['form9'] = "Subject";
$lang['forma'] = "Send replies by email";
$lang['formb'] = "Message";
$lang['formc'] = "Quote";
$lang['formd'] = "Bold";
$lang['forme'] = "Italic";
$lang['formf'] = "Link";
$lang['formg'] = "Image";
$lang['formh'] = "Code";
$lang['formi'] = "Avatar";
$lang['formj'] = "Reset";
$lang['formk'] = "Remember your details";
$lang['forml'] = "Yes";
$lang['formm'] = "No";
$lang['formn'] = "Preview";
$lang['formo'] = "Post";


while (list($key, $value) = each($lang)) {
  if (!is_array($value) && $key != "charset") {
    if ($pageEncoding == 3) {
      $lang[$key] = htmlentities($value, ENT_COMPAT, "ISO-8859-1");
      $lang[$key] = str_replace("&gt;", ">", $lang[$key]);
      $lang[$key] = str_replace("&lt;", "<", $lang[$key]);
    } else if ($pageEncoding == 1) $lang[$key] = utf8_encode($value);
  }
}

?>