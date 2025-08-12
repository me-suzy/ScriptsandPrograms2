<?PHP
	$tracknum = "0";
	$result = mysql_query ("SELECT * FROM Lists
							 WHERE name != ''
							ORDER BY name
							");
	if ($c1 = mysql_num_rows($result)) {
	
	while($row = mysql_fetch_array($result)) {
	$selid = $row["id"];
						$selid = " , $selid ";
	$seluser = $row_admin["user"];
						$selector = mysql_query ("SELECT * FROM Admin
			WHERE user LIKE '$seluser'
			AND lists LIKE '%$selid%'
							");
	
	if ($seld = mysql_fetch_array($selector))
	{
	$tracknum++;
	}
	}
	}
	 $result = mysql_query ("SELECT * FROM Admin
                         WHERE user LIKE '$usernow'
						 limit 1
                       ");
	$row = mysql_fetch_array($result);
	
	if ($tracknum >= $row["m_limit"] AND $usernow != "admin" AND $row["m_limit"] != "0"){
	print "$lang_492: ";
	print $row["m_limit"];
	}
	else {
$today = date("Ymd");
if ($clsettings == "1"){
$ltt = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nlold'
						LIMIT 1
						");
$ltf = mysql_fetch_array($ltt);

$confirm = $ltf["confirm"];
$confirm2 = $ltf["confirm2"];
$welcomes = $ltf["welcomes"];
$goodbyes = $ltf["goodbyes"];
$welcomemesg = $ltf["welcomemesg"];
$goodbyemesg = $ltf["goodbyemesg"];
$confirmopt = $ltf["confirmopt"];
$confirmoptmesg = $ltf["confirmoptmesg"];
$confirmopt2 = $ltf["confirmopt2"];
$confirmoptmesg2 = $ltf["confirmoptmesg2"];
$bk = $ltf["bk"];
$murl = $ltf["murl"];
$field1 = $ltf["field1"];
$field2 = $ltf["field2"];
$field3 = $ltf["field3"];
$field4 = $ltf["field4"];
$field5 = $ltf["field5"];
$field6 = $ltf["field6"];
$field7 = $ltf["field7"];
$field8 = $ltf["field8"];
$field9 = $ltf["field9"];
$field10 = $ltf["field10"];
$a_ui = $ltf["a_ui"];
$a_ua = $ltf["a_ua"];
$a_is = $ltf["a_is"];
$a_as = $ltf["a_as"];
$a_ff = $ltf["a_ff"];
$a_nm = $ltf["a_nm"];
$a_pt = $ltf["a_pt"];
$a_tp = $ltf["a_tp"];
$a_gc = $ltf["a_gc"];
$a_ed = $ltf["a_ed"];
$a_mx = $ltf["a_mx"];
$a_s1 = $ltf["a_s1"];
$a_s2 = $ltf["a_s2"];
$a_s3 = $ltf["a_s3"];
$a_em = $ltf["a_em"];
$a_rq = $ltf["a_rq"];
$a_ep = $ltf["a_ep"];
$a_sc = $ltf["a_sc"];

mysql_query ("INSERT INTO Lists (name, email, date, confirm, confirm2, welcomes, goodbyes, welcomemesg, goodbyemesg, confirmopt, confirmoptmesg, confirmopt2, confirmoptmesg2, bk, murl, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, a_ui, a_ua, a_is, a_as, a_ff, a_nm, a_pt, a_tp, a_gc, a_ed, a_mx, a_s1, a_s2, a_s3, a_em, a_rq, a_ep, a_sc) VALUES ('$name' ,'$email' ,'$today' ,'$confirm' ,'$confirm2' ,'$welcomes' ,'$goodbyes' ,'$welcomemesg' ,'$goodbyemesg' ,'$confirmopt' ,'$confirmoptmesg' ,'$confirmopt2' ,'$confirmoptmesg2' ,'$bk' ,'$murl' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10' ,'$a_ui' ,'$a_ua' ,'$a_is' ,'$a_as' ,'$a_ff' ,'$a_nm' ,'$a_pt' ,'$a_tp' ,'$a_gc' ,'$a_ed' ,'$a_mx' ,'$a_s1' ,'$a_s2' ,'$a_s3' ,'$a_em' ,'$a_rq' ,'$a_ep' ,'$a_sc')");  
}
else{

$ltt = mysql_query ("SELECT * FROM Admin
                         WHERE user LIKE '$usernow'
						LIMIT 1
						");
$ltf = mysql_fetch_array($ltt);

$a_ui = $ltf["a_ui"];
$a_ua = $ltf["a_ua"];
$a_is = $ltf["a_is"];
$a_as = $ltf["a_as"];
$a_ff = $ltf["a_ff"];
$a_nm = $ltf["a_nm"];
$a_pt = $ltf["a_pt"];
$a_tp = $ltf["a_tp"];
$a_gc = $ltf["a_gc"];
$a_ed = $ltf["a_ed"];
$a_mx = $ltf["a_mx"];
$a_s1 = $ltf["a_s1"];
$a_s2 = $ltf["a_s2"];
$a_s3 = $ltf["a_s3"];
$a_em = $ltf["a_em"];
$a_lt = $ltf["a_lt"];
$a_pz = $ltf["a_pz"];
$a_bn = $ltf["a_bn"];
$a_op = $ltf["a_op"];
$a_co = $ltf["a_co"];
mysql_query ("INSERT INTO Lists (name, email, date, a_ui, a_ua, a_is, a_as, a_ff, a_nm, a_pt, a_tp, a_gc, a_ed, a_mx, a_s1, a_s2, a_s3, a_em, a_lt, a_pz, a_bn, a_op, a_co) VALUES ('$name' ,'$email' ,'$today' ,'$a_ui' ,'$a_ua' ,'$a_is' ,'$a_as' ,'$a_ff' ,'$a_nm' ,'$a_pt' ,'$a_tp' ,'$a_gc' ,'$a_ed' ,'$a_mx' ,'$a_s1' ,'$a_s2' ,'$a_s3' ,'$a_em' ,'$a_lt' ,'$a_pz' ,'$a_bn' ,'$a_op' ,'$a_co')");  
}
$last = mysql_query ("SELECT * FROM Lists
                         WHERE name LIKE '$name'
                       	ORDER BY id DESC
						LIMIT 1
						");
$lastfind = mysql_fetch_array($last);
$add = $lastfind["id"];
if ($row_admin["user"] != admin){
$usnow = $row_admin["user"];
$lists = $row_admin["lists"];
$lists = "$lists , $add ";
mysql_query("UPDATE Admin SET lists='$lists' WHERE (user='$usnow')");
}
		  $result321 = mysql_query ("SELECT * FROM Admin
                         WHERE user LIKE 'admin'
						 limit 1
                       ");
$row321 = mysql_fetch_array($result321);
$lists = $row321["lists"];
$lists = "$lists , $add ";
mysql_query("UPDATE Admin SET lists='$lists' WHERE (user='admin')");
if ($qpadd != ""){
			$q = stripslashes($qpadd);
				$prefilter = "AND nl LIKE '$nlold'
							AND email != ''
							AND active LIKE '0'"; 
				$filterdata = "AND $q";
				$filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
		
		$result = mysql_query ("SELECT * FROM ListMembers
											WHERE active LIKE '0'
											AND email != ''
											AND nl LIKE '$nlold'
											$filterdata
											ORDER BY email
		");
		if ($c1 = mysql_num_rows($result)) {
		
		while($row = mysql_fetch_array($result)) {
		$email = $row["email"];
		$name = $row["name"];
		$field1 = $row["field1"];
		$field2 = $row["field2"];
		$field3 = $row["field3"];
		$field4 = $row["field4"];
		$field5 = $row["field5"];
		$field6 = $row["field6"];
		$field7 = $row["field7"];
		$field8 = $row["field8"];
		$field9 = $row["field9"];
		$field10 = $row["field10"];
		$today = $row["sdate"];
		$sip = $row["sip"];
		mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10) VALUES ('$email' ,'$name' ,'$add' ,'$today' ,'$sip' ,'0' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10')");  
					if ($msgCounter % 40 == 0){
						@mysql_close($db_link);
						require("engine.inc.php");
						$msgCounter == 0;
					}
					$msgCounter++;
					flush();
		
		}
		}
}
if ($ltadd != ""){
		$result123 = mysql_query ("SELECT * FROM 12all_LinksD
											WHERE lid LIKE '$ltadd'
		");
		if ($c = mysql_num_rows($result123)) {
		
		while($row123 = mysql_fetch_array($result123)) {
		
		$ema = $row123["email"];
		$result = mysql_query ("SELECT * FROM ListMembers
											WHERE email LIKE '$ema'
											AND nl LIKE '$nlold'
		");
		$row = mysql_fetch_array($result);

		$email = $row["email"];
		$name = $row["name"];
		$field1 = $row["field1"];
		$field2 = $row["field2"];
		$field3 = $row["field3"];
		$field4 = $row["field4"];
		$field5 = $row["field5"];
		$field6 = $row["field6"];
		$field7 = $row["field7"];
		$field8 = $row["field8"];
		$field9 = $row["field9"];
		$field10 = $row["field10"];
		$today = $row["sdate"];
		$sip = $row["sip"];
		mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10) VALUES ('$email' ,'$name' ,'$add' ,'$today' ,'$sip' ,'0' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10')");  
					if ($msgCounter % 40 == 0){
						@mysql_close($db_link);
						require("engine.inc.php");
						$msgCounter == 0;
					}
					$msgCounter++;
					flush();
		
		}
		}
}
if ($atadd != ""){
		$result123 = mysql_query ("SELECT * FROM 12all_Bounce
											WHERE mid LIKE '$atadd'
		");
		if ($c1 = mysql_num_rows($result123)) {
		
		while($row123 = mysql_fetch_array($result123)) {
		
		$ema2 = $row123["email"];
		$result = mysql_query ("SELECT * FROM ListMembers
											WHERE id LIKE '$ema2'
		");
		$row = mysql_fetch_array($result);

		$email = $row["email"];
		$name = $row["name"];
		$field1 = $row["field1"];
		$field2 = $row["field2"];
		$field3 = $row["field3"];
		$field4 = $row["field4"];
		$field5 = $row["field5"];
		$field6 = $row["field6"];
		$field7 = $row["field7"];
		$field8 = $row["field8"];
		$field9 = $row["field9"];
		$field10 = $row["field10"];
		$today = $row["sdate"];
		$sip = $row["sip"];
		if ($email == ""){
		$email = $ema2;
		}
		mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10) VALUES ('$email' ,'$name' ,'$add' ,'$today' ,'$sip' ,'0' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10')");  
					if ($msgCounter % 40 == 0){
						@mysql_close($db_link);
						require("engine.inc.php");
						$msgCounter == 0;
					}
					$msgCounter++;
					flush();
		
		}
		}
}

if ($clusers != ""){
		$result123 = mysql_query ("SELECT * FROM ListMembers
											WHERE nl LIKE '$nlold'
		");
		if ($c1 = mysql_num_rows($result123)) {
		
		while($row = mysql_fetch_array($result123)) {
		$email = $row["email"];
		$name = $row["name"];
		$field1 = $row["field1"];
		$field2 = $row["field2"];
		$field3 = $row["field3"];
		$field4 = $row["field4"];
		$field5 = $row["field5"];
		$field6 = $row["field6"];
		$field7 = $row["field7"];
		$field8 = $row["field8"];
		$field9 = $row["field9"];
		$field10 = $row["field10"];
		$today = $row["sdate"];
		$sip = $row["sip"];
		mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10) VALUES ('$email' ,'$name' ,'$add' ,'$today' ,'$sip' ,'0' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10')");  
					if ($msgCounter % 40 == 0){
						@mysql_close($db_link);
						require("engine.inc.php");
						$msgCounter == 0;
					}
					$msgCounter++;
					flush();
		
		}
		}
}
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_139; ?></strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_140; ?></font></p>
<hr width="100%" size="1" noshade>
<p><font size="2" face="Arial, Helvetica, sans-serif"><em><?PHP print $lang_141; ?></em></font></p>
<hr width="100%" size="1" noshade>
<p><a href="main.php"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_142; ?></font></a></p>
<?PHP } ?>