<?php

/**
 * Pivot Moblog configuration script..
 */

// The user, password and pop3 server for the email account..
// This _must_ be a  regular pop3 mail account, so hotmail 
// and gmail will _not_ work.
// If possible you should use an account on the same server 
// the same server as your weblog is on, so access time is minimized.
$moblog_cfg['pop_user'] = "mail@example.org";
$moblog_cfg['pop_pass'] = "password";
$moblog_cfg['pop_host'] = "mail.example.org";
$moblog_cfg['pop_port'] = 110;


// The mininum time in seconds between fetching email..
// Default is 600 seconds, once every ten minutes..
// If you're using pivot on the same server as where the mailbox is, you
// can lower this number to check mail more often. eg. 180 seconds.
$moblog_cfg['interval'] = 60*10;

// The user that the posted entries will belong to..
$moblog_cfg['user'] = "bob";


// Only email that has a 'from:' that matches one of the following will
// be posted. You can enter complete email addresses, or partial ones.
$moblog_cfg['allowed_senders'] = array("bob@mijnkopthee.nl", "bob" );


// The category that normal messages will be posted to..
$moblog_cfg['category'] = "moblog";


// If a message is not from an allowed sender, it will be posted to this
// category. Normally you would set this category so it isn't published.
$moblog_cfg['spam_category'] = "spam";

// Assign messages with certain mime-types to specific categories..
//$moblog_cfg['mime_cat']['image'] = "default";
//$moblog_cfg['mime_cat']['application'] = "default";
$moblog_cfg['mime_cat']['video'] = "video";
$moblog_cfg['mime_cat']['audio'] = "mp3";



// If the entry has no Title:.. line, this will be used as a title.
// Use the php date format: www.php.net/date
$moblog_cfg['title'] = "Moblog on " . date("m-d H:i");


// Set the status of moblog posts. Set this to "hold" when you're testing.
$moblog_cfg['status'] = "publish";


// Allow comments on moblog posts? 1 for yes, 0 for no..
$moblog_cfg['allow_comments'] = 1;


// Save a local copy of the email in the folder moblog/mail/
$moblog_cfg['save_mail'] = true;


// Leave messages on server.. Only set this to 'true' when testing, 
// because you will get duplicates, everytime the mail-popper is
// run.
$moblog_cfg['leave_on_server'] = false;


// Send confirmation to the sender of the moblog?
$moblog_cfg['send_confirmation'] = true;

// Image size and quality of the thumbnail. When an image is found, it
// is automatically cropped and scaled to exactly fit within these
// dimensions.
// Width and height are in pixels. The quality is a number between 0 and 100.
$moblog_cfg['maxwidth'] = 400;
$moblog_cfg['maxheight'] = 200;
$moblog_cfg['quality'] = 70;

// If no thumbnail can be created for an image, this text will be
// inserted in your entry.
$moblog_cfg['click_for_image'] = "Click for image";



// These are the current known providers. 
$moblog_cfg['known_carriers'] = array('t-mobile', 'vodafone', 'kpn', 'orange', 'tele2.no', 'virgin mobile', 'telfort');


$moblog_cfg['skipcontent']['t-mobile']['content-disposition'] = 'inline; filename="text.txt"';
$moblog_cfg['skipcontent']['vodafone']['content-type'] = 'text/html; charset=utf-8';
$moblog_cfg['skipcontent']['vodafone']['filename'] = array( 'reply2.gif', 'title_bar.gif', 'vodafone_logo.gif', 
		'pixel.gif', 'h_left.jpg', 'h_background.gif', 'sender.gif', 'subject.gif', 'button_answer.gif', 
		'h_right.gif', 'corner_11.gif', 'dot_line_h1.gif', 'corner_12.gif', 'dot_line_v.gif','corner_21.gif', 
		'dot_line_h2.gif', 'corner_22.gif', 'vodafone_footer.gif');

?>
