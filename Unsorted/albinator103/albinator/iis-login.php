<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");

      $usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

	if ( $HTTP_POST_VARS["change"] == '' && $HTTP_POST_VARS["uidform"] == '' && $HTTP_POST_VARS["password"] == '' )
	{
	}
	else
	{
	$uidform = strtolower($uidform);
    	connectDB();
	$uid = $uidform;

	if($integrate_db && $intergrate_known == "vb")
	$result = queryDB( "SELECT userid,username,password FROM $tbl_user_alter WHERE username='".addslashes(htmlspecialchars($uid))."'" );

	else
	$result = queryDB( "SELECT uid,password,admin,status,logintime FROM $tbl_userinfo WHERE uid='$uidform' && status = '1' && (validity >= $now_date || validity = '0')" );


    	$nr = mysql_num_rows( $result );

    	if ($nr > 0)
    	{
	         $row = mysql_fetch_array($result);
		   if (md5($password) == $row[password])
     		   {
		    if($integrate_db)
		    {
		    $result_log = queryDB( "SELECT logintime FROM $tbl_userinfo WHERE uid='$uidform'" );
		    $row_log    = mysql_fetch_array( $result_log );
		    $row[logintime] = $row_log[logintime];
		    }

		    $result = queryDB( "SELECT admin FROM $tbl_userinfo WHERE uid='$uidform'" );
	          $row_admin = mysql_fetch_array($result);

		    if(($Config_sysstatus == "1" || $row_admin[admin] == "1") && $Config_sysstatus != "2")
		    {
		    $login_time = $row[logintime]*60*60*24;
		    if(!$login_time)
		    $login_time = $Config_logout_time;

		    $LastTimeDate = date ("l dS of F Y h:i:s A");
		    $lastinfo = "$REMOTE_ADDR, $LastTimeDate";

		    if($Config_makelogs == "1")
		    $csr->MakeAdminLogs( $uid, "Loggedin", "1");

	     	    $result_lastip = queryDB( "UPDATE $tbl_userinfo SET lastip = '$lastinfo' WHERE uid='$uid'" );

                Header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                Header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                Header ("Cache-Control: no-cache, must-revalidate");
                Header ("Pragma: no-cache");

		    if($ref == "1" && $myref && !preg_match("/login.php/i", $myref) && !preg_match("/dbintegrate/i", $myref))
		    $redurl = $myref;
		    else
		    $redurl = "$Config_mainurl/user/index.php?o=1";

                $ucook->Login( $row[$fld_uid], $row[$fld_password], $uidform, $login_time);
                Header("Refresh: 1;url=$redurl");

		    $strLoginWelcome = $csr->LangConvert($strLoginWelcome, $uid);

echo <<< _HTML_END_

<div align="center" style="font-family: Verdana; font-size: 10pt; font-weight: bold; color: #990000;">
$strLoginWelcome
</div>

_HTML_END_;

		     exit;
		     }
		     else
		     {
			 $csr->MakeAdminLogs( $uid, "Couldnt login - system shutdown", "3");
 			 $usrMsg = "$strLoginSysShut,<br>$Config_sysmsg";
		     }
		    }
		    else
		    {
 			 $usrMsg = "$strLoginError1";
		    }
		}
		else if ($nr == 0 && !$integrate_db)
		{
			$result = queryDB( "SELECT status,validity FROM $tbl_userinfo WHERE uid='$uidform'" );
	            $row = mysql_fetch_array($result);

			 if(!$row[uid])
			 $usrMsg = "$strLoginError1";

			 else if($row[status] == '0')
 			 $usrMsg = "$strLoginError3";
			 
			 else if($row[status] == '2')
 			 $usrMsg = "$strLoginError2";
			 
			 else if($row[validity] < $now_date)
 			 $usrMsg = "$strLoginError6";
			 
			 else
 			 $usrMsg = "$strLoginError1";
		}
	    else
	    {
		 $usrMsg = "$strLoginError1";
	    }
	}

	if ( !empty($usrMsg) )
	{
		$usr->HeaderOut('', '', '', 'onload');
		$errMsg = "<b>$usrMsg</b><br><br>\n";

		$usr->errMessage( $errMsg, $strSorry, 'error', '65' );
	}

      else
	{ $usr->HeaderOut('onload'); }

?>

<br><br>
<form method=post action="login.php" name="Login" <?php if(!$integrate_db) echo('onsubmit="return loginCheck()"'); ?>>
  <table width="80%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#006699">
    <tr> 
      <td colspan=2 bgcolor="#000000" class=tn> 
        <div align="right"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/login.gif" width="114" height="49"></div>
      </td>
    </tr>
    <tr> 
      <td colspan=2 bgcolor="#000000" class=tn> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> 
              <table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr> 
                  <td> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
                      <tr> 
                        <td bgcolor="#dddddd" width="29%" class=tn> 
                          <div align="right"><?php echo $strUsername ?>&nbsp;</div>
                        </td>
                        <td bgcolor="#dddddd" width="40%">&nbsp; 
                          <input type="text" name="uidform" class="fieldsd" maxlength="15">
                        </td>
                        <td bgcolor="#dddddd">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td bgcolor="#CCCCCC" width="29%" class=tn> 
                          <div align="right"><?php echo $strPassword ?>&nbsp;</div>
                        </td>
                        <td bgcolor="#CCCCCC" width="40%" valign=bottom>&nbsp; 
                          <input type="password" name="password" class=fieldsd maxlength="15">
                        </td>
                        <td bgcolor="#CCCCCC" valign=bottom>
				  <input type=hidden name=change value=1>
				  <input type=hidden name=ref value="<?php echo $ref ?>">
				  <input type=hidden name=myref value="<?php if($myref) echo $myref; else echo $HTTP_REFERER; ?>">
                          <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/loginbut.gif" width="53" height="19" align="bottom" border="0">
                        </td>
                      </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
                      <tr> 
                        <td bgcolor="#006699" class=ts> 
                          <div align="center">[ <a class=noundertsb href="register.php"><?php echo $strMenusSignup ?></a> 
                            ~ <a class=noundertsb href="forgot.php"><?php echo $strMenusForgot ?></a> 
                            ]</div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div align="center" class=ts><br><?php echo $strLoginError4 ?></div>
</form>
<?php
	    	    closeDB();
		    $usr->FooterOut();
?>