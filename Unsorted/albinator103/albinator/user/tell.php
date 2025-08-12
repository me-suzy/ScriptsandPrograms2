<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->Header($Config_SiteTitle ." :: $strMenusTell");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/tell.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr6, <a href=index.php>$strCreate</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 mysql_free_result( $result );

	 closeDB();
	 exit;
      }

	if(!$aid)
  	{
       $usr->Header($Config_SiteTitle ." :: $strMenusTell");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/tell.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr1, <a href=album_view.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 mysql_free_result( $result );

	 closeDB();
	 exit;
      }

	if($aid != "all" && $aid != "site")
	{
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid' && uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->Header($Config_SiteTitle ." :: $strMenusTell");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/tell.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr4</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 mysql_free_result( $result );

	 closeDB();
	 exit;
      }
	}

# show
	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$userinfo_nr = mysql_fetch_array( $result );
	mysql_free_result ( $result );

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid' && uid = '$uid'" );
	$row = mysql_fetch_array( $result );


if($al_private == 1)
{ 
	$temp_enc = md5($al_pass);
	if($temp_enc != $row[password] && $row[private] == 1)
	{
	$err .= "<p>&nbsp;</p>$strError:<UL><font color=#C00000>";
	$error = 1;
	$pass_err = 1;
	$err .= "<LI>$strTellWrong</LI>";
	}
	else if($row[private] == 1 && !$al_pass)
	{
	$err .= "<p>&nbsp;</p>$strError:<UL><font color=#C00000>";
	$error = 1;
	$pass_err = 1;
      $err .= "<LI>$strTellWrongPassb</LI>";
	}
	else
	{ 
	$priv_pass = "\n$strPassword: $al_pass\n\n$strTellWrongPassb"; 
	$priv_only_pass = "$strPassword: $al_pass";
	}
}


$name = $userinfo_nr[uname];
$email = $userinfo_nr[email];

if($row[private] == 1)
{
$private_ask = "<tr><td bgcolor=\"#CCCCCC\" class=\"tn\">$strAlbum $strPassword</td><td bgcolor=\"#006699\" width=\"69%\"><input type=password name=al_pass size=\"35\" class=fieldsg value=\"$al_pass\" ><input type=hidden name=\"al_private\" value=\"1\"></td></tr>";
}

$message = str_replace("\\'", "'", $message);
$Config_sitename_url = "$Config_mainurl";

if($aid != "site")
{ $subject = "$name - $strAlbum $strLink"; }
else
{ $subject = "$name - $strSuggestion"; }


if($aid != "all" && $aid != "site")
{
$suglink = "$Config_mainurl/showalbum.php?uuid=$uid&aid=$aid";
$alter_method = $csr->LangConvert($strTellAlterMethod, $Config_sitename_url, $strRegisterName4, $uid, $strAlbum, $aid, $priv_only_pass);

if($message)
{ $message = strip_tags($message, '<b><i>');
  $pre_msg = $csr->LangConvert($strTellMailTemp1, $name).":\n$message\n"; }

$putmsg = $csr->LangConvert($strTellAdvertiseMsg, $Config_systemname, $Config_site_msg);

$premessage = $csr->LangConvert($strTellMail, $name, $Config_sitename, $row[aname], $suglink, $priv_pass, $alter_method, $pre_msg, $putmsg);

$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage \n $endmessage";
}

else if($aid == "site")
{
$suglink = "$Config_mainurl/";

if($message)
{ $message = strip_tags($message, '<b><i>');
  $pre_msg = $csr->LangConvert($strTellMailTemp1, $name).":\n$message\n"; }

$putmsg = "\n$Config_site_msg";
$premessage = $csr->LangConvert($strTellAboutURsite, $name, $Config_sitename, $Config_mainurl, $pre_msg, $putmsg);

$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage \n $endmessage";
$row[aname] = "$Config_sitename";
}

else
{
$suglink = "$Config_mainurl/showlist.php?uuid=$uid&dowhat=user";

$alter_method2 = $csr->LangConvert($strTellAlterMethod, $Config_sitename_url, $strRegisterName4, $uid);

if($message)
{ $message = strip_tags($message, '<b><i>');
  $pre_msg = $csr->LangConvert($strTellMailTemp1, $name).":\n$message\n"; }

$putmsg = $csr->LangConvert($strTellAdvertiseMsg, $Config_systemname, $Config_site_msg);

$premessage = $csr->LangConvert($strTellMail, $name, $Config_sitename, "$strList, $name", $suglink, '', $alter_method, $pre_msg, $putmsg);

$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage \n $endmessage";
$row[aname] = "$strAlbum$strPuralS $strList";
}

if ($action == "sendmail") {
	$recnameto = split (",",$recname);
	$recemailto = split (",",$recemail);
	$count = count ($recemailto);
	$ncount = count ($recnameto);
      
     if($count != $ncount)
     { 
       $count_err = 1;
       $send = "no";
     }


	for ($i=0;$i<$count;$i++) {
	    if ($recnameto[$i] == "") {
			$recnameerror = "1";
        	$recsend = "no";
	    }

		if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $recemailto[$i]) || ereg("'", $recemailto[$i])) {
			$recemailerror = "1"; 
          
		    if($errno)
                { $ecount++; $errno .= ", ". ($i + 1); }
                else
                { $ecount = 1; $errno = $i + 1; }

    	    $send = "no";
	    }

		if ($cname == $name && $cemail == $email && $crecname == $recnameto[$i] && $crecemail == $recemailto[$i] && $csubject == $subject && $cmessage == $message && $cip == $ip) {
	        $send = "no";
		}

		if ($send ==  "no") {
		$error = "1";
	    	}
	}

     if($error != 1)
     {	$recnameto = split (",",$recname);
	      $recemailto = split (",",$recemail);
	      $count = count ($recemailto);

	for ($i=0;$i<$count;$i++) {
	    if ($send != "no") {

	$csr->PublicList($recnameto[$i], $recemailto[$i], $uid);

      $mailheader = "From: $name <$email>\nX-Mailer: $strAlbum $Config_sitename\nContent-Type: text/plain";
	mail("$recemailto[$i]","$subject","$sendmessage","$mailheader");

	$done_mailing = 1;
     		   }
       	}

    }    

	if($pass_err != 1)
	{ $err .= "<p>&nbsp;</p>$strError<UL><font color=#C00000>"; }

     if($error == 1 && $done_mailing != 1)
      {

if($count_err == "1")
{ $err .= "<LI><b>$strTellError1</b></LI>"; }

if ($recnameerror == "1")
{ $err .= "<LI>$strTellError2</LI>"; }

if ($emailerror == "1")
{ $err .= "<LI><b>$strEmail $strInvalid</b></LI>"; }

if ($recemailerror == "1")
{ 
 if($ecount == 1)
 { if($errno == "1")
   $recperror = "$strTellError3";
   else
   $recperror = $csr->LangConvert($strTellError4, $errno);
 }

 else
 $recperror = $csr->LangConvert($strTellError4, $errno);

 $err .= "<LI>$recperror</LI>"; 
}

if ($subjecterror == "1")
{ $err .= "<LI>$strNo <b>$strSubject</b></LI>"; }

if ($messageerror == "1")
{ $err .= "<LI>$strNo <b>$strMessage</b></LI>"; }

$err .= "</font></UL>";

      }
}

?> 
      <?php
  
 if($done_mailing != 1)
 {
 if($aid != "site" && $aid != "all")
 $albwht = "$strAlbum";

 $usr->Header($Config_SiteTitle ." :: $strMenusTell");
 echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/tell.gif>&nbsp;</div>");

?> 
<br><br>
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr> 
    <td class=tn align=center bgcolor=#CCCC99><font color="#000000"><?php echo "$strMenusTellb $albwht"; ?><br>"<b><?php echo $row[aname] ?></b>"</font></td>
  </tr>
</table>
				<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
				  <tr> 
				   <td class=tn>
                            <p class=tn><?php echo $err; ?></p>
<p>&nbsp;</p>
                              <form name="sugg" action=<?php echo $PHP_SELF; ?> method=POST>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                                  <tr bgcolor="#333333"> 
                                    <td height="187"> 
                                      <table width="100%" border="0" cellspacing="1" cellpadding="3" align="center">
                                        <tr> 
                                          <td bgcolor="#CCCCCC" class="tn"><?php echo $strName ?> </td>
                                          <td bgcolor="#006699" width="69%" class=tn> 
							<font color=#ffffff><?php echo $userinfo_nr[uname] ?></font>
                                          </td>
                                        </tr>
                                        <tr> 
                                          <td bgcolor="#CCCCCC" class="tn"><?php echo $strEmail ?> </td>
                                          <td bgcolor="#006699" width="69%" class=tn> 
							<font color=#ffffff><?php echo $userinfo_nr[email] ?></font>
                                          </td>
                                        </tr>
                                        <tr bgcolor=#dddddd> 
                                          <td colspan="2" class="tn"><?php echo $strTellMultiple ?></td>
                                        </tr>
							<?php echo $private_ask ?>
                                        <tr> 
                                          <td bgcolor="#CCCCCC" class="tn"><?php echo("$strFriend $strName"); ?></td>
                                          <td bgcolor="#006699" width="69%"> 
                                    <input type=text name=recname size="35" class=fieldsg value="<?php echo $recname; ?>" >
                                          </td>
                                        </tr>
                                        <tr> 
                                          <td bgcolor="#CCCCCC" class="tn"><?php echo("$strFriend $strEmail"); ?></td>
                                          <td bgcolor="#006699" width="69%"> 
                                  <input type=text name=recemail size="35" class=fieldsg value="<?php echo $recemail; ?>" >
                                          </td>
                                        </tr>
                                        <tr> 
                                          <td bgcolor="#CCCCCC" class="tn"><?php echo $strMessage ?> </td>
                                          <td bgcolor="#006699" height="27" width="69%"> 
                                        <textarea name=message cols=34 rows=5 class=fieldsg><?php echo $message; ?></textarea>
                                          </td>
                                        </tr>
                                        <tr bgcolor="#999999"> 
                                          <td colspan=2 height="27" class="tn"> 
                                            <div align="right"> 
                                              <input type=hidden name=action value="sendmail">
                                              <input type=hidden name=aid value="<?php echo $aid ?>">
                                              <input type=hidden name=l_confirm value="1">
                                              <input type=submit value="<?php echo $strSend ?> &gt;&gt;" name="submit">
                                            </div>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                                <br>
                              </form>

					</td></tr></table>
<?php 
}

	      closeDB();
		
		if($done_mailing != 1)
            $usr->Footer();


if($l_confirm != 1)
{
		exit;
}


else if($error !=1 && $done_mailing == 1)
{
		if($aid != "site")
		{ $sugalb = ", <a href=album_view.php>$strNext</a><br><br><a href=tell.php?aid=site>$strMenusTell, $Config_sitename</a>"; }

     	      $usr->Header($Config_SiteTitle ." :: $strMenusTell");
		echo ("<br>");
	      $errMsg = "<b>$strTellSent</b>$sugalb\n";
		
		if($aid != "site")
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '80' );

		else
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '50' );

            $usr->Footer();
		exit;
}
?>