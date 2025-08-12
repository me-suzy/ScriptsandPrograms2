<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
	{ $ShowHeader = "HeaderOut"; $ShowFooter = "FooterOut"; }
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

$usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
echo("<p class=\"tn\" align=\"right\">\n
<img src=\"$dirpath$Config_imgdir/{$Config_LangLoad}_headers/searchalb.gif\" width=\"243\" height=\"44\">&nbsp;<br>");


if($dowhat == "rpass")
{
	if($confirm == 1)
	{
	if(!$name)
      $errMsg = "<b>$strReqPass1</b>";

	if(!$email || !CheckEmail($email))
      $errMsg .= "<br><b>$strReqPass2</b>";

	if(!$comments)
      $errMsg .= "<br><b>$strNo $strComment$strPuralS</b>";
	}

	if($confirm != 1 || $errMsg)
	{
	if($errMsg)
	$usr->errMessage( $errMsg, $strError );

	$result = queryDB( "SELECT uname FROM $tbl_userinfo WHERE uid = '$uuid'" );
	$row = mysql_fetch_array( $result );
?>	

<p>&nbsp;</p>
<div class="ts" align=center><b><?php echo $strReqPass3 ?></b></div>
<form action=show.php method=post>
<table width="75%" border="0" cellspacing="1" cellpadding="0" align="center" bgcolor="#333333">
  <tr bgcolor="#333333"> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr bgcolor="#DDDDDD"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo ("$strAlbum $strOwner"); ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td class=tn><?php echo $row[uname] ?></td>
        </tr>
        <tr bgcolor="#eeeeee"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo $strName ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td class=tn><input type=text name=name value="<?php echo $HTTP_POST_VARS['name'] ?>" maxlength=25></td>
        </tr>
        <tr bgcolor="#dddddd"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo $strEmail ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td class=tn><input type=text name=email value="<?php echo $HTTP_POST_VARS['email'] ?>" maxlength=50></td>
        </tr>
        <tr bgcolor="#eeeeee"> 
          <td width="30%" class="tn"> 
            <div align="right"><?php echo ("$strComment$strPuralS"); ?></div>
          </td>
          <td width="2%">&nbsp;</td>
          <td> 
            <textarea name="comments" rows="5" cols="30"><?php echo $HTTP_POST_VARS['comments'] ?></textarea>
          </td>
        </tr>
        <tr bgcolor="#eeeeee">
          <td width="30%" class="tn">&nbsp;</td>
          <td width="2%">&nbsp;</td>
          <td>
		  <input type=hidden name=dowhat value=<?php echo $dowhat ?>>
		  <input type=hidden name=confirm value=1>
		  <input type=hidden name=uuid value=<?php echo $uuid ?>>
		  <input type=hidden name=aid value=<?php echo $aid ?>>
		  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>                  


<?php
$usr->$ShowFooter();
exit;
	}


$result = queryDB( "SELECT uname, email FROM $tbl_userinfo WHERE uid = '$uuid'" );
$row = mysql_fetch_array( $result );

$result = queryDB( "SELECT aname FROM $tbl_albumlist WHERE aid = '$aid'" );
$row_alb = mysql_fetch_array( $result );

$recnameto  = $row[uname];
$recemailto = $row[email];

$subject = "$Config_systemname :: $strReqPass4";

$premessage = $csr->LangConvert($strReqPass5, $row[uname], $row_alb[aname], $name, $email, $comments);
$endmessage = "$msgfooter";
$sendmessage = "$premessage $endmessage";

$csr->PublicList($name, $email, 'system');

$mailheader = "From: $Config_adminname <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

       $errMsg = "<b>$strSent</b>, <a href=show.php>$strView $strAlbum$strPuralS</a>\n";
       $usr->errMessage( $errMsg, '', 'tick' );
}

else
{

?>

</p><p>&nbsp;</p>
<table width="95%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr bgcolor="#eeeeee"> 
    <td colspan="2" class="ts" align="right"><?php echo $strShow2 ?>&nbsp;</td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr bgcolor=#DDDDDD> 

    <td width="50%"> 
      <table width="90%" border="0" cellspacing="2" cellpadding="2" align="center">
	<form action=showalbum.php method=post>
        <tr> 
          <td width="28%" class="tn"> 
            <div align="right"><?php echo("$strAlbum $strID"); ?></div>
          </td>
          <td width="72%"> 
            <input type="text" name="aid" maxlength="10" class="fieldsb2">
          </td>
        </tr>
        <tr> 
          <td width="28%" class="tn"> 
            <div align="right"><?php echo $strUsername ?></div>
          </td>
          <td width="72%"> 
            <input type="text" name="uuid" maxlength="15" class="fieldsb2">
            <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_view.gif" width=20 height=14 border=0 value="<?php echo $strSearch ?> &gt;&gt;">
          </td>
        </tr>
     </form>
      </table>
    </td>

    <td width="50%"> 
      <table width="90%" border="0" cellspacing="2" cellpadding="2" align="center">
        <form action=showlist.php method=post>
        <tr> 
          <td width="28%" class="tn"> 
            <div align="right"><?php echo $strUsername ?></div>
          </td>
          <td width="72%"> 
            <input type=hidden name=dowhat value=user>
            <input type="text" name="uuid" maxlength="15" class="fieldsb2">
            <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_search.gif" width=17 height=16 border=0 value="<?php echo $strSearch ?> &gt;&gt;">
          </td>
        </tr>
	  </form>
      <form action=showlist.php method=post>
        <tr> 
          <td width="28%" class="tn"> 
            <div align="right"><b>or</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $strEmail ?></div>
          </td>
          <td width="72%"> 
            <input type=hidden name=dowhat value=email>
            <input type="text" name="email_id" maxlength=150 class="fieldsb2">
            <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_search.gif" width=17 height=16 border=0 value="<?php echo $strSearch ?> &gt;&gt;">
          </td>
        </tr>
	  </form>
      <form action=showlist.php method=post>
        <tr> 
          <td width="28%" class="tn"> 
            <div align="right"><b>or</b>&nbsp; <?php echo $strRegisterName3 ?></div>
          </td>
          <td width="72%"> 
            <input type=hidden name=dowhat value="country">
            <input type="text" name="country_id" maxlength=150 class="fieldsb2">
            <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_search.gif" width=17 height=16 border=0 value="<?php echo $strSearch ?> &gt;&gt;">
          </td>
        </tr>
	  </form>
      </table>
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td colspan="2">&nbsp; </td>
  </tr>
</table>

	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center">
      <form action=showlist.php method=post>
  	  <tr bgcolor="#EEEEEE"> 
          <td align="center" class="tn"> 
            <?php echo $strRegisterName1 ?> <input type="hidden" name="dowhat" value="realname">
            <input type="text" name="real_name" size="30" maxlength="25" class="fieldsb2">
            <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_search.gif" width=17 height=16 border=0 value="<?php echo $strSearch ?> &gt;&gt;">
          </td>
        </tr>
  	  <tr bgcolor="#EEEEEE"> 
          <td align="center" class="ts"> 
		<input type="radio" name="bool" value="1"> <?php echo $strShow11 ?>
		<input type="radio" name="bool" value="2" checked> <?php echo $strShow12 ?>
          </td>
        </tr>
	 </form>
      </table>

<br><br>
<?php
}

$usr->$ShowFooter();

?>