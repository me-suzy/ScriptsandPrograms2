<?
##############################################################################
#                                                                            #
#                              email.php                                     #
#                                                                            #
##############################################################################
# PROGRAM : E-MatchMaker                                                     #
# VERSION : 1.51                                                             #
#                                                                            #
# NOTES   : site using default site layout and graphics                      #
##############################################################################
# All source code, images, programs, files included in this distribution     #
# Copyright (c) 20012-2002                                                   #
# Supplied by          : CyKuH [WTN]                                         #
# Nullified by         : CyKuH [WTN]                                         #
# Distribution:        : via WebForum and xCGI Forums File Dumps             #
##############################################################################
#                                                                            #
#    While we distribute the source code for our scripts and you are         #
#    allowed to edit them to better suit your needs, we do not               #
#    support modified code.  Please see the license prior to changing        #
#    anything. You must agree to the license terms before using this         #
#    software package or any code contained herein.                          #
#                                                                            #
#    Any redistribution without permission of MatchMakerSoftware             #
#    is strictly forbidden.                                                  #
#                                                                            #
##############################################################################
?>
<?

include("siteconfig.php"); 
require_once("login-functions.php");

$login_check = $loginlib->is_logged();

if (!$login_check) {
        if($action == "read") {
	  header("Location: index.php?action=$action&msg_id=$msg_id&msg_index=$msg_index");
          exit;
	}
	else {
          header("Location: register.php");
          exit;
	}
}

$session_vars = explode(":", $mmcookie);
$username = $session_vars[0]; 

if($action) {

  if($action == "send") {
   if($mmconfig->payed_email) {
      $recordSet = $db->Execute("select emails_sent, pmember from login_data where username = '$username'");
      $emails_sent = $recordSet->fields("emails_sent");
      $pmember = $recordSet->fields("pmember");
      if($emails_sent > $mmconfig->max_free_emails && !$pmember) {
          header("Location: premium.php");
        exit;
      }
    }
    $sql = "insert into messages (sending_user, receiving_user, message, date) values ('$username', '$user', '$message', now())"; 
    if($db->Execute($sql)) {
      $recordSet = &$db->Execute("select email from login_data where username = '$user'");
      $emailaddr = $recordSet->Fields('email');
      $sql = "select id from messages where sending_user = '$username' AND receiving_user = '$user' AND message = '$message'";
      $recordSet = $db->Execute($sql);
      $msg_id = $recordSet->Fields('id');
      if($msg_id) {
        $msg_index = md5($msg_id.$username.$user.$mmconfig->secret);
        @mail($emailaddr, "New message from $username via $mmconfig->website", "You have received a new message from $username a subscriber at $mmconfig->website.\n
              \nTo retrieve your message please click on the following link:
              \n$mmconfig->webaddress and click on the MailBox button.
              \n\nThank You
              \n$mmconfig->website
              \nPlease visit us at $mmconfig->webaddress and tell all your friends to join!!", "From: $username@$mmconfig->domain\r\n");
        if($mmconfig->payed_email) {
          $emails_sent++;
          $db->Execute("update login_data set emails_sent = '$emails_sent' where username = '$username'");
        }
	  include("static/header.html");
        include("static/emailsent.html");
      } 
    }
  }
  elseif($action == "read") {
    $recordSet = &$db->Execute("select * from messages where id = '$msg_id'");
    $db_msg_id = $recordSet->Fields('id');
    $db_sending_user = $recordSet->Fields('sending_user');
    $db_receiving_user = $recordSet->Fields('receiving_user');
    $db_message = $recordSet->Fields('message');
    $db_date = $recordSet->Fields('date');
    $db->Execute("update messages set beenread = 1 where id = '$db_msg_id'");
    if($username == $db_receiving_user || $username = $db_sending_user) {
        $centerimage = "images/email_big.gif";
        include("static/header.html");
        include("static/showemail.html");
    }
  }
  elseif($action == reply) {
    $centerimage = "images/email_big.gif";
    include("static/header.html");
    include("static/sendemail.html");
  }
}
else {

  $centerimage = "images/email_big.gif";
  include("static/header.html");
  include("static/sendemail.html");

}

?>
