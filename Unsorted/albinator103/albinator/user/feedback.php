<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	
      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

       $usr->Header($Config_SiteTitle ." :: $strMenusFeedback");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/feedback.gif>&nbsp;</div><br>");

	 if($confirm != 1)
	 {
 	 $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	 $row = mysql_fetch_array( $result );

?>

<form action=feedback.php method=post>
<table width="75%" border="0" cellspacing="1" cellpadding="0" align="center" bgcolor="#333333">
  <tr bgcolor="#333333"> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr bgcolor="#eeeeee"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo $strName ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td class=tn><b><?php echo $row[uname] ?></b><input type=hidden name=name value="<?php echo $row[uname] ?>"></td>
        </tr>
        <tr bgcolor="#dddddd"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo $strEmail ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td class=tn><?php echo $row[email] ?><input type=hidden name=email value="<?php echo $row[email] ?>"></td>
        </tr>
        <tr bgcolor="#eeeeee"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo $strType ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td> 
            <select name="type">
              <option value="" selected>--- Select ----</option>
              <option value="Help">Help</option>
              <option value="Query">Query</option>
              <option value="Comment">Comment</option>
              <option value="Feedback">Feedback</option>
              <option value="Bug Report">Bug Report</option>
              <option value="Suggestion">Suggestion</option>
              <option value="Monthly Reminders">Monthly Reminders</option>
              <option value="Manipulate">Manipulate Access</option>
              <option value="Extra Photos">Extra Photos</option>
              <option value="Extra Albums">Extra Albums</option>
              <option value="Extra Reminders">Extra Reminders</option>
              <option value="Extra Space">Extra Space</option>
              <option value="Other">Other</option>
            </select>
          </td>
        </tr>
        <tr bgcolor="#dddddd"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo ($strComment.$strPuralS); ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td> 
            <textarea name="comments" rows="5" cols="30"></textarea>
          </td>
        </tr>
        <tr bgcolor="#dddddd">
          <td width="30%" class="tn">&nbsp;</td>
          <td width="2%">&nbsp;</td>
          <td><input type=hidden name=confirm value=1>
		  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>                  
<?php
}

else
{
	if(!$type)
	{
       $errMsg = "<b>$strFeedbackNotify, <a href=\"javascript:history.back(-1);\">$strBack</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '60' );
   	 $usr->FooterOut();

	 exit;
      }

	if(!$comments)
	{
       $errMsg = "<b>$strNo $strComment$strPuralS, <a href=\"javascript:history.back(-1);\">$strBack</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '60' );
   	 $usr->FooterOut();

	 exit;
      }


$BAKstrFeedbackSent = $strFeedbackSent;
$BAKstrThanks	  = $strThanks;

include($dirpath."essential/lang/{$Config_AdminLangLoad}.lang.php");

$recnameto   = $Config_adminname;
$recemailto  = $Config_adminmail;
$subject     = "$Config_systemname $strMenusFeedback";
$premessage  = "$REMOTE_ADD\n$strName: $name\n$strEmail: $email\nuid: $uid\n$strType: $type\n$strComment: $comments\n\n";
$endmessage  = "$Config_msgfooter";
$sendmessage = "$premessage $endmessage";
$sendmessage = stripslashes($sendmessage);

$mailheader = "From: $name <$email>\nX-Mailer: $Config_systemname $strFeedback\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

       $errMsg = "<b>$BAKstrFeedbackSent</b>\n";
       $usr->errMessage( $errMsg, $BAKstrThanks, 'tick', '60' );
	 echo("<br>");
}

$usr->Footer(); 

?>