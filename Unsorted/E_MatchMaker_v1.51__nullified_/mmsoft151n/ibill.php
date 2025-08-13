<?
<?
##############################################################################
#                                                                            #
#                                ibill.php                                   #
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

require_once('siteconfig.php');

if(isset($pincode) && $pincode != "") {

  $recordSet = $db->Execute("select * from ibill where pincode = $pincode and is_used = 0");

  if(isset($recordSet) && $recordSet != "") {
    if($recordSet->RecordCount()) {
      $success = $db->Execute("update ibill set is_used = 1, used_date = now(), userid_used_by = '$user' where pincode=$pincode");
      $success2 = $db->Execute("update login_data set pmember = 1 where username = '$user'");

      if(!$success || !$success2) {
        @mail($mmconfig->webmaster, "Ibill Payment Update Failed", "The system incurred an error updating payment status for user '$username'. Please login to the admin area to verify that the user's status has been updated.");
        echo "success = $success <BR> success2 = $success2";
        include("static/ibillerror.html");
      }
      else {
        header("Location: index.php");
      }
    }
    else {
      @mail($mmconfig->webmaster, "IBILL FAILURE", "Either an error occured with Ibill or someone has illegitamately tried to gain access without paying.");    
      include("static/header.html");
      include("static/ibillerror.html");
    }
  }
}
else {
include("static/header.html");
include("static/ibillinput.html");
}

?>
