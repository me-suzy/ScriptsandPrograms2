	<script language="JavaScript">
	function GP_popupConfirmMsg(msg) {
	  document.MM_returnValue = confirm(msg);
	}
	</script><p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_152; ?></strong></font></p>
	<p><font size="2" face="Arial, Helvetica, sans-serif"> 
	<?PHP 
	if ($val != dgo & $val != bgo){ 
	print $lang_157; 
	?>
	</font></p>
	<table width="100%" height="25" border="0" cellpadding="0" cellspacing="0" bgcolor="#D5E2F0">
	  <tr>
		<td><table width="100%" height="23" border="0" cellpadding="1" cellspacing="0">
			<tr bgcolor="#ECF8FF"> 
			  <td> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_batch&nl=<?PHP print $nl; ?>&val=dgo" onClick="GP_popupConfirmMsg('THIS CAN NOT BE UNDONE!\r\rAre you sure that you would like to remove all addresses?\r\rThis will delete all addresses from this list without recovery options.');return document.MM_returnValue"><?PHP print $lang_153; ?></a> <font color="#FF0000"> [ <?PHP print $lang_154; ?> ]</font></font></div></td>
			</tr>
		  </table></td>
	  </tr>
	</table>
	<p><font size="2" face="Arial, Helvetica, sans-serif"><img src="media/line_mblue.gif" width="550" height="1"></font> 
	</p>
	
<p> <font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_577; ?></strong></font></p>
	<form action="main.php" method="post" name="" id="">
	<p>
    <textarea name="removals" cols="65" rows="12" id="removals"></textarea>
	</p>
	<p> 
    <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
    <input type="hidden" name="page" value="list_batch">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input name="val" type="hidden" id="val" value="bgo">
	</p>
	</form>
	<p> 
	<?PHP
	}
	if ($val == "dgo"){
		if ($nl != ""){
			$findcount = mysql_query ("SELECT * FROM ListMembers
										WHERE nl LIKE '$nl'
										");
			$countdata = mysql_num_rows($findcount);
			mysql_query ("DELETE FROM ListMembers
							WHERE nl LIKE '$nl'
							");
			if ($countdata == "0"){
				print "$lang_155";
			}
			else {
				print "$countdata $lang_156";
			}
		}
	}
	if ($val == "bgo"){
		$num = 0;
		$numr = 0;
		$words=explode("\n",$removals);
   		while($words[$num] != ""){
			$cem = $words[$num];
			$cem = ereg_replace (" ", "", $cem);
			$cem = ereg_replace ("\r", "", $cem);
			$findcount = mysql_query ("SELECT * FROM ListMembers
										WHERE nl LIKE '$nl'
										AND email LIKE '$cem'
										AND active LIKE '0'
										");
			$countdata = mysql_num_rows($findcount);
			if ($countdata != "0"){
			$numr++;
			}
			mysql_query ("DELETE FROM ListMembers
							WHERE nl LIKE '$nl'
							AND email LIKE '$cem'
							AND active LIKE '0'
							");
			$num++;
		}
		// Results
		print "Attempted to remove $num addresses from your list.<br>$numr were removed.";
	}
	?>
	</font>
	</p>