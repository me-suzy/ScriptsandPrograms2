<?PHP
	$backendfind = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
	$backend = mysql_fetch_array($backendfind);
	$pop_op = $backend["pop_op"];
	$b_max = $backend["pop_nu"];
	if ($pop_op == "0"){
?>
<META HTTP-EQUIV="Refresh" CONTENT="5; URL=main.php?page=sendmailPop&nl=<?PHP print $nl; ?>">

<?PHP
	}
	require_once("engine.inc.php");
	require_once("parseBounce.php");
	require_once("lang_select.php");	
	ini_set('max_execution_time', '45*60');
	set_time_limit (45*60);
	ignore_user_abort();
/**
* Calling the function to check your pop settings.
* You should not change anything below.
*/

	$addresses = getBounceAddresses();
	$count = 0;
	$count_f = 0;
	$count_d = 0;
	
	if ($pop_op == "0" AND $addresses != ""){
	foreach ($addresses as $something) 
	{
	$something = base64_decode ($something);
	$ex_addy=explode(" , ",$something);
	$ex_em = $ex_addy[0];
	$ex_mi = $ex_addy[1];
	$b_find = mysql_query ("SELECT * FROM ListMembers
							WHERE email LIKE '$ex_em'
							limit 1
							");
							$b_found = mysql_fetch_array($b_find);
	$nb = $b_found["bounced"];
	$nb_d = $b_found["bounced_d"];
	$ex_nl = $b_found["nl"];
	$nb = $nb + 1;
	$t_date = date("Y-m-d");
	$t_time = date("H:i:s");
	mysql_query ("INSERT INTO 12all_Bounce (email, mid, tdate, ttime, nl) VALUES ('$ex_em' ,'$ex_mi' ,'$t_date' ,'$t_time' ,'$ex_nl')");  
	if ($t_date != $nb_d){
	if ($nb < $b_max){
	mysql_query("UPDATE ListMembers SET bounced='$nb', bounced_d='$t_date' WHERE (email='$ex_em')");
	$count_f = $count_f + 1;
	}
	else {
	mysql_query ("DELETE FROM ListMembers
                                WHERE email LIKE '$ex_em'
								");
	$count_d = $count_d + 1;
	}
	}
	$count = $count + 1;
	}
	print "$count $lang_328";
	if ($count != 0){
	print "<p>$count_d $lang_329 $b_max $lang_330.";
	print "<br>$count_f e-mail $lang_331 $b_max $lang_332.";
	}
	
	}
	if ($pop_op == "1" AND $addresses != ""){
	foreach ($addresses as $something) 
	{
	$something = base64_decode ($something);
	$ex_addy=explode(" , ",$something);
	$ex_em = $ex_addy[0];
	$ex_mi = $ex_addy[1];
	$b_find = mysql_query ("SELECT * FROM ListMembers
							WHERE email LIKE '$ex_em'
							limit 1
							");
							$b_found = mysql_fetch_array($b_find);
	$nb = $b_found["bounced"];
	$nb = $nb + 1;
	if ($count == "0"){
	?>
	<form action="main.php" method="post" name="" id="">
  <table width="100%" border="0" cellspacing="0" cellpadding="3">
    <tr> 
      <td width="30">&nbsp;</td>
      <td width="100"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"># <?PHP print $lang_517; ?></font></div></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></td>
      <td width="40"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_22; ?></font></td>
    </tr>
	<?PHP } ?>
    <tr> 
      <td width="30"><font size="1" face="Arial, Helvetica, sans-serif"> 
        <input name="addy[<?PHP print $count; ?>]" type="checkbox" value="<?PHP print $b_found["email"]; ?>" checked>
        </font></td>
      <td width="100"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $b_found["bounced"]; ?><br>
          <?PHP if ($b_found["bounced"] >= $b_max){ print "Marked For Removal"; } ?>
          </font></div></td>
      <td><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $b_found["email"]; ?></font></td>
      <td width="40"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_modify&id=<?PHP print $b_found["id"]; ?>&nl=<?PHP print $nl; ?>" target="_blank"><img src="media/edit.gif" width="11" height="7" border="0"></a></font></div></td>
    </tr>
	
    <?PHP
	$count = $count + 1;
	}
	if ($count != "0"){
	?>
  </table>
  <br>
  <input type="submit" name="Submit" value="<?PHP print $lang_518; ?>">
  <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
  <input name="page" type="hidden" id="page" value="sendmailPop2">
  <input name="ex_em" type="hidden" id="ex_em" value="<?PHP print $ex_em; ?>">
  <input name="ex_mi" type="hidden" id="ex_mi" value="<?PHP print $ex_mi; ?>">
</form>
<?PHP
	}
	else{
	print "$count $lang_328";
	}
	}
?>