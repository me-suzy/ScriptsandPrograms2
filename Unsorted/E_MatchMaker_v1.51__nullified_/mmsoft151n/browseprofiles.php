<?
##############################################################################
#                                                                            #
#                              browseprofiles.php                            #
#                                                                            #
##############################################################################
# PROGRAM : E-MatchMaker                                                     #
# VERSION : 1.51                                                             #
#                                                                            #
# NOTES   : site using default site layout and graphics                      #
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
	header("Location: login.php");
	exit;
}

require_once("writecombo.php");
require_once("select_values.php");

$FrecordSet1 = $db->Execute("select profile.username from profile, profile_pic where 
profile.username = profile_pic.username and profile_pic.approved = '1' and profile.sex = '1' and profile.haspicture = '1' 
LIMIT 1");
$Fuser1 = $FrecordSet1->fields('username');
$MrecordSet1 = $db->Execute("select profile.username from profile, profile_pic where 
profile.username = profile_pic.username and profile_pic.approved = '1' and profile.sex = '2' and profile.haspicture = '1' 
LIMIT 1");
$Muser1 = $MrecordSet1->fields('username');
$FrecordSet2 = $db->Execute("select profile.username from profile, profile_pic where 
profile.username = profile_pic.username and profile_pic.approved = '1' and profile.sex = '1' and profile.haspicture = '1' 
and profile.username != '$Fuser1' LIMIT 1");
$Fuser2 = $FrecordSet2->fields('username');
$MrecordSet2 = $db->Execute("select profile.username from profile, profile_pic where 
profile.username = profile_pic.username and profile_pic.approved = '1' and profile.sex = '2' and profile.haspicture = '1' 
and profile.username != '$Muser1' LIMIT 1");
$Muser2 = $MrecordSet2->fields('username');

$centerimage = "images/search_red_dot_big.gif";
include("static/header.html");
include("static/browse.html");

?>

