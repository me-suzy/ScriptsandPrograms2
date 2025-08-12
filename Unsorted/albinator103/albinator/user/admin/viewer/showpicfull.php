<?php
	$dirpath = "$Config_rootdir"."../../../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	
      if ( !$ucook->LoggedIn() )
      {
	    $csr->customMessage( 'logout' );  
          exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "Denied Access to the Admin Panel :: $SCRIPT_NAME", "2");

       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	if(!$aid || !$username || !$pid)
	{
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError1</b>\n";
       $usr->errMessage( $errMsg, $strError );

	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strError );

	 closeDB();
	 exit;
      }

   	$row = mysql_fetch_array( $result );
	$nr = $row[pused];

	if(!$nr)
	{
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError4</b>\n";
       $usr->errMessage( $errMsg, $strError );

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid' && pid='$pid'" );
	$row_orig = mysql_fetch_array( $result );

	 if($row_orig[pmsg] && $row_orig[pmsg] != '0')
	 { $pmsg = "<br><br><span class=tn><font color=#333333>&quot; <i>$row_orig[pmsg]</i> &quot;</font</span><br><br>"; }

	 $result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$username'" );
 	 $row_user = mysql_fetch_array( $result_user );
	 error_reporting(0);
	 $size = GetImageSize ("$dirpath"."$Config_datapath/$username/$row_orig[pname]");
	 error_reporting(E_ERROR | E_WARNING);

	 if (preg_match("/B/", $row_user[prefs]))
	 $borderval = "border=1";
	 else
	 $borderval = "border=0";
?>

<html>
<head>
<title><?php echo ("$Config_SiteTitle :: $strShowError6"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" HREF="<?php echo("{$dirpath}essential/{$Config_LangLoad}_default.css"); ?>" type="text/css">
<style>
<!--

a:hover{ color: #DDDDDD; }
a:link{ color: #CCCCCC; }
a:vlink{ color: #CCCCCC; }

//-->
</style>

</head>

<body bgcolor="#FFFFFF" text="#FFFFFF" link="#FFFFFF" vlink="#CCCCCC" alink="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr bgcolor="#006699"> 
    <td height="24" class=tn>&nbsp;<b><font color="#FFFFFF"><?php echo ("$Config_systemname $strShowError6"); ?> 
    </font></b><font color="#FFFFFF"> [<a href=javascript:self.close() onmouseover="self.status='<?php echo $strClose ?>';return true;" onmouseout="self.status=''"><?php echo $strClose ?></a>]</font></td>
  </tr>
  <tr background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>">
    <td background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>" height=530 align=center class=tn>
<table width="99%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr> 
    <td align=center>
	<?php if($pmsg) echo "<BR>"; ?><img src=<?php echo "\"".$dirpath."$Config_datapath/$username/$row_orig[pname]\" $borderval $size[3]>".stripslashes($pmsg); ?>
    </td>
  </tr>
</table></td>
  </tr>
  <tr bgcolor="#333333"> 
    <td class=tn>&nbsp;<b><font color="#FFFFFF"><?php echo ("$Config_systemname $strShowError6"); ?></font></b><font color="#FFFFFF">[<a href=javascript:self.close() onmouseover="self.status='<?php echo $strClose ?>';return true;" onmouseout="self.status=''"><?php echo $strClose ?></a>]</font></td>
  </tr>
</table>

</body>
</html>
