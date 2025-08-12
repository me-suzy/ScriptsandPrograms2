<?php

$dbhost = 'localhost';
$dbname = 'seo_board';
$dbuser = 'username';
$dbpass = 'password';
$dbpref = 'seo_board_';

$adminfile = 'admin.php';
$adminuser = 'adminusername';
$adminpass = 'adminpassword';
$adminemail = 'admin@yoursite.com';

//don't change this AFTER installing the board. You can change it before installing.
$shaprefix = 'This is the default sha hashing prefix.';

$forumhome = 'link to forum home'; //link to forum home
$forumdir = 'forum directory'; //forum dir without the script file, must end with a '/'
$forumscript = 'link to main forum script file'; //forum dir + script file

$cookiedomain = '';
$cookiename = 'seo-board';
$cookiepath = '';
$cookiesecure = FALSE;

$enablegzip = 0; // 0 - no; 1 - yes

$visittimeout = 600;
$usereditposttimeout = 1200;

$forumtimezone = 2; // default forum zone

$registermode = 1; // 1 - register user immediately; 0 - send an e-mail with generated password

$forumtitle = 'SEO-Board Forums';

$lang = 'eng';

$invalidusernames = array('Anonymous', 'Guest');

$maxwordlength = 60;
$maxpostlength = 20000;

$postsperpage = 30;
$topicsperpage = 20;
$searchresultsperpage = 30;

$searchexpiretime = 600;

$showforumstats = 1; //0 - no; 1 - yes
$showlastposts = 1;  //0 - no; 1 - yes
$shownumlastposts = 30;
//exclude forums from showing their topics in last discussions
//array(forumexcludeid1, forumexcludeid2..); or empty -> array();
$lastpostsexclude = array();

$datetimeformat = 'd M Y H:i';
$dateformat = 'd M Y';
$shortdateformat = 'M Y';
//if you use long months (January), then change $engmonths to long English months for multi-lang support
$engmonths = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

$antispam = 60;

$modrewrite = 0; // 0- no mod_rewrite; 1- search engine friendly urls, requires .htaccess ...

//special forum options
//private forums, only for a selected subset of users
// = array(private_forum_id => array(allowuserid1, allowuserid2..));
$privateforums = array();
//only admin can post in read only forums
// = array(readonly_forum_id, readonly_forum_id2);
$readonlyforums = array();
//do everything except creating NEW topics (only admin can do it!)
// = array(postonly_forum_id, postonly_forum_id2..);
$postonlyforums = array();
// by default guests cannot post
// = array(guestcanpost_forum_id1, guestcanpost_forum_id2..);
$guestscanpostforums = array();
// allow posting of HTML tags in messages in these forums
// = array(allowedhtmlforumid1,...);
$allowhtmlforums = array();
// forums that are sorted by topic creation time (newest on top)
// = array(forumid1, forumid2..);
$articleforums = array ();

//forum moderators
// = array(forum => array(moderatorID => 'ModeratorName', moderatorID2 => 'ModeratorName2'));
$moderators = array();

$showmoderators = 0; //0-no 1-yes
?>
