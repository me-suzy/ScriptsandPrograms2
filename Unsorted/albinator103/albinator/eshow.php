<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

	#remove old ecards in list
	$lastdate  = mktime (0,0,0,date("m"),date("d")-$Config_ecard_days,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);
	$ik = 0;	
	
	$result_del = queryDB( "DELETE FROM $tbl_ecards WHERE makedate < '$curdate'" );
	############################

      if ( !$ucook->LoggedIn() )
	{ $ShowHeader = "HeaderOut"; $ShowFooter = "FooterOut"; }
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

	if(!$id || !$code)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/ecards2.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strESHOWCmt2</b>\n";
       $usr->errMessage( $errMsg, $strESHOWCmt1, 'error', '70' );
   	 $usr->$ShowFooter();

	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_ecards WHERE ecid = '$id' && code = '$code'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/ecards2.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strESHOWCmt2 $strESHOWCmt3</b>\n";
       $usr->errMessage( $errMsg, $strESHOWCmt1, 'error', '70' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	$row = mysql_fetch_array( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$row[uid]'" );
	$row_user = mysql_fetch_array( $result_user );

	$result_pic = queryDB( "SELECT * FROM $tbl_pictures WHERE pid = '$row[pic]'" );
	$row_pic = mysql_fetch_array( $result_pic );

	$nr = mysql_num_rows( $result_pic );

	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/ecards2.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strESHOWCmt4</b>\n";
       $usr->errMessage( $errMsg, $strESHOWCmt5, 'error', '70' );
	
	 $fromus = 1;
      }

if($fromus != 1)
{
	if($row[notify] == "1")
	{
	$name = "$Config_adminname";
	$email = "$adminemail";
	$subject = "$strESHOWCmt6";
	$recnameto = $row_user[uname];
	$recemailto = $row_user[email];
	$premessage = $csr->LangConvert($strESHOWCmt7, $row_user[uname], $row[rec_name], $Config_sitename);
	$endmessage = "\n$Config_msgfooter";
	$sendmessage = "$premessage\n\n$endmessage";
	$mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");

	$result_update = queryDB( "UPDATE $tbl_ecards SET notify='0' WHERE ecid='$id'" );
	}

      $usr->$ShowHeader($Config_SiteTitle ." :: $strMenusEcards");
	echo("<p>&nbsp;</p>\n<div align=center class=tn>$strESHOWCmt8</div>\n<p>&nbsp;</p>");

	if($row[music] != 0)
	echo("<bgsound src=\"$dirpath"."ecards/music/$row[music]\" loop=1>");

      list ($fontcolor, $bgcolor) = split ('[|]', $row[colors]);

	if($row_pic[i_used])
	{
	  $fullsize = 1;
	  $DIRR = "full_";
	}

	$row[message] = stripslashes($row[message]);
	$row[message] = htmlspecialchars($row[message], ENT_QUOTES);

?>

<table width="85%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#CCCC99">
  <tr <?php if($bgcolor == "1") echo ("background=$Config_main_bgimage bgcolor=$Config_main_bgcolor"); else echo("bgcolor=$bgcolor"); ?>> 
    <td <?php if($bgcolor == "1") echo ("background=$Config_main_bgimage bgcolor=$Config_main_bgcolor"); else echo("bgcolor=$bgcolor"); ?>> 
      <p align="center"><br>
        <?php if($fontcolor == 1) echo("To $row[rec_name]"); else echo("<font color=$fontcolor>$strTo $row[rec_name]</font>"); ?></p>
      <p align="center"><img src=<?php echo "$dirpath"."$Config_datapath/$row[uid]/$DIRR"."$row_pic[pname]"; ?> <?php echo $sizeval ?>></p>
	<br>
      <p align="center"><i><?php if($fontcolor == 1) echo("&quot;$row[message]&quot;"); else echo("<font color=$fontcolor>&quot;$row[message]&quot;</font>"); ?></i></p>
      <p align="center">&nbsp;</p>
      <p align="center"><?php if($fontcolor == 1) echo("from $row_user[uname]"); else echo("<font color=$fontcolor>$strFrom $row_user[uname]</font>"); ?></p>
      </td>
  </tr>
</table>
<?php

	echo("<p>&nbsp;</p>");
	echo($csr->LangConvert($strShowAbuse, $Config_abuse_link));
	echo("<p>&nbsp;</p>");
                 
}

else
{
	echo("<p>&nbsp;</p><div align=center class=tn>$strESHOWCmt8</div>");

?>

<br><div align=center class=tn><?php echo $strESHOWCmt10 ?></div>
<br>
<table width="85%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#CCCC99">
  <tr background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>"> 
    <td background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>"> 
      <p align="center"><br>
        <?php echo ("$strTo $row[rec_name]"); ?></p>
      <p align="center"><img src="<?php echo $dirpath.$Config_imgdir ?>/ourcard.gif"></p>
	<br>
      <p align="center"><i><?php echo $strESHOWCmt9 ?></i></p>
      <p align="center">&nbsp;</p>
      <p align="center"><?php echo "$strFrom $Config_adminname, $Config_sitename"; ?></p>
      </td>
  </tr>
</table>
<p>&nbsp;</p>

<?php
}
	echo("<br><br><div align=center class=tn>$strESHOWCmt8</div>");
  	$usr->$ShowFooter();
	exit;
?>