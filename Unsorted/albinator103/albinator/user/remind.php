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

	$usr->Header($Config_SiteTitle ." :: $strMenusReminders");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div>");

	$today = getdate(); 

$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid'" );
$nr_res = mysql_num_rows( $result );
mysql_free_result($result);

$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
$row = mysql_fetch_array( $result );
list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);
mysql_free_result($result);

if($nr_res > 1 || $nr_res == 0)
$s = $strPuralS;

$strTotalInfo = $csr->LangConvert($strTotalInfo, $nr_res, $strMenusReminders);
echo ("<p>&nbsp;</p><div align=center class=tn>$strTotalInfo<br></div>");

if(($nr_res < $rlimit && $rlimit) || $rlimit == "0")
{
?>

<form method=post action=remind_cr.php>
  <table width="90%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#006699" class=tn>
     <tr> 
	<td colspan=2 class=ts bgcolor="#333333">
	    <font color=#cccccc><b><?php echo $strReminderAdd ?><b></font>
	</td>
     </tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strEvent ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="text" name="newevent" maxlength=99 size="30" class=fieldsa><span class=ts>&nbsp;&nbsp;<?php echo $strReminderEventExample ?></span></td>
    </tr>
     <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn valign=top>&nbsp;<?php echo $strReminderMessage ?><br>&nbsp;<span class=ts><?php echo($csr->LangConvert($strReminderMessageLength, $Config_remind_msg_max)); ?></span></td>
      <td width=65% bgcolor="#CCCCCC" class=tn>&nbsp;<textarea name="newmessage" rows=5 cols="30" class=fieldsa></textarea></td>
	</tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strDate ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;
    <select name="send_year">
    <option value="0" selected><?php echo $strReminderEveryYear ?></option>
<?php
	$today_year = $today['year'];
	echo("      <option value=\"$today_year\">$today_year</option>\n");
	$today_year++;
	echo("      <option value=\"".$today_year."\">".$today_year."</option>\n");

?>
    </select>
      <select name="send_month">
      <option value="0" selected><?php echo $strReminderEveryMonth ?></option>
<?php
	$today_month = $today['mon'];

	for($i=1;$i<=12;$i++)
	{
		echo("      <option value=\"$i\">$date_show[$i]</option>\n");
	}

?>
      </select>
      <select name="send_date">
<?php
	$today_date = $today['mday'] + 1;

	for($i=1;$i<=31;$i++)
	{
		echo("      <option value=\"$i\">$i</option>\n");
	}

?>
      </select>
	</td>
    </tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strReminderWhen ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="radio" name="whento" value=1 checked> <?php echo $strReminderWhenOpt1 ?>&nbsp;<input type="radio" name="whento" value=3> <?php echo $strReminderWhenOpt2 ?>&nbsp;<input type="radio" name="whento" value=2> <?php echo $strReminderWhenOpt3 ?></td>
    </tr>
    <tr>
      <td colspan=2 align=right> 
      <input type="submit" name="Submit" value="<?php echo $strCreate ?> &gt;&gt;">&nbsp;
      </td>
    </tr>
  </table>
</form>
<a name=list></a>
<p>&nbsp;</p>
<?php
}
else
{
    echo ("<br>");
    $errMsg = "<b>".$csr->LangConvert($strCrossLimit, strtolower($strMenusReminders))."</b> [<a href=\"$Config_buylink\">$strBuySentence</a>] or <a href=javascript:history.back(1)>$strBack</a>...</b><br><br>\n";
    $usr->errMessage( $errMsg, $strNote, 'error', '70' );
    echo ("<p>&nbsp;</p>");
}

$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid' ORDER BY rid" );
$nr = mysql_num_rows( $result );

if($nr > 0)
{
?>

<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr class="tn" align=center> 
    <td colspan=4 class=tn><font color="#666666"><b><?php echo $strReminderList ?></b></font><br><br></td>
  </tr>
  <tr class="tn"> 
    <td width=25%><b><?php echo $strEvent ?></b></td>
    <td width=30%><b><?php echo $strMessage ?></b></td>
    <td width=25%><b><?php echo $strDate ?> (Y-M-D)</b></td>
    <td width=20% align=right class=ts>[<a href=remind_chg.php?dowhat=edit><?php echo $strEditAll ?></a>]&nbsp;</td>
  </tr>


<?php
$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }

	$row[event] = stripslashes($row[event]);
	$row[message] = stripslashes($row[message]);
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><?php if(strlen($row[event]) > 25) echo substr($row[event], 0, 22)."..."; else echo $row[event] ?></td>
    <td><?php if(strlen($row[message]) > 25) echo substr($row[message], 0, 22)."..."; else if($row[message] == "") echo "<font color=#999999><?php echo $strNone ?></font>"; else  echo $row[message] ?></td>
    <td>
	<?php 

	if($row[date_year] == "0")
	$year_show = "$strReminderEverySym - ";
	else
	$year_show = "$row[date_year] - ";

	
	if($row[date_month] == "0")
	$month_show = "$strReminderEverySym - ";
	else
	$month_show = "$row[date_month] - ";
	
	echo "$year_show"."$month_show"."$row[date_day]"; 
	
	?>
   </td>
    <td class=ts align=right>[<a href="<?php echo "remind_chg.php?dowhat=edit&remind_id=$row[rid]&sfrom=main"; ?>" class=nounderts><?php echo $strEdit ?></a>] [<a href="<?php echo "remind_chg.php?dowhat=delete&rid=$row[rid]&sfrom=main"; ?>" class=nounderts><?php echo $strDelete ?></a>]&nbsp;</td>
  </tr>
<?php
}

?>

  <tr class="ts"> 
    <td colspan=4 class=ts><?php echo ("$strReminderEverySym $strReminderEveryInfo"); ?> </td>
  </tr>
</table>
<p>&nbsp;</p>

<?php
	}


closeDB();
$usr->Footer(); 

?>