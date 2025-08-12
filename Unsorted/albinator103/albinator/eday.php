<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$csr = new ComFunc();

	#remove unactivated accounts
	$lastdate  = mktime (0,0,0,date("m"),date("d")-$Config_unact_days,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);
	$ik = 0;	
	
	$result_unact = queryDB( "SELECT * FROM $tbl_userwait WHERE adddate < $curdate" );
	while ($row = mysql_fetch_array( $result_unact ))
	{
	  $result_conf = queryDB( "SELECT email, uname FROM $tbl_userinfo WHERE uid = '$row[uid]'" );   
	  $row_conf = mysql_fetch_array( $result_conf );

	  $result_pub = queryDB( "SELECT * FROM $tbl_publist WHERE email = '$row_conf[email]'" );
	  $row_pub = mysql_num_rows( $result_pub );
	  
	  if(!$row_pub)  	  
	  $result = queryDB( "INSERT INTO $tbl_publist VALUES(NULL, '$row_conf[uname]', '$row_conf[email]', 'system');" );

	  $result_del = queryDB( "DELETE FROM $tbl_userinfo WHERE uid = '$row[uid]'" ); 
	}
	mysql_free_result( $result_unact );
	$result_del = queryDB( "DELETE FROM $tbl_userwait WHERE adddate < $curdate" );

	############################
	#remove old ecards in list
	$lastdate  = mktime (0,0,0,date("m"),date("d")-$Config_ecard_days,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);
	$ik = 0;	
	$result_del = queryDB( "DELETE FROM $tbl_ecards WHERE makedate < '$curdate'" );
	############################


	############################
	#block accounts
	$lastdate  = mktime (0,0,0,date("m"),date("d")+$Config_BlockNotifyDay,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);
	
      $result_getuser = queryDB( "SELECT * FROM $tbl_userinfo WHERE validity <= $curdate && validity!='0' && status='1'" );
      $nr = mysql_num_rows( $result_getuser );

	if($nr && $Config_BlockNotify != '0')
	{
	$subject = $strNotify;
	$endmessage = $Config_msgfooter;

		while($row = mysql_fetch_array( $result_getuser ))
		{
		$block_uids .="$row[uname] :: $row[uid]\n"; 

		if($Config_BlockNotify == '1' || $Config_BlockNotify == '3')
		{
		$sendmessage = "$row[uname],\n\n$Config_blockmsgEar\n\n$endmessage";
		$mailheader = "From: $Config_adminname <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
		mail("$row[email]","$Config_sitename Ac","$sendmessage","$mailheader");
		}
		}

	if($Config_BlockNotify > 1 && $block_uids)
	{
	$subject = $strAutoBlock1;
	$premessage = $csr->LangConvert($strAutoBlock2, $Config_adminname, $Config_BlockNotifyDay, $block_uids);
	$endmessage = $Config_msgfooter;
	$sendmessage = "$premessage\n\n$endmessage";

	$mailheader = "From: $Config_sitename <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$Config_adminmail","$subject","$sendmessage","$mailheader");
	}
	}

	$result_getuser = queryDB( "SELECT * FROM $tbl_userinfo WHERE validity <= $now_date && validity != '0' && status != '0'" );
	while($row = mysql_fetch_array( $result_getuser ))
	{
		$block_uids2 .="$row[uname] :: $row[uid]\n"; 

		if($Config_BlockNotify == '1' || $Config_BlockNotify == '3')
		{
		$sendmessage = "$row[uname],\n\n$Config_blockmsg\n\n$endmessage";
		$mailheader = "From: $Config_sitename <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
		mail("$row[email]","$subject","$sendmessage","$mailheader");
		}
	}

	if($Config_BlockNotify > 1 && $block_uids2)
	{
	$subject = $strAutoBlock1;
	$premessage = $csr->LangConvert($strAutoBlock3, $Config_adminname, $block_uids2);
	$endmessage = $Config_msgfooter;
	$sendmessage = "$premessage\n\n$endmessage";

	$mailheader = "From: $Config_sitename <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$Config_adminmail","$subject","$sendmessage","$mailheader");
	}	

	$result_block = queryDB( "UPDATE $tbl_userinfo SET status='2' WHERE validity <= $now_date && validity != '0'" );
	############################

	############################
	#send mails
	$Config_sitename_url = "$Config_mainurl";

	$subject = "$strEcardSubject";
	$putmsg = $csr->LangConvert($strEcardContent1, $Config_systemname,$Config_site_msg);

	$curdate = date("Ymd");

	$result = queryDB( "SELECT * FROM $tbl_ecards WHERE makedate = '$curdate'" );

	while($row = mysql_fetch_array( $result ))
	{
	if($row[mailsent] == "0")
	{
	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$row[uid]'" );
	$row_user = mysql_fetch_array( $result_user );

	$name = "$row_user[uname]";
	$email = "$row_user[email]";
	$recnameto = $row[rec_name];
	$recemailto = $row[rec_email];
	$cardurl = "$Config_mainurl/eshow.php?id=$row[ecid]&code=$row[code]";
	$premessage = $csr->LangConvert($strEcardContent2, $name, $name, $cardurl, $Config_ecard_days, $putmsg);
	$endmessage = "$Config_msgfooter";
	$sendmessage = "$premessage \n $endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");

	$result_update = queryDB( "UPDATE $tbl_ecards SET mailsent='1' WHERE ecid='$row[ecid]'" );
	}
	}
	############################


	#send reminders

	$Config_sitename_url = "$Config_mainurl";
	$name = "$Config_sitename";
	$email = "$Config_adminmail";
	$today = getdate(); 
	$curdate = date("Ymd");

	$result = queryDB( "SELECT * FROM $tbl_reminders ORDER BY rid" );

while($row = mysql_fetch_array( $result ))
{
  $send_mail = 0;
  if($row[estatus] != "0")
  {
	if($row[estatus] == "1" || $row[estatus] == "2")
	{
		if($row[date_year] == "0")
		$send_year = $today['year'];
		else
		$send_year = $row[date_year];

		if($row[date_month] == "0")
		{
			if($today['mon'] < 10) 
			$send_month = "0".$today['mon'];
			else
			$send_month = $today['mon'];
		}
		else if($send_month < 10)
		$send_month = "0".$row[date_month];
		else
		$send_month = $row[date_month];

		$send_date = $row[date_day];

		if($send_date < 10)
		$send_date = "0$send_date";

		$trigerdate = "$send_year"."$send_month"."$send_date";

		if($curdate == $trigerdate)
		{ 
			$subject = "$strEdayRSub1";
			$send_mail = 1; 
		}
	}

	if($send_mail != 1)
	{
	if($row[estatus] == "3" || $row[estatus] == "2")
	{
		if($row[date_year] == "0")
		$send_year = $today['year'];
		else
		$send_year = $row[date_year];

		if($row[date_month] == "0")
		{
			if($today['mon'] < 10) 
			$send_month = "0".$today['mon'];
			else
			$send_month = $today['mon'];
		}
		else if($send_month < 10)
		$send_month = "0".$row[date_month];
		else
		$send_month = $row[date_month];

		$send_date = $row[date_day] - 1;

		if($send_date < 10)
		$send_date = "0$send_date";

		$trigerdate = "$send_year"."$send_month"."$send_date";

		if($curdate == $trigerdate)
		{ 
			$subject = " $strEdayRSub2 $strEdayRSub1";
			$send_mail = 2; 
		}
	}
	}

	if($send_mail == 1 || $send_mail == 2)
	{
	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$row[uid]'" );
	$row_user = mysql_fetch_array( $result_user );

	$recnameto = $row_user[uname];
	$recemailto = $row_user[email];

	if($row[message])
	{ $urmessage = "$strMessage,\n$row[message]"; }

	############## date to show to user ###########
	$reg_year = substr($trigerdate, 0, 4);
	$reg_month = substr($trigerdate, 4, 2);
	$reg_date = substr($trigerdate, 6, 2);

	if($send_mail == 2)
	$reg_date += 1;
	else
	$reg_date += 0;

	$eventdate = date ("F jS, Y", mktime (0,0,0,$reg_month,$reg_date,$reg_year));

	if($send_mail == "2")
	{  $day_ear = " $strEdayRSub2"; }
	else
	{ $day_ear = ""; }
      ###############################################

	$premessage = $csr->LangConvert(" $strEdayRMail1", $row_user[uname], $day_ear, $row[event], $eventdate, $urmessage, $Config_sitename);
	$endmessage = "$Config_msgfooter";
	$sendmessage = "$premessage \n\n$endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");

	
		if($send_mail == 1)
		{
			if($row[date_month] != "0" && $row[date_year] != "0")
			{ 
		 		if($row[estatus] == "1")
	                  $result_update = queryDB( "UPDATE $tbl_reminders SET estatus='0' WHERE rid='$row[rid]'");
			}

			# special case when month is constant but year isn't
			if($row[date_month] == "0" && $row[date_year] != "0")
			{ 
		 		if($today['mon'] == "12")
	                  $result_update = queryDB( "UPDATE $tbl_reminders SET estatus='0' WHERE rid='$row[rid]'");
			}
		}
		else if($send_mail == 2)
		{
			if($row[date_month] != "0" && $row[date_year] != "0")
			{ 
		 		if($row[estatus] == "2")
	                  $result_update = queryDB( "UPDATE $tbl_reminders SET estatus='1' WHERE rid='$row[rid]'");

		 		else if($row[estatus] == "3")
	                  $result_update = queryDB( "UPDATE $tbl_reminders SET estatus='0' WHERE rid='$row[rid]'");
			}
		}
	#send mail
	}
  #estatus!=0
  }

}

	$result_delete = queryDB( "DELETE from $tbl_reminders WHERE estatus='0'");
	########## delete old temp files ########

      $BASEDIR = $dirpath.$Config_datapath."/temp";
      $DAYS = "1";

	$DAYS = date("z") - $DAYS;

	if ($DELDIR = @opendir($BASEDIR))
	{
	    while ($file = readdir($DELDIR))
	    {
		 if($file != ".." && $file != ".")
		 {
			 $ftime = filemtime("$BASEDIR/$file");
			 $fdate = date("z", $ftime);

			 if ($DAYS >= $fdate)
 	      	 unlink("$BASEDIR/$file");
		 }
	    }
	    closedir($DELDIR);
	}
	############################
?>