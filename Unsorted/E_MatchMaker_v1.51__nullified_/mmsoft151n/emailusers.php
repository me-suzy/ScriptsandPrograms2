<?
##############################################################################
#                                                                            #
#                            emailusers.php                                  #
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

if($submit) {
  $recordSet = $db->Execute("select f_name, l_name, email from login_data");

  while(!$recordSet->EOF()) {
    $f_name = $recordSet->fields("f_name");
    $l_name = $recordSet->fields("l_name");
    $email = $recordSet->fields("email");
    $from = addslashes($from);

    @mail("\"$f_name $l_name\" <$email>", addslashes($subject), addslashes($message), "From: $from\r\n");

    $recordSet->MoveNext();
  }
  exit;
}

?>

<form action="admin.php" method=post>
<input type=hidden name=action value=emailusers>
From:&nbsp;<input type=text name=from length=20>&nbsp;&nbsp;In the format "Your Name" &ltemail@address.com&gt<br>
Subject:&nbsp;<input type=text name=subject length=40><br>
<br>
Message:<br>
<input type=textarea name=message cols=40 rows=10>
<input type=submit name=submit value=Send>
</form>

<?
