<?
##############################################################################
#                                                                            #
#                          errorvals.inc.php                                 #
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

class errorvalues {
      var $loginerror = array (
                        "Error 33-1-0: Incomplete form data. Fields empty",
                        "Error 33-1-1: Invalid characters in first name",
                        "Error 33-1-2: Invalid characters in last name",
                        "Error 33-1-3: Invalid email address",
                        "Error 33-1-4: Username too short. Must be atleast 4 characters long",
                        "Error 33-1-5: Username too long. Must be less than 20 characters long",
                        "Error 33-1-6: Invalid characters in username",
                        "Error 33-1-7: Both passwords must match",
                        "Error 33-1-8: Password too short. Must be atleast 4 characters long",
                        "Error 33-1-9: Password too long. Must be less than 20 characters long",
                        "Error 33-1-10: Invalid characters in password",
                        "Error 33-1-11: Username is already in use. Please choose another",
                        "Error 33-1-12: Email address exists in database. Only one registered user per email address",
                        "Error 33-1-13: Critical Database Error. Insert failed to add user data to confirmation database",
                        "Error 33-2-14: Incomplete form data. Fields empty",
                        "Error 33-2-15: Invalid characters in username",
                        "Error 33-2-16: Invalid characters in password",
                        "Error 33-3-17: Incomplete form data. Fields Empty",
                        "Error 33-3-18: Invalid temporary ID and username. Possibly caused by temporary ID expiration. User must confirm email within 48 hours",
                        "Error 33-3-19: Critical Database Error. Insert failed to add user data into login database.",
                        "Error 33-4-20: Flushing of stale data failed from confirmation database",
                        "Error 33-5-21: Incomplete form data. Fields empty",
                        "Error 33-5-22: No username found in database for specified email address",
                        "Error 33-6-23: Both new email addresses must match. Please correct",
                        "Error 33-6-24: Invalid email address",
                        "Error 33-6-25: A user already exists with that email address. Only one user allowed per email address",
                        "Error 33-6-26: Current and new email addresses match. Please specify a new email address",
                        "Error 33-6-27: Critical Database Error. Insert failed to add new data into email confirmation database",
                        "Error 33-7-28: Incomplete form data. Fields empty",
                        "Error 33-7-29: Invalid temporary ID and username. Possibly caused by temporary ID expiration. User must confirm email within 48 hours",
                        "Error 33-8-30: Flushing of stale data failed from email confirmation database",
                        "Error 33-9-31: Passwords dont match. Both passwords should be the same",
                        "Error 33-9-32: Password too short. Must be atleast 4 characters long",
                        "Error 33-9-33: Password too long. Must be less than 20 characters long",
                        "Error 33-9-34: Invalid characters in password",
                        "Error 33-9-35: Critical Database Error. Password update failed in login database"
                     );
}

$errorval = new errorvalues;


?>
