<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$csr = new ComFunc();

	$uid = "";
	if($change == 1)
	{
		if($findwht == "user")
		{					            
		  $finduser = strtolower($finduser);

		  if(!$finduser)
		  $err .= "$strRegisterError7e<br>";

		  else
		  {
		  $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$finduser' && status != '0'" );
		  $nr = mysql_num_rows( $result );

		  if(!$nr)
		  { $err .= "$strRegisterError7e<br>"; }
		  }
		}
	
		else
		{
		  if(!CheckEmail($findemail) || !$findemail)
		  { $err .= "$strRegisterError2b<br>"; }

		  else
		  {
		  $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email='$findemail'" );
		  $nr = mysql_num_rows( $result );

		  if(!$nr)
		  { $err .= "$strRegisterError2b<br>"; }
		  }
		}
	}

	if($err || $change != 1)
	{
		# show
     	      $usr->HeaderOut($Config_SiteTitle .' :: Login Help');

		if($err)
		{
		    closeDB();
		    $errMsg = "<b>$err</b><br>\n";
		    $usr->errMessage( $errMsg, $strError );
		}

		$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
		$nr = mysql_num_rows( $result );
		$row = mysql_fetch_array( $result );
		mysql_free_result ( $result );
?>

<p>&nbsp;</p>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#003366">
  <tr bgcolor="#003366"> 
    <td align="right" height="2"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/password.gif" width="212" height="49"></td>
  </tr>
</table>
<table width="70%" border="0" cellspacing="1" cellpadding="3" bgcolor="#003366" align="center">
  <tr bgcolor="#CCCCCC"> 
    <td height="2"> 
      <form method=post action=forgot.php>
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td width="21%" class="tn" height="2"> 
              <div align="right"></div>
            </td>
            <td width="79%" height="2">&nbsp; </td>
          </tr>
        </table>
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td width="21%" class="tn"> 
              <div align="right"><?php echo $strUsername ?></div>
            </td>
            <td width="79%"> 
              <input type="text" name="finduser" maxlength="15" class="fieldsc">
              <input type="hidden" name="change" value="1">
              <input type="hidden" name="findwht" value="user">
              <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers" ?>/buttons/find.gif" width=53 height=19 border=0 value="change &gt;&gt;">
            </td>
          </tr>
        </table>
      </form>
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="21%" class="tn" height="2"> 
            <div align="right"><b>OR</b>&nbsp;</div>
          </td>
          <td width="79%" height="2">&nbsp; </td>
        </tr>
      </table>
      <form method=post action=forgot.php>
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td width="21%" class="tn"> 
              <div align="right"><?php echo $strEmail ?></div>
            </td>
            <td width="79%"> 
              <input type="text" name="findemail" maxlength="100" class="fieldsc">
              <input type="hidden" name="change" value="1">
              <input type="hidden" name="findwht" value="email">
              <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers" ?>/buttons/find.gif" width=53 height=19 border=0 value="change &gt;&gt;">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>

<?php
            $usr->FooterOut();
		exit;
  }

  else
  {
		$randpass = md5(uniqid ($Config_p));
		$randpass = substr($randpass, 0, 14);
		
		$enc_newpassword = md5($randpass);

		if($findwht == "user")
		{ 
       	 $finduser = strtolower($finduser);
		 $result_orig = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$finduser'" );
		 $row = mysql_fetch_array( $result_orig );
		 mysql_free_result( $result_orig );			 

		 $result = queryDB( "UPDATE $tbl_userinfo SET password = '$enc_newpassword' WHERE uid = '$finduser'" );

		 if($integrate_db && $intergrate_known == 'vb')
		 $result = queryDB( "UPDATE $tbl_user_alter SET password = '$enc_newpassword' WHERE $fld_uid_name = '$finduser'" );

		 $inform_mail_msg = $csr->LangConvert($strForgetMail1, "$strPassword: $randpass\n\n");
		}

		else
		{ 
		 $result_orig = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$findemail'" );
		 $row = mysql_fetch_array( $result_orig );
		 mysql_free_result( $result_orig );			 

		 $result = queryDB( "UPDATE $tbl_userinfo SET password = '$enc_newpassword' WHERE email = '$findemail'" );

		 if($integrate_db && $intergrate_known == 'vb')
		 $result = queryDB( "UPDATE $tbl_user_alter SET password = '$enc_newpassword' WHERE email = '$findemail'" );

		 $inform_mail_msg = $csr->LangConvert($strForgetMail1, "$strUsername: $row[uid]\n$strPassword: $randpass\n\n");
		}

		$subject = "New Password";
		$Config_sitename_url = $Config_mainurl;
		$recnameto = $row[uname];
		$recemailto = $row[email];
		$name = $Config_sitename;
		$email = $Config_adminmail;

		$premessage = $csr->LangConvert($strForgetMail2, $inform_mail_msg);

		$endmessage = "$Config_msgfooter";
		$sendmessage = "$premessage \n $message \n $endmessage";

		$mailheader = "From: $name <$email>\nX-Mailer: $strPassword\nContent-Type: text/plain";
		mail("$recemailto","$subject","$sendmessage","$mailheader");

		$usr->HeaderOut($Config_SiteTitle ." $strDetail");
	      $errMsg = "<b>$strForgetMail3</b>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '60' );
            $usr->FooterOut();

		exit;
  }

?>
