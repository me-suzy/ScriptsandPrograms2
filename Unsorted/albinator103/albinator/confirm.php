<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
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

	if(!$uuid || !$code)
  	{
       $usr->HeaderOut($Config_SiteTitle ." :: $strRegisterMail1b");
       $errMsg = "<b>$strConfirmError1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_userwait WHERE uid = '$uuid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->HeaderOut($Config_SiteTitle ." :: $strRegisterMail1b");
       $errMsg = "<b>$strConfirmError2</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }
  	mysql_free_result($result);

	$result = queryDB( "SELECT * FROM $tbl_userwait WHERE uid = '$uuid' && code = '$code'" );
	$nr = mysql_num_rows( $result );
  	mysql_free_result($result);
	if(!$nr)
  	{
       $usr->HeaderOut($Config_SiteTitle ." :: $strRegisterMail1b");
       $errMsg = "<b>$strConfirmError3</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }

	else
	{	 
	 $adddate = $now_date;

       $LastTimeDate = date ("l dS of F Y h:i:s A");
       $lastinfo = "$REMOTE_ADDR, $LastTimeDate";

  	 $result_del = queryDB( "DELETE FROM $tbl_userwait WHERE uid = '$uuid' && code = '$code'" );
	 $result_update = queryDB( "UPDATE $tbl_userinfo SET lastip = '$lastinfo', status = '1', adddate='$adddate' WHERE uid = '$uuid'" );
	 mkdir ("$dirpath"."$Config_datapath/$uuid", 0777);
	}
	
      	closeDB();
	      $usr->HeaderOut($Config_SiteTitle ." :: $strRegisterMail1b");
		echo("<br><br>");
	      $errMsg = "<b>$strConfirmError4 <a href=login.php>$strLogin</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );

		echo("<BR>");
            $usr->FooterOut();

?>
