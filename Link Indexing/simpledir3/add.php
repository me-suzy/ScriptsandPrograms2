<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

require("config.php");
require("common.php");

require_once('smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign("headfile",$headfile);
$smarty->assign("footfile",$footfile);
$smarty->assign("sdversion",$sdversion);
$smarty->assign("self",$self);
$smarty->assign("pend",$pend);
$smarty->assign("allowdesc",$allowdesc);
$smarty->assign("sitename",$sitename);
$smarty->assign("adminname",$adminname);
$smarty->assign("adminemail",$adminemail);
$smarty->assign("siteurl",$siteurl);

if ($query == "" && $visitoradd == "Y")
{
  $smarty->display('add_top.tpl');          // display top template
  printCats('relCatID');                    // print categories
  $smarty->display('add_bottom.tpl');       // display bottom template
}

if ($query == "" && $visitoradd == "N")
{
  $smarty->display('add_disable.tpl');      // display template
}

if ($query == "newlinkv")
{
  // set form values to variables
  $relCatID = $_POST['relCatID'];
  $linkname = addslashes($_POST['linkname']);
  $linkurl = $_POST['linkurl'];
  $linkstatus = $_POST['linkstatus'];
  $linkdesc = addslashes($_POST['linkdesc']);
  $ownername = addslashes($_POST['ownername']);
  $owneremail = $_POST['owneremail'];
  $linknotes = addslashes($_POST['linknotes']);
  // select category name
  $select = mysql_query ("SELECT catname FROM $tbcats WHERE catID = '$relCatID'");
  $row = mysql_fetch_array($select);
  $catname = $row['catname'];
  // set form variables to smarty variables
  $smarty->assign("category",$catname);
  $smarty->assign("linkname",$linkname);
  $smarty->assign("linkurl",$linkurl);
  $smarty->assign("linkstatus",$linkstatus);
  $smarty->assign("linkdesc",$linkdesc);
  $smarty->assign("ownername",$ownername);
  $smarty->assign("owneremail",$owneremail);
  $smarty->assign("linknotes",$linknotes);
  // if email notification is turned on
  if ($emailnotify == "Y")
  {
    // message to directory owner
    $recipient = "$adminemail";
    $subject = "$sitename: New Pending Link";
    $mailheaders = "From: $owneremail\n";
    $mailheaders .= "Reply-To: $owneremail\n\n";
    if ($linknotes != "")
      $msglinknotes = "\n\nThe owner also left this note:\n$linknotes";
    $msg = "A new link has been added to $sitename with pending status and the following information:\n\nOwner name: $ownername\nOwner E-mail: $owneremail\nLink name: $linkname\nLink url: $linkurl\nLink Description: $linkdesc\nCategory: $catname $msglinknotes\n\nTo approve this link and/or edit its information, login to your admin panel at $siteurl/admin.php\n\nThis is an automated message sent by SimpleDir $sdversion. To stop these e-mails from being sent, change the e-mail options in your control panel.";
    mail($recipient, $subject, $msg, $mailheaders);
  }
  // add to $tblinks
  $query2 = "INSERT INTO $tblinks VALUES ('', '$relCatID', '$linkname', '$linkurl', '$linkstatus', '$linkdesc', '$ownername', '$owneremail', '$linknotes')";
  mysql_query($query2) or die(mysql_error());
  // Display template
  $smarty->display('link_success.tpl');
}

if ($query == "modify")
{
  $smarty->display('modifyA.tpl');  // Display template
  printCats('oldcat');              // Select categories for old category choice
  $smarty->display('modifyB.tpl');  // Display template
  printCats('newcat');              // Select categories for new category choice
  $smarty->display('modifyC.tpl');  // Display template
}

if ($query == "modifyemail")
{
  // set form values to variables
  $name = addslashes($_POST['name']);
  $oldemail = $_POST['oldemail'];
  $oldsitename = addslashes($_POST['oldsitename']);
  $oldsiteurl = $_POST['oldsiteurl'];
  $oldcatid = $_POST['oldcat'];
  $newemail = $_POST['newemail'];
  $newsitename = addslashes($_POST['newsitename']);
  $newsiteurl = $_POST['newsiteurl'];
  $newcatid = $_POST['newcat'];
  $anythingelse = addslashes($_POST['anythingelse']);
  // select category name for $oldcatid
  $select = mysql_query ("SELECT catname FROM $tbcats WHERE catID = '$oldcatid'");
  $row = mysql_fetch_array($select);
  $oldcat = $row['catname'];
  // select category name for $newcatid
  if ($newcatid != "")
  {
    $select = mysql_query ("SELECT catname FROM $tbcats WHERE catID = '$newcatid'");
    $row = mysql_fetch_array($select);
    $newcat = $row['catname'];
    $smarty->assign("newcat",$newcat);
  }
  // set smarty variables
  $smarty->assign("name",$name);
  $smarty->assign("oldemail",$oldemail);
  $smarty->assign("oldsitename",$oldsitename);
  $smarty->assign("oldsiteurl",$oldsiteurl);
  $smarty->assign("oldcatid",$oldcatid);
  $smarty->assign("newemail",$newemail);
  $smarty->assign("newsitename",$newsitename);
  $smarty->assign("newsiteurl",$newsiteurl);
  $smarty->assign("newcatid",$newcatid);
  $smarty->assign("anythingelse",$anythingelse);
  $smarty->assign("oldcat",$oldcat);
  // set $owneremail
  if ($newemail == "")
    $owneremail = $oldemail;
  else
    $owneremail = $newemail;
  // set variables to what will be displayed
  if ($newemail != "")
    $msgnewemail = "\nNew E-Mail Address: $newemail";
  if ($newsitename != "")
    $msgnewsitename = "\nNew Site Name: $newsitename";
  if ($newsiteurl != "")
    $msgnewsiteurl = "\nNew Site URL: $newsiteurl";
  if ($newcatid != "")
    $msgnewcat = "\nNew Category: $newcat";
  if ($anythingelse != "")
    $msgelse = "\n\nAnything else?: $anythingelse";
  // begin message to directory owner
  $recipient = $adminemail;
  $subject = "$sitename: Modify Info Request";
  $mailheaders = "From: $owneremail\n";
  $mailheaders .= "Reply-To: $owneremail\n\n";
  $msg = "Someone has submitted a Modify Info Request for $sitename. Here are the details of the form:\n\nName: $name\nOld E-mail Address: $oldemail\nOld Site Name: $oldsitename\nOld Site URL: $oldsiteurl\nOld Category: $oldcat $msgnewemail $msgnewsitename $msgnewsiteurl $msgnewcat $msgelse\n\nTo modify this link's information, login to your admin panel at $siteurl/admin.php";
  mail($recipient, $subject, $msg, $mailheaders);
  // display success template
  $smarty->display('modify_success.tpl');
}

?>