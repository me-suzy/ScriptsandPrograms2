<?
##############################################################################
#                                                                            #
#                              contact.php                                   #
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

if($_SERVER['HTTP_REFERER'] == "$mmconfig->webaddress/contactus.php")
  @mail($mmconfig->webmaster, "New Website Inquiry", "The following information was left on the Contact Us page:\n
            \nName:  $firstname $lastname
	    \nPhone:  $phone
            \nEmail Address:  $emailaddress
            \nMember:  $member
            \n\nMessage:  
            \n$message", "From: $mmconfig->webmaster\r\n");

header("Location: index.php");

?>