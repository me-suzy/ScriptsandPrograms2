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


	if($send == "listen")
	{
?>

<html>
<head>
<title><?php echo "$Config_sitename :: $strListen"; ?></title>
<link rel="stylesheet" HREF="<?php echo("{$dirpath}essential/{$Config_LangLoad}_default.css"); ?>" type="text/css">
</head>
<body bgcolor=#000040>
<div align=center class=tn><font color=#ffffff>
<?php
		if($music == "0" || !$music)
		{ echo("<div align=center class=tn>$strEcardErr1</div>"); }
		else if($music)
		{ echo("$strMusicNotice <bgsound src=\"$dirpath"."music/$music\" loop=0>"); }
?>

<br><br><span class=ts>[<a href=javascript:self.close()><font color=#ffffff><?php echo $strClose ?></font></a>]</span></font>
</div>

</body>
</html>

<?php

exit;

	}

       $usr->Header($Config_SiteTitle .' :: '.$strMenusEcards);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/ecards.gif>&nbsp;</div><br>");


	if(!$aid && !$pid)
	{
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr6, <a href=index.php>$strCreate</a>...</b>\n<br>";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }
	mysql_free_result( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	
?>
<br>
<div align=center><?php echo ($csr->LangConvert($strSelectAlbum, "$strEcardErr2")); ?></div><br><br>
<form action="ecards.php" method="post">
<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo("$strAlbumCrErr16 "); ?>
        <select name="aid">
<?php
		while($row = mysql_fetch_array( $result_user ))
		{
			echo("<option value=$row[aid]>".stripslashes($row[aname])."</option>\n");
		}
?>
        </select>
<input type=submit name=submit value="<?php echo $strNext ?> &gt;" class="butfieldc">
      </div>
    </td>
  </tr>
</table>
</form>	

<p>&nbsp;</p>
<?php
	}

	else if(!$pid && $aid)
	{
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=ecards.php>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	$rs = new PagedResultSet("SELECT * FROM $tbl_pictures WHERE aid = '$aid' ORDER BY pindex, pname",$Config_maxshow);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("aid=$aid");

	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr17, <a href=upload.php>$strAdd</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }


	echo("<div class=tn align=center><br>\n$strSelectPhotoEcard<br><span class='ts'>$nav</span></div><br>\n");
	echo("<div class=tn align=center>\n<table width=600 cellpadding=4 cellspacing=4 align=center>\n");

	$result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=1";
	else
	$borderval = "border=0";


	$i = -1;
      $total = 0;

		while($row = $rs->fetchArray())
		{
	 	 $total++;
		 if($i == 3 || $i == -1)
		 { $i = 0; echo("\n<tr>"); $messagebar = "\n<tr>\n"; }
		 else
		 { $i++; }
		
		 $picurl = "$dirpath"."$Config_datapath/$uid/tb_$row[pname]";
		 error_reporting(0);
   	       $size = GetImageSize ("$picurl");
		 error_reporting(E_ERROR | E_WARNING);

  		 echo("\n<td align=center valign=bottom><a href=\"ecards.php?aid=$aid&pid=$row[pid]\"><img src=\"$picurl\" $borderval $size[3]></a></td>");
		 $messagebar .= "\n<td class=ts align=center valign=bottom><a href=\"ecards.php?aid=$aid&pid=$row[pid]\">$strSend</a></td>";

		 if($i == 3)
		 { echo("\n</tr>$messagebar\n</tr><tr><td colspan=4 height=4>&nbsp;</td></tr>\n"); }
		}

	if($total < 4)
	{ echo("\n</tr>$messagebar\n</tr>"); }
	else if($total%4 != 0)
	{ 
	  $i++;
	  if($i%2 == 0)
	  { echo("\n<td colspan=2>&nbsp;</td>\n</tr>$messagebar<td colspan=2>&nbsp;</td>\n</tr>"); }
	  else if($i%3 == 0)
	  { echo("\n<td>&nbsp;</td>\n</tr>$messagebar\n<td>&nbsp;</td>\n</tr>"); }
	  else
	  { echo("\n<td colspan=3>&nbsp;</td>\n</tr>$messagebar\n<td colspan=3>&nbsp;</td>\n</tr>"); }
      }

	echo("\n\n</table>\n\n</div>\n");

?>

<br>
<div align=center><?php echo ("<span class='ts'>$nav</span>"); ?><p><a href=ecards.php>&lt;&lt; <?php echo $strBackAlbumSelect ?></a></div>

<p>&nbsp;</p>

<?php
	}

else if($make_show != 1 && $send_now != 1)
{
	if(!$aid)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	if(!$pid)
  	{
       $errMsg = "<b>$strAlbumCrErr18, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
	$row = mysql_fetch_array( $result );

	$today = getdate(); 

	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if($row[i_used])
	{
	$fullsize = 1;
	$DIRR = "full_";
	}
?>


<p>&nbsp;</p>
<table width="85%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#CCCC99">
  <tr background="<?php echo $Config_main_bgimage; ?>" bgcolor="<?php echo $Config_main_bgcolor; ?>"> 
    <td background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>"> 
      <p align="center"><br>
        <?php echo ("$strTo $strEcardErr4") ?></p>
      <p align="center"><img src=<?php echo "$dirpath"."$Config_datapath/$uid/$DIRR"."$row[pname]"; ?>></p>
      <p align="center">&nbsp;</p>
      <p align="center">&quot;<i><?php echo $strMessage ?></i>&quot;</p>
      <p align="center">&nbsp;</p>
      <p align="center"> <?php echo ("$strFrom $row_user[uname]"); ?></p>
      </td>
  </tr>
</table>
<p>&nbsp;</p>
<form method=post action=ecards.php name=ecard>
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td width="30%" class="tn"> 
      <a href="ecards.php?aid=<?php echo ("$aid\">&lt;&lt; $strChange"); ?></a><br><br>
    </td>
    <td width="70%"> 
      &nbsp;
    </td>
  </tr>
  <tr> 
    <td bgcolor="#dddddd" width="30%" class="tn"> 
      <div align="right"><?php echo("$strEcardErr4"); ?>&nbsp;</div>
    </td>
    <td bgcolor="#dddddd" width="70%"> 
      <input type="text" name="rec_name[]" size="38" maxlength="50">
    </td>
  </tr>
  <tr> 
    <td bgcolor="#eeeeee" width="30%" class="tn"> 
      <div align="right"><?php echo("$strEcardErr5"); ?>&nbsp;</div>
    </td>
    <td bgcolor="#eeeeee" width="70%"> 
      <input type="text" name="rec_email[]" size="38" maxlength="100">
    </td>
  </tr>
  <tr> 
    <td bgcolor="#dddddd" width="30%" class="tn"> 
      <div align="right"><?php echo $strMessage ?>&nbsp;</div>
    </td>
    <td bgcolor="#dddddd" width="70%"> 
      <textarea name="message" rows="5" cols="32"></textarea>
    </td>
  </tr>
  <tr bgcolor="#eeeeee"> 
    <td width="30%" class="tn"> 
      <div align="right">&nbsp;</div>
    </td>
    <td width="70%" class="tn"><i><?php echo ("$strText $strColor"); ?> 
      <select name="fontcolor">
	<option value="1" selected><?php echo $strDefault ?></option>
        <option value="#ffffff">white</option>
        <option value="#000000">black</option>
	<option value="#000080">blue</option>
	<option value="#008000">green</option>
	<option value="#C00000">red</option>
	<option value="#ffff00">yellow</option>
	<option value="#d4d4d4">gray</option>
	<option value="#a52a2a">brown</option>
	<option value="#8a2be2">blueviolet</option>
	<option value="#5f9ea0">cadetblue</option>
	<option value="#d2691e">chocolate</option>
	<option value="#00ffff">cyan</option>
	<option value="#ffd700">gold</option>
	<option value="#adff2f">greenyellow</option>
	<option value="#f0e68c">khaki</option>
	<option value="#e6e6fa">lavender</option>
	<option value="#fffacd">lemonchiffon</option>
	<option value="#add8e6">lightblue</option>
	<option value="lightslategray">lightslategray</option>
	<option value="#ff00ff">magenta</option>
	<option value="#800000">maroon</option>
	<option value="#00ff00">lime</option>
	<option value="#ffe4e1">mistyrose</option>
	<option value="#000080">navy</option>
	<option value="#da70d6">orchid</option>
	<option value="#ff4500">orange</option>
	<option value="#800080">purple</option>
	<option value="#f5deb3">wheat</option>
	<option value="#800080">violet</option>
	<option value="#4682b4">steelblue</option>
	<option value="#d2b48c">tan</option>
      </select>
      &nbsp;&nbsp;<?php echo $strBackground ?></I>
      <select name="bgcolor">
	<option value="1" selected><?php echo $strDefault ?></option>
      <option value="#ffffff">white</option>
      <option value="#000000">black</option>
	<option value="#000080">blue</option>
	<option value="#008000">green</option>
	<option value="#C00000">red</option>
	<option value="#ffff00">yellow</option>
	<option value="#d4d4d4">gray</option>
	<option value="#a52a2a">brown</option>
	<option value="#8a2be2">blueviolet</option>
	<option value="#5f9ea0">cadetblue</option>
	<option value="#d2691e">chocolate</option>
	<option value="#00ffff">cyan</option>
	<option value="#ffd700">gold</option>
	<option value="#adff2f">greenyellow</option>
	<option value="#f0e68c">khaki</option>
	<option value="#e6e6fa">lavender</option>
	<option value="#fffacd">lemonchiffon</option>
	<option value="#add8e6">lightblue</option>
	<option value="lightslategray">lightslategray</option>
	<option value="#ff00ff">magenta</option>
	<option value="#800000">maroon</option>
	<option value="#00ff00">lime</option>
	<option value="#ffe4e1">mistyrose</option>
	<option value="#000080">navy</option>
	<option value="#da70d6">orchid</option>
	<option value="#ff4500">orange</option>
	<option value="#800080">purple</option>
	<option value="#f5deb3">wheat</option>
	<option value="#800080">violet</option>
	<option value="#4682b4">steelblue</option>
	<option value="#d2b48c">tan</option>
      </select>
    </td>
  </tr>
  <tr bgcolor="#dddddd"> 
    <td class="tn" width="30%"> 
      <div align="right"><?php echo $strMusic ?>&nbsp;</div>
    </td>
    <td width="70%"> 
      <select name="music">
	<option value="ecards.php?send=listen&music=0" selected>none</option>
	<option value="ecards.php?send=listen&music=6.mid">Abba :: Dancing Queen</option>
	<option value="ecards.php?send=listen&music=5.mid">American Women</option>
	<option value="ecards.php?send=listen&music=2.mid">Fundu Song</option>
	<option value="ecards.php?send=listen&music=1.mid">Happy Birthday</option>
	<option value="ecards.php?send=listen&music=10.mid">Hotel California</option>
	<option value="ecards.php?send=listen&music=14.mid">James Bond</option>
	<option value="ecards.php?send=listen&music=7.mid">Pink Panther</option>
	<option value="ecards.php?send=listen&music=4.mid">We wish you a merry chirstmas</option>
	<option value="ecards.php?send=listen&music=8.mid">We will rock you</option>
	<option value="ecards.php?send=listen&music=3.mid">Santa Claus is coming to town</option>
	<option value="ecards.php?send=listen&music=9.mid">ScobbyDo Where are you?</option>
	<option value="ecards.php?send=listen&music=13.mid">Star Wars</option>
	<option value="ecards.php?send=listen&music=11.mid">X-Files Theme</option>
	<option value="ecards.php?send=listen&music=12.mid">YMCA</option>
      </select>
	<a href="" onclick="return ListenWindow()" target="ListenMusic"><img src="<?php echo "$dirpath{$Config_imgdir}/{$Config_LangLoad}_headers/listen.gif\" alt=\"$strListen\""; ?> border="0" width="41" height="10"></a>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#eeeeee" width="30%" class="tn"> 
      <div align="right"><?php echo("$strEcardErr3"); ?></div>
    </td>
    <td bgcolor="#eeeeee" width="70%" class="tn"> 
    <select name="send_year">
<?php
	$today_year = $today['year'];
	echo("      <option value=\"$today_year\" selected>$today_year</option>\n");
	$today_year++;
	echo("      <option value=\"".$today_year."\">".$today_year."</option>\n");

?>
    </select>
      <select name="send_month">
<?php
	$today_month = $today['mon'];

	for($i=1;$i<=12;$i++)
	{
	if($today_month == $i)
	echo("      <option value=\"$i\" selected>$date_show[$i]</option>\n");
	else
	echo("      <option value=\"$i\">$date_show[$i]</option>\n");
	}

?>
      </select>
      <select name="send_date">
<?php
	$today_date = $today['mday'];

	for($i=1;$i<=31;$i++)
	{
	if($today_date == $i)
	echo("      <option value=\"$i\" selected>$i</option>\n");
	else
	echo("      <option value=\"$i\">$i</option>\n");
	}

?>
      </select>
      </td>
  </tr>
  <tr> 
    <td width="30%" class=tn bgcolor="#dddddd">&nbsp;</td>
    <td class=tn bgcolor="#dddddd">
      <input type="checkbox" name="inform[]" value="1">&nbsp;<?php echo $strInformPick ?>
  </tr>
  <tr> 
    <td bgcolor="#eeeeee" width="30%" class="tn"> 
      <div align="right"></div>
    </td>
    <td bgcolor="#eeeeee" width="70%" class="tn"> 
      <input type="checkbox" name="multiple" value="1">&nbsp;<?php echo $strSendMultiple ?>
      </td>
  </tr>
  <tr> 
	<td>&nbsp;</td>
    <td class="tn"><br>
  <input type=hidden name=pid value=<?php echo $pid ?>>
  <input type=hidden name=aid value=<?php echo $aid ?>>
  <input type=hidden name=make_show value=1>
  <input type="submit" name="send" value="<?php echo $strPreview ?>" class="butfieldc">
  <input type="submit" name="send" value="<?php echo $strSend ?>" class="butfieldb"></td>
    </td>
  </tr>
</table>
</form>
<script>
function ListenWindow() {

ListenMusic=window.open( document.ecard.music.value ,"ListenMusic","status=no,resize=no,toolbar=no,scrollbars=no,width=200,height=100,maximize=no");
ListenMusic.moveTo(200,200)

return false
}
//-->
</script>
<p>&nbsp;</p>

<?php
}

else if($make_show == 1 && $send_now != 1 && $send != "send" && $send != "listen")
{

	$music = eregi_replace("ecards.php", "", $music);
	$music = eregi_replace("send=listen&music=", "", $music);
	$music = eregi_replace("\?", "", $music);

	$message = stripslashes($message);
	$message = htmlspecialchars($message, ENT_QUOTES);

	$message = strip_tags($message, '<b><i>');

	$rec_name[0] = strip_tags($rec_name[0]);

	if(!$aid)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	if(!$pid)
  	{
       $errMsg = "<b>$strAlbumCrErr18, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	if(!$rec_name[0])
  	{
       $errMsg = "<b>$strEcardErr4, <a href=javascript:history.back(-1);>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }

	$result = CheckEmail($rec_email[0]);
	if(!$result || !$rec_email[0])
  	{
       $errMsg = "<b>$strEcardErr5 $strInvalid, <a href=javascript:history.back(-1);>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }

	if(!$message)
  	{
       $errMsg = "<b>$strMessage, <a href=javascript:history.back(-1);>$strBack</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }

	if($send_date < 10 && !preg_match("/0/", $send_date))
	$send_date = "0$send_date";

	if($send_month < 10 && !preg_match("/0/", $send_month))
	$send_month = "0$send_month";

	$date_send = "$send_year"."$send_month"."$send_date";
	$curdate = date("Ymd");

	if(!checkdate($send_month, $send_date, $send_year) || $date_send < $curdate)
	{
	$errMsg ="<br><b>$strInvalidDate, <a href=javascript:history.back(-1)>$strRetry</a></b>";
	$usr->errMessage( $errMsg, $strError, 'error', '70' );
	echo("<br>");
   	$usr->Footer();
	exit;
	}

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
	$row = mysql_fetch_array( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if($music != 0)
	{ echo("<bgsound src=\"$dirpath"."music/$music\" loop=1>"); }

	$message = nl2br($message);

	if($row[i_used])
	{
	$fullsize = 1;
	$DIRR = "full_";
	}
?>	

<p>&nbsp;</p>
<div align=center>
<form action=ecards.php method=post>
  <input type=hidden name=fontcolor value="<?php echo "$fontcolor" ?>">
  <input type=hidden name=bgcolor value="<?php echo "$bgcolor" ?>">
  <input type=hidden name=rec_name[] value="<?php echo $rec_name[0] ?>">
  <input type=hidden name=rec_email[] value="<?php echo $rec_email[0] ?>">
  <input type=hidden name=music value="<?php echo $music ?>">
  <input type=hidden name=inform[] value="<?php echo $inform[0] ?>">
  <input type=hidden name=message value="<?php echo $message ?>">
  <input type=hidden name=multiple value="<?php echo $multiple ?>">
  <input type=hidden name=pid value="<?php echo $pid ?>">
  <input type=hidden name=send_year value="<?php echo $send_year ?>">
  <input type=hidden name=send_month value="<?php echo $send_month ?>">
  <input type=hidden name=send_date value="<?php echo $send_date ?>">
  <input type=hidden name=aid value="<?php echo $aid ?>">
  <input type=hidden name=send_now value=1>
  <a href="javascript:history.back(-1);"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/change.gif" width="53" height="19" border="0"></a>
  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0">
</form>
</div>
<table width="85%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#CCCC99">
  <tr <?php if($bgcolor == 1) echo ("background=$Config_main_bgimage bgcolor=$Config_main_bgcolor"); else echo("bgcolor=$bgcolor"); ?>> 
    <td <?php if($bgcolor == 1) echo ("background=$Config_main_bgimage bgcolor=$Config_main_bgcolor"); else echo("bgcolor=$bgcolor"); ?>> 
      <p align="center"><br>
        <?php if($fontcolor == 1) echo("To $rec_name[0]"); else echo("<font color=$fontcolor>To $rec_name[0]</font>"); ?></p>
      <p align="center"><img src=<?php echo "$dirpath"."$Config_datapath/$uid/$DIRR"."$row[pname]"; ?> <?php echo $sizeval ?>></p>
	<br>
      <p align="center"><i><?php if($fontcolor == 1) echo("&quot;$message&quot;"); else echo("<font color=$fontcolor>&quot;$message&quot;</font>"); ?></i></p>
      <p align="center">&nbsp;</p>
      <p align="center"><?php if($fontcolor == 1) echo("$strFrom $row_user[uname]"); else echo("<font color=$fontcolor>$strFrom $row_user[uname]</font>"); ?></p>
      </td>
  </tr>
</table>
<div align=center>
<form action=ecards.php method=post>
  <input type=hidden name=fontcolor value="<?php echo "$fontcolor" ?>">
  <input type=hidden name=bgcolor value="<?php echo "$bgcolor" ?>">
  <input type=hidden name=rec_name[] value="<?php echo $rec_name[0] ?>">
  <input type=hidden name=rec_email[] value="<?php echo $rec_email[0] ?>">
  <input type=hidden name=music value="<?php echo $music ?>">
  <input type=hidden name=inform[] value="<?php echo $inform[0] ?>">
  <input type=hidden name=message value="<?php echo $message ?>">
  <input type=hidden name=multiple value="<?php echo $multiple ?>">
  <input type=hidden name=send_year value="<?php echo $send_year ?>">
  <input type=hidden name=send_month value="<?php echo $send_month ?>">
  <input type=hidden name=send_date value="<?php echo $send_date ?>">
  <input type=hidden name=pid value="<?php echo $pid ?>">
  <input type=hidden name=aid value="<?php echo $aid ?>">
  <input type=hidden name=send_now value=1>
  <a href="javascript:history.back(-1);"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/change.gif" width="53" height="19" border="0"></a>
  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0">
</form>
</div>
<p>&nbsp;</p>

<?php
}


else if($send_now == 1 || $send == "send")
{

	$music = eregi_replace("ecards.php", "", $music);
	$music = eregi_replace("send=listen&music=", "", $music);
	$music = eregi_replace("\?", "", $music);

	$rec_name[0] = strip_tags($rec_name[0]);
	$message = strip_tags($message, '<b><i>');

	if ( $HTTP_POST_VARS["aid"] == '' || $HTTP_POST_VARS["pid"] == '' )
	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
	}

	if(!$aid)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	if(!$pid)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=ecards.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	if($multiple == 2)
	{ $val = "1"; }
	else
	{ $val = ""; }

    if($multiple != "2")
    {
	if(!$rec_name[0])
  	{
       $errMsg = "<b>$strEcardErr4 $val, <a href=javascript:history.back(-1);>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }

	$result = CheckEmail($rec_email[0]);
	if(!$result || !$rec_email[0])
  	{
       $errMsg = "<b>$strEcardErr5 $val $strInvalid, <a href=javascript:history.back(-1);>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }

	if(!$message)
  	{
       $errMsg = "<b>$strMessage $val, <a href=javascript:history.back(-1);>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strMissingField, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }
    }

	if($multiple == 1)
	{
	$i = 0;
	if(!$showmore || $showmore < 1 || !is_numeric($showmore))
	$showmore = $Config_show_min;
?>

<form action="ecards.php" method="post">
<table width="70%" border="0" cellspacing="2" cellpadding="3" align="center">

<?php

	while($i < $showmore)
	{

if($showmore > 5 && $i == 0)
{
?>
<tr><td>&nbsp;</td><td colspan=2 class=ts>
<br><input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0"><br><br>
</td></tr>
<?php
}
?>

  <tr> 
    <td width="30%" class="tn"> 
      <div align="right"><?php echo("$strEcardErr4"); echo ($i+1); ?>&nbsp;</div>
    </td>
    <td><input name="rec_name[]" type="text" maxlength=50 size=24 value="<?php if($i == 0) echo $rec_name[0] ?>"></td>
  </tr>
  <tr> 
    <td width="30%" class="tn"> 
      <div align="right"><?php echo $strEmail ?>&nbsp;</div>
    </td>
    <td><input name="rec_email[]" type=text maxlength=150 size=24 value="<?php if($i == 0) echo $rec_email[0] ?>"></td>
  </tr>
  <tr> 
    <td width="30%" class=tn>&nbsp;</td>
    <td class=tn>
      <input type="checkbox" name="inform[<?php echo $i ?>]" value="1" <?php if($i == 0) { if($inform[0] == 1) echo "checked"; } ?>>&nbsp;<?php echo("$strInformPick"); ?>
  </tr>
  <tr>
    <td width="30%" class="tn" valign=top> 
      &nbsp;
    </td>
    <td>&nbsp;</td>
  </tr>
	

<?php
	$i++;
	}
?>
<tr><td>&nbsp;</td><td colspan=2 class=ts>
<input type=hidden name=fontcolor value="<?php echo "$fontcolor" ?>">
<input type=hidden name=bgcolor value="<?php echo "$bgcolor" ?>">
<input type=hidden name=music value="<?php echo $music ?>">
<input type=hidden name=message value="<?php echo $message ?>">
<input type=hidden name=pid value="<?php echo $pid ?>">
<input type=hidden name=aid value="<?php echo $aid ?>">
<input type=hidden name=multiple value="2">
<input type=hidden name=send_now value=1>
<input type=hidden name=send_year value="<?php echo $send_year ?>">
<input type=hidden name=send_month value="<?php echo $send_month ?>">
<input type=hidden name=send_date value="<?php echo $send_date ?>">
<input type="hidden" name="aid" value="<?php echo $aid ?>">
<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0">
</td></tr>
</table>
</form>
<p>&nbsp;</p>
<form action=ecards.php method=post>
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
	<div align=center class=tn>
  <input type=hidden name=fontcolor value="<?php echo "$fontcolor" ?>">
  <input type=hidden name=bgcolor value="<?php echo "$bgcolor" ?>">
  <input type=hidden name=music value="<?php echo $music ?>">
  <input type=hidden name=rec_name[] value="<?php echo $rec_name[0] ?>">
  <input type=hidden name=rec_email[] value="<?php echo $rec_email[0] ?>">
  <input type=hidden name=inform value="<?php echo $inform[0] ?>">
  <input type=hidden name=message value="<?php echo $message ?>">
  <input type=hidden name=multiple value="1">
  <input type=hidden name=send_year value="<?php echo $send_year ?>">
  <input type=hidden name=send_month value="<?php echo $send_month ?>">
  <input type=hidden name=send_date value="<?php echo $send_date ?>">
  <input type=hidden name=pid value="<?php echo $pid ?>">
  <input type=hidden name=aid value="<?php echo $aid ?>">
  <input type=hidden name=send_now value=1>
  <?php echo $strShowFields ?>
  <input type=text name=showmore size=2 maxlength=2 class=fieldsf>
  <input type=submit name="submit" value="<?php echo $strGo ?>" class=butfieldc>
	</div>
    </td>
  </tr>
</table>                
</form>

<?php
	 $usr->Footer();
	 exit;
	}


####### db write ########

	$i = 0;

	while($rec_name[$i] || $rec_email[$i])
	{
	$val = $i + 1;

	if(!$rec_name[$i])
      $errMsg .= "<b>$strNo $strEcardErr4 $val</b><br>\n";

	$result = CheckEmail($rec_email[$i]);
	if(!$result || !$rec_email[$i])
      $errMsg .= "<b>$strEcardErr5 $val $strInvalid</b><br>\n";

	$i++;
	}
	
	if($i == 0)
	{
	$errMsg .="<b>$strNo $strName$strPuralS <a href=javascript:history.back(-1)>$strRetry</a></b>";
	$usr->errMessage( $errMsg, $strError, 'error', '70' );
	echo("<br>");
   	$usr->Footer();
	exit;
	}

	else if($errMsg)
      {
	$errMsg .="<br><b><a href=javascript:history.back(-1)>$strRetry</a></b>";
	$usr->errMessage( $errMsg, $strError, 'error', '70' );
	echo("<br>");
   	$usr->Footer();
	exit;
	}

	if($send_date < 10 && !preg_match("/0/", $send_date))
	$send_date = "0$send_date";

	if($send_month < 10 && !preg_match("/0/", $send_month))
	$send_month = "0$send_month";

	$date_send = "$send_year"."$send_month"."$send_date";
	$curdate = date("Ymd");

	if(!checkdate($send_month, $send_date, $send_year) || $date_send < $curdate)
	{
	$errMsg ="<br><b>$strInvalidDate, <a href=javascript:history.back(-1)>$strRetry</a></b>";
	$usr->errMessage( $errMsg, $strError, 'error', '70' );
	echo("<br>");
   	$usr->Footer();
	exit;
	}

	if($curdate == $date_send)
	$tosend = "1";
	else
	$tosend = "0";

	$colors = "$fontcolor|$bgcolor";

	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	$row = mysql_fetch_array( $result );

	$name = "$row[uname]";
	$email = "$row[email]";
	$Config_sitename_url = "$Config_mainurl";
	$subject = $strEcardSubject;

	$i = 0;

	while($rec_name[$i])
	{
	$rec_name[$i] = strip_tags($rec_name[$i]);

	$csr->PublicList($rec_name[$i], $rec_email[$i], $uid);

	$val = $i + 1;

	######## generate code #######

	srand((double)microtime()*100);
	$code = rand();
	$code = crypt ($code, $Config_p);
	$code = ereg_replace ("/", "", $code);
	$code = ereg_replace ('\.', "", $code);

	##############################

	######## add to the db #######
	
	$message = addslashes($message);

	$result_addit = queryDB( "INSERT INTO $tbl_ecards VALUES(NULL, '$uid', '$rec_name[$i]', '$rec_email[$i]', '$colors', '$message', '$pid', '$music', '$date_send', '$inform[$i]', '$code', '$tosend')" );
	$ecid = mysql_insert_id();

	if($curdate == $date_send)
	{
	######## mail it #############
	$recnameto  = $rec_name[$i];
	$recemailto = $rec_email[$i];
	$cardurl    = "$Config_mainurl/eshow.php?id=$ecid&code=$code";
	$putmsg     = $csr->LangConvert($strEcardContent1, $Config_systemname, $Config_site_msg);
	$premessage = $csr->LangConvert($strEcardContent2, $name, $name, $cardurl, $Config_ecard_days, $putmsg);
	$endmessage = "$Config_msgfooter";

	$sendmessage = "$premessage \n $endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");
	###############################
	}

	######### tata birla ##########
	$end_show .= "$rec_name[$i], <i>$strDone</i> <img src=".$dirpath.$Config_imgdir."/design/tick.gif><br>";

	$i++;
	}

       $errMsg = "$end_show<br>$strEcardNotice<br><br><a href=ecards.php?aid=$aid>$strMore</a>\n";
       $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	 echo("<br>");
}

$usr->Footer();
exit;

?>