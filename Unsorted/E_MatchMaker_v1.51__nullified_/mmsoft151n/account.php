<?
##############################################################################
#                                                                            #
#                              account.php                                   #
#                                                                            #
##############################################################################
# PROGRAM : E-MatchMaker                                                     #
# VERSION : 1.51                                                             #
#                                                                            #
# NOTES   : site using default layout and graphics                           #
##############################################################################
# All source code, images, programs, files included in this distribution     #
# Copyright (c) 2001-2002                                                    #
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
require_once("siteconfig.php"); 
require_once("login-functions.php");

$login_check = $loginlib->is_logged();

if (!$login_check) {
        header("Location: index.php");
        exit;
}

require_once("mmsoft.inc.php");
require_once("siteconfig.php");

$session_vars = explode(":", $mmcookie);
$username = $session_vars[0]; 
$id = $session_vars[2];

if($action == chemail) {
  if($email) {
    $return = $loginlib->chemail($id, $email, $email2);
    if($return == 2) {
       header("Location: account.php?message=Email Confirmation Sent");
       exit;  
    }
    else {
       header("Location: account.php?message=$return");
       exit;
    }
  }
  $centerimage = "images/change_email_big.gif";
  include("static/header.html");
  include("static/chemail.html");
  exit;
}

if($action == chpwd) {
  if($password) {
    $return = $loginlib->chpass($id, $password, $password2);
    if($return == 2) {
       header("Location: account.php?message=Password Changed Successfully");
       exit;  
    }
    else {
       header("Location: account.php?message=$return");
       exit;
    }
  }
  $centerimage = "images/change_password_big.gif";
  include("static/header.html");
  include("static/chpwd.html");
  exit;
}

$centerimage = "images/my_account_big.gif";
include("static/header.html");
include("static/account.html");

?>
