<?php

$page_maker = 25;
$UserInfo   = array();

if($Config_showProcessTime == "1")
$starttime = CalcStartTime('start');

$Config_AdminLangLoad = $Config_langCode;
$now_date  = date("Ymd");

if(!$Config_SiteTitle)
$Config_SiteTitle = $Config_sitename;

if(!$Config_buylink)
{
	if(preg_match ("/(config.php)/i", $SCRIPT_NAME))
	$Config_buylink = "";

	else
	$Config_buylink = $dirpath."user/feedback.php";
}

if($Config_langCodeForce == "1" || !$uid)
$Config_LangLoad = $Config_langCode;
else if($uid)
{ 
	$result = queryDB( "SELECT langcode FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row = mysql_fetch_array($result);

	$Config_LangLoad = $row[langcode];
	mysql_free_result( $result );
}

if(!$Config_LangLoad)
$Config_LangLoad = "eng";

// load the language file
include($dirpath."essential/lang/{$Config_LangLoad}.lang.php");
if(preg_match ("/(admin)/i", $SCRIPT_NAME))
include($dirpath."essential/lang/{$Config_LangLoad}.adminlang.php");

include($dirpath."essential/config.php");

if(!preg_match("/(photo-rand)/i", $SCRIPT_NAME))
include($dirpath."essential/headerfooter.php");

class ComFunc
{
  var $uid;

  function ComFunc()
  {  }

  function LangConvert()
  {
 	  $numChg = func_num_args() - 1;
	  $Chg_list = func_get_args();

	  for ($i = 1; $i <= $numChg; $i++) 
	  $Chg_list[0] = preg_replace("/%$i/", $Chg_list[$i], $Chg_list[0]);
	
  return($Chg_list[0]);
  } 

     function customMessage( $msgID )
     {
	    global $dirpath, $strAdminNoAccess, $strLogin, $strNotLogin, $strError;

	    $htmlCall = new Html();

	    if($msgID == 'logout')
	    $errMsg = "<b>$strNotLogin, <a href=".$dirpath."login.php?ref=1>$strLogin</a></b><br><br>\n";

	    else if($msgID == 'noadmin')	    
	    $errMsg = "<B>$strAdminNoAccess</B>\n\n";

	    $htmlCall->errMessage( $errMsg, $strError );
     }

     function MakeAdminLogs( $logname, $AccMsg = "Denied Access", $logstatus = "1" )
     {
       global $tbl_adlogs;

       $AccTimeDate = date ("l dS of F Y h:i:s A");
       $result = queryDB( "INSERT INTO $tbl_adlogs VALUES(NULL, '$logname', '$AccTimeDate', '$logstatus', '$AccMsg');" );
     }

     function PublicList( $pub_name, $pub_email, $userval )
     {
     global $tbl_userinfo, $tbl_publist;

      if($pub_email)
      {
      $result = queryDB( "SELECT * FROM $tbl_publist WHERE email ='$pub_email'" );
      $nr = mysql_num_rows( $result );

      $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE email ='$pub_email'" );
      $nr_user = mysql_num_rows( $result_user );

      if(!$nr && !$nr_user)
      $result = queryDB( "INSERT INTO $tbl_publist VALUES(NULL, '$pub_name', '$pub_email', '$userval');" );
      }
     }

	function ResizeImg( $sSource, $sDest, $fType, $nWidth, $nHeight, $UserId)
	{
	global $dirpath, $Config_datapath, $Config_ResizeBy;

	if($fType == "JPG")
	$fType = "JPEG";

	$sSource = $dirpath.$Config_datapath."/".$UserId."/".$sSource;
	$sDest = $dirpath.$Config_datapath."/".$UserId."/".$sDest;

	$TrueCall = "ImageCreateFrom".$fType;
	$ImageCall = "Image".$fType;

	error_reporting(0);
	$arSrcSize = GetImageSize( $sSource );
	error_reporting(E_ERROR | E_WARNING);
	$src = @$TrueCall ( $sSource ); 

		if($src)
		{
		      if($Config_ResizeBy == '3')
			{
		      	$dst = @ImageCreate ($nWidth, $nHeight);
		      	ImageCopyResized( $dst, $src, 0, 0, 0, 0, $nWidth, $nHeight, $arSrcSize[0], $arSrcSize[1] );
			}

			else
			{
				$dst = ImageCreateTrueColor ($nWidth, $nHeight); 
				ImageCopyResampled( $dst, $src, 0, 0, 0, 0, $nWidth, $nHeight, $arSrcSize[0], $arSrcSize[1] );
			}
			$ImageCall($dst, $sDest );
		}
	}


	function calcSpaceVal( $total_size )
	{
		 global $byteUnits;

		 $total_size_val = ($total_size / 1000000);

		 if(floor($total_size_val) != 0)
		 $size_of_dir = "$total_size_val $byteUnits[2]";

		 else
		 { 
		       $total_size_val = ($total_size / 1000);

			 if(floor($total_size_val) != 0)
			 $size_of_dir = floor($total_size_val)." $byteUnits[1]";

			 else
			 $size_of_dir = "$total_size_val  $byteUnits[0]";
		 }

		 return( $size_of_dir );
	}


	function editSize( $edPid, $edUn, $effect, $sp = '0', $sp2 = '0' )
	{
	      global $dirpath, $Config_datapath, $Config_spaceScheme, $tbl_userinfo, $tbl_albumlist, $tbl_pictures;

		$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid='$edPid'" );
		$row    = mysql_fetch_array( $result );
		$edAid  = $row[aid];

		$fspace = $row[o_used];		

		if($effect == "scheme")
		{ $fspace = 0; $row[t_used] = $sp; $row[i_used] = $sp2; }
		else if($row[i_used] < 0)
		$row[i_used] = 0;

		// Check Space Scheme before packing
		if($Config_spaceScheme == "AB")
		$fspace += $row[t_used] + $row[i_used];
		else if($Config_spaceScheme == "A")
		$fspace += $row[t_used];
		else if($Config_spaceScheme == "B")
		$fspace += $row[i_used];

		if($effect == "scheme")
		return($fspace);

		if($effect == "del")
		{
		   if($sp != '2')
		   {
		   $result = queryDB( "UPDATE $tbl_userinfo SET sused=sused-'$fspace', pused=pused-1 WHERE uid='$edUn'" );
		   if($sp == '0') // not when deleting album
		   $result = queryDB( "UPDATE $tbl_albumlist SET sused=sused-'$fspace', pused=pused-1 WHERE aid='$edAid'" );
		   }
		   $result = queryDB( "DELETE FROM $tbl_pictures WHERE pid='$edPid'" );

		   unlink($dirpath."$Config_datapath/$edUn/$row[pname]");
		   unlink($dirpath."$Config_datapath/$edUn/tb_$row[pname]");

	         if(file_exists($dirpath."$Config_datapath/$edUn/full_$row[pname]"))
		   unlink($dirpath."$Config_datapath/$edUn/full_$row[pname]");
		}

		else if($effect == "move")
		{
		   $oldname = $row[pname];
		   $newAid  = $sp;

		   if(!$sp2)
		   $sp2 = $edUn;

	   	   $newname = eregi_replace($edAid."_p", $newAid."_p", $oldname);
	         rename ($dirpath."$Config_datapath/$edUn/$oldname", "$dirpath"."$Config_datapath/$sp2/$newname");	
	         rename ($dirpath."$Config_datapath/$edUn/tb_$oldname", "$dirpath"."$Config_datapath/$sp2/tb_$newname");	
	         if(file_exists($dirpath."$Config_datapath/$edUn/full_$oldname"))
	         rename ($dirpath."$Config_datapath/$edUn/full_$oldname", $dirpath."$Config_datapath/$sp2/full_$newname");

		   $result = queryDB( "UPDATE $tbl_pictures SET aid='$newAid', pname='$newname' WHERE pid='$edPid'" );

		   $result = queryDB( "UPDATE $tbl_albumlist SET sused=sused-'$fspace', pused=pused-1 WHERE aid='$edAid'" );
		   $result = queryDB( "UPDATE $tbl_albumlist SET sused=sused+'$fspace', pused=pused+1 WHERE aid='$newAid'" );

		   if($sp2 != $edUn)
		   return($fspace);
		}

		else if($effect == "copy")
		{
		   $oldname = $row[pname];
		   $newAid  = $sp;
		   $result_index = queryDB( "SELECT MAX(pindex) FROM $tbl_pictures WHERE aid='$aid'" );
		   $row_index    = mysql_fetch_array( $result_index );
		   $npindex      = $row_index[0] + 1;

		   if(!$sp2)
		   $sp2 = $edUn;
   
		   $result = queryDB("INSERT INTO $tbl_pictures (`pid`, `aid`, `pname`, `pindex`, `pmsg`, `o_used`, `i_used`, `t_used`) VALUES(NULL,'$newAid','$newAid','$npindex','$row[pmsg]','$row[o_used]','$row[i_used]','$row[t_used]')");

		   $npid = mysql_insert_id();
	   	   $newname = eregi_replace("$edAid"."_"."p$edPid", "$newAid"."_"."p$npid", $oldname);

	         copy ($dirpath."$Config_datapath/$edUn/$oldname", $dirpath."$Config_datapath/$sp2/$newname");
	         copy ($dirpath."$Config_datapath/$edUn/tb_$oldname", $dirpath."$Config_datapath/$sp2/tb_$newname");
	         if(file_exists($dirpath."$Config_datapath/$edUn/full_$oldname"))
	         copy ($dirpath."$Config_datapath/$edUn/full_$oldname", $dirpath."$Config_datapath/$sp2/full_$newname");

		   $result = queryDB( "UPDATE $tbl_pictures SET pname='$newname' WHERE pid='$npid'" );

		   $result = queryDB( "UPDATE $tbl_albumlist SET sused=sused+'$fspace', pused=pused+1 WHERE aid='$newAid'" );
		   $result = queryDB( "UPDATE $tbl_userinfo SET sused=sused+'$fspace', pused=pused+1 WHERE uid='$edUn'" );

		   if($sp2 != $edUn)
	         return($fspace);
		}

		else if($effect == "movalb")
		{
		   $aid = $sp;

		   $oldname = $edPid;
		   $newname = $edUn;

		   $result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid='$aid'" );
		   $row = mysql_fetch_array($result);
		   $fspace = $row[sused]; $pused = $row[pused];

		   $result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid='$aid'" );
		   while($row = mysql_fetch_array($result))
		   {
		   copy ($dirpath."$Config_datapath/$oldname/$row[pname]", "$dirpath"."$Config_datapath/$newname/$row[pname]");	
		   unlink ($dirpath."$Config_datapath/$oldname/$row[pname]");

		   copy ($dirpath."$Config_datapath/$oldname/tb_$row[pname]", "$dirpath"."$Config_datapath/$newname/tb_$row[pname]");
		   unlink($dirpath."$Config_datapath/$oldname/tb_$row[pname]");

			   if(file_exists($dirpath."$Config_datapath/$oldname/full_$row[pname]"))
			   {
			   copy ($dirpath."$Config_datapath/$oldname/full_$row[pname]", "$dirpath"."$Config_datapath/$newname/full_$row[pname]");
			   unlink ($dirpath."$Config_datapath/$oldname/full_$row[pname]");
			   }
		   }

	         $result = queryDB( "UPDATE $tbl_albumlist SET uid='$newname' WHERE aid='$aid'");

	         $result = queryDB( "UPDATE $tbl_userinfo SET sused=sused-'$fspace', pused=pused-'$pused' WHERE uid='$oldname'");
	         $result = queryDB( "UPDATE $tbl_userinfo SET sused=sused+'$fspace', pused=pused+'$pused' WHERE uid='$newname'");
		}
	}

	function userLogoff()
	{
		global $usr, $Config_LangLoad, $effect, $defDB, $tbl_userinfo, $username, $dirpath, $Config_imgdir, $strSuccess, $strAdminLoggedOff, $strAdminstration, $strClickhere, $strRedirecting, $strElse, $Config_SiteTitle;

		if($effect == "1")
		{
      	    $result = queryDB( "UPDATE $tbl_userinfo SET sessiontime='0' WHERE admin = '0'" );
		}

		else
		{
	          $result = queryDB( "UPDATE $tbl_userinfo SET sessiontime='0' WHERE uid='$username'" );

		    $usr->Header($Config_SiteTitle ." :: $strAdminstration", '1', "usrmngt.php?username=$username&dowhat=show");
		    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
		    $errMsg = "<b>$username $strAdminLoggedOff, $strRedirecting...</b><br>$strElse <a href=\"usrmngt.php?username=$username&dowhat=show\">$strClickhere</a>\n";
		    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
		    echo("<BR>");
	          $usr->Footer();
		    exit;
		}
	}
	function DisplayDate( $datemake )
	{
		if($datemake)
		{
			$reg_year  = substr($datemake, 0, 4);
			$reg_month = substr($datemake, 4, 2);
			$reg_date  = substr($datemake, 6, 2);
			return(date("M j, Y", mktime (0,0,0,$reg_month,$reg_date,$reg_year)));
		}
		else
		{
			return("-");
		}
	}
}

class UserCookie extends Cookie
{
    var $uid;

    function UserCookie()
    {
        $this->Cookie();
    }
    function GetRow( $bRefresh = false )
    {
        global $tbl_userinfo, $Config_logout_time;

	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        if ( empty($this->row) or $bRefresh )
        {
            $result = queryDB( "SELECT * FROM $usertable WHERE $fld_uid='$this->uid'" );
            $this->row = mysql_fetch_array($result);
            mysql_free_result($result);
        }
        $this->npExpire = $Config_logout_time;
    }
    function LoggedIn( $passCheck = '0' )
    {
	  global $Config_logout_time,$tbl_userinfo, $Config_sysstatus, $Config_sysmsg, $strLoginSysShut, $SCRIPT_NAME;

	  if(preg_match("/logout/i", $SCRIPT_NAME))
	  $passCheck = 1;

	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session, $intergrate_known;
	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        $sUid = $this->get($cookie_uid);
        $sUidpassword = $this->get($cookie_password);
	  $rUid = $this->get("uid");

        if ( empty($this->uid) ) { $this->uid = $sUid; }
        if ( empty($this->ruid) ) { $this->ruid = $rUid; }
        if ( empty($this->uidpassword) ) { $this->uidpassword = $sUidpassword; }

        if ( !empty($this->uid) ) { $this->GetRow(); }
        if ( !empty($this->uidpassword) ) { $this->GetRow(); }

	  if( !$this->ruid )
	  $passCheck = 0;

	  if($integrate_db && !$passCheck && $this->ruid)
        {
		$result = queryDB("SELECT COUNT(*) FROM $tbl_userinfo WHERE uid='$this->ruid'");
		$row    = mysql_fetch_array($result);
		if(!$row[0])
		{
			$this->dbregister( $this->ruid );
		}
	  }

	  $result_session = queryDB("SELECT sessiontime FROM $tbl_userinfo WHERE uid='$this->ruid'");
	  $row_session    = mysql_fetch_array( $result_session );

	  $return_val = 0;

	  if((!empty( $rUid ) && !empty( $sUid ) && !empty( $sUidpassword ) && ($this->row[$fld_password] == $sUidpassword)))
	  {
		if(!$passCheck)
		{
		  if(($row_session["sessiontime"] >= time()))
		  {
		  $result = queryDB( "SELECT admin FROM $tbl_userinfo WHERE uid='$this->ruid'" );
		  $row_admin = mysql_fetch_array($result);

		  if(($Config_sysstatus == "1" || $row_admin[admin] == "1") && $Config_sysstatus != "2")
		  $return_val = 1;
		  else
		  classErrDisplay( '2' );
		  }
		}
		  else
		  $return_val = 1;
	  }

//      echo("- ".$this->get("bbpassword"));
//	echo($sUid);
//	exit;

    if($return_val == 1)
    $this->getUserInfo();

    return ( $return_val );
    }
    function getUserInfo()
    {
		global $UserInfo, $uid, $tbl_userinfo;

		$result   = queryDB("SELECT * FROM $tbl_userinfo WHERE uid = '$uid'");
		$UserInfo = mysql_fetch_array( $result );
    }
    function Login( $sUid, $sUidpassword, $rUid, $loginTime )
    {
        global $tbl_userinfo, $Config_logout_time;

	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        $this->uid = $sUid;
	  $this->uidpassword = $sUidpassword;
        $this->ruid = $rUid;
	  $this->loginTime = $loginTime;

   	  $result = queryDB("UPDATE $usertable SET $fld_session=" .time(). "+$this->loginTime WHERE $fld_uid='$this->uid'");

   	  if($tbl_userinfo != $usertable)
   	  $result = queryDB("UPDATE $tbl_userinfo SET sessiontime=" .time(). "+$this->loginTime WHERE uid='$this->ruid'");

        $this->GetRow( true );

        $this->set( $cookie_uid, $this->uid );
        $this->set( $cookie_password, $this->uidpassword );

	  if($rUid != $sUid)     
	  $this->set( "uid", $this->ruid );

	  if($cookie_session)
        $sUidsession = $this->set($cookie_session, time());
    }
    function ReLogin( $sUidpassword )
    {
	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        $this->set( $cookie_password, $sUidpassword );
    }
    function Logout()
    {
     global $tbl_userinfo;
     global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $intergrate_known, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

     $rUid = $this->get("uid");
     if($integrate_db && $intergrate_known == 'vb')
     {
	     $rUid_id = $this->get($cookie_uid);
	     $this->delete( $cookie_uid );
	     $this->delete( $cookie_password );
	     $this->delete( $cookie_session );
	     $this->delete( "bbstyleid" );
	     $result = queryDB( "UPDATE $tbl_user_alter SET $fld_session='".(time()-900)."',lastvisit='".time()."' WHERE $fld_uid_name='$rUid'" );
	     $result = queryDB( "DELETE FROM session WHERE $fld_uid='$rUid_id' " );
     }
     
     $this->delete( "uid" );
     $result = queryDB("UPDATE $tbl_userinfo SET sessiontime='0' WHERE uid='$this->ruid'");
    }
    function UploadRefresh( $sUid )
    {
        global $tbl_userinfo, $Config_logout_time;
	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        $this->uid = $sUid;

        $result = queryDB("UPDATE $usertable SET $fld_session=" .time(). "+$Config_logout_time WHERE $fld_uid='$this->uid'");
	  if($tbl_userinfo != $usertable)
	  $result = queryDB("UPDATE $tbl_userinfo SET sessiontime=" .time(). "+$Config_logout_time WHERE uid='$this->ruid'");

        $this->GetRow( true );
        $this->set( $cookie_uid, $this->uid );
        $this->set( $cookie_password, $this->uidpassword );
    }
    function RefreshAll()
    {
        global $tbl_userinfo, $Config_logout_time;
	  //
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session;

	  $usertable = $tbl_userinfo;
	  if($tbl_user_alter)
	  $usertable = $tbl_user_alter;
	  //

        $nSafeTime = ( (int) (0.4 * $Config_logout_time) );

        if ( (time() - $this->row[$fld_session] + $Config_logout_time) >= $nSafeTime )
        {
            $result = queryDB( "UPDATE $usertable SET $fld_session=" . time() . "+$Config_logout_time WHERE $fld_uid='$this->uid'" );
	      if($tbl_userinfo != $usertable)
	      $result = queryDB("UPDATE $tbl_userinfo SET sessiontime=" . time() . "+$Config_logout_time WHERE uid='$this->ruid'" );

            $this->GetRow( true );

            $this->refresh( $cookie_uid );
            $this->refresh( $cookie_password );
        }
    }

	function dbregister( $uid )
	{
	  global $fld_uid, $fld_uid_name, $fld_password, $fld_session, $integrate_db, $tbl_user_alter, $cookie_uid, $cookie_password, $cookie_session, $intergrate_known;
	  global $Config_dprefs, $Config_default_space, $Config_default_album, $Config_default_photo, $Config_default_remind, $Config_langCode, $Config_default_uvalid, $tbl_userinfo, $Config_logout_time, $now_date, $strLoginError3, $dirpath, $Config_datapath;

	  if($intergrate_known == "vb");
	  $result = queryDB("SELECT *,COUNT($fld_uid_name) as usrcount FROM $tbl_user_alter WHERE $fld_uid_name='$uid' && usergroupid!='3' GROUP BY $fld_uid");

	  $row    = mysql_fetch_array( $result );

	 if(!$row[usrcount])
	 {
		classErrDisplay( '1' );
	 }

	 classErrDisplay ( '3' );
    }
}


function classErrDisplay( $errMsg )
{
		    global $dirpath, $uid, $strIndexWelcome, $strRedirecting, $SCRIPT_NAME;

		    if(!preg_match("/dbintegrate_disp/i", $SCRIPT_NAME))
		    {
		    $csr = new ComFunc();

		    $errMsg = urlencode($errMsg);
		    $reurl = $dirpath."user/dbintegrate_disp.php?errmsg=$errMsg";
                Header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
                Header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                Header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
                Header ("Pragma: no-cache");

echo <<< _HTML_END_

<head>
<meta http-equiv="refresh" content="1;URL=$reurl">	
</head>
<body>
<div align="center" style="font-family: Verdana; font-size: 10pt; font-weight: bold; color: #990000;">
$strIndexWelcome $uid, $strRedirecting...
<div>
</body>

_HTML_END_;

		    exit;
		}

}
function UserLoggedIn()
{
    $ucook = new UserCookie();
    return ( $ucook->LoggedIn() );
}

function CheckEmail( $sAddr)
{
    return ( preg_match( "/^(.*[\<|\(]{1})?(([a-z\._\-0-9])+\@([a-z\._\-0-9])+\.([a-z])+)?([\>|\)]{1})?$/i", $sAddr) );
}

class Html
{
        var $ucook;

        function Html()
        {
           	$this->ucook = new UserCookie();
     	      if ( $this->ucook->LoggedIn() )
            {
           	   	$this->ucook->RefreshAll();
           	}
 	  }

        function Header($pgTitle = '', $refresh_sec = '', $refresh_url = '', $loaderpg = '', $ref = '0', $icon_links = '0')
	  {
	  global $dirpath, $Config_SiteTitle, $Config_SiteTitle, $uid, $tbl_userinfo, $headcontent, $Config_main_bgcolor, $Config_main_bgimage, $head_header, $Config_table_size, $Config_imgdir, $topbar_first, $topbar_second, $lang_charset, $HTTP_REFERER, $HTTP_HOST;

	  global $strAdmin, $strIndexHome, $strMenusHelp, $strBack;

	  if(!$pgTitle)
	  { $pgTitle = $Config_SiteTitle; }

	  $admin_result = queryDB( "SELECT * FROM $tbl_userinfo WHERE admin='1' && uid='$uid'" );
        $admin_nr = mysql_num_rows( $admin_result );

	  if($admin_nr)
	  $admin_link = "<a href=\"".$dirpath."user/admin/index.php\"><img src=\"".$dirpath.$Config_imgdir."/design/icon_admin.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strAdmin\"></a> ";

	  if($loaderpg == "onload" && $pgTitle != "onload")
	  { 
		$onloadshow = "onLoad=\"onLoad()\"";
		$head_header = eregi_replace("<body", "<body $onloadshow", $head_header);
	  }

	  if(!$icon_links)
	  {
	  $domain_name = preg_replace("/^www\./i", "", $HTTP_HOST);
	  if($ref == '0' && !preg_match("/(login.php)/i", $HTTP_REFERER) && preg_match("/($domain_name)/i", $HTTP_REFERER))
	  { $back_link = '<a href="javascript:history.back(1);"><img src="'.$dirpath.$Config_imgdir.'/design/icon_back.gif" width="16" height="16" alt="'.$strBack.'" border=0></a> '; }
	  else
	  { $back_link = '<img src="'.$dirpath.$Config_imgdir.'/design/icon_back.gif" width="16" height="16" border=0> '; }

	  $icon_links = "{$back_link}<img src=\"{$dirpath}$Config_imgdir/design/icon_front.gif\" width=\"16\" height=\"16\">&nbsp;";
	  }

	?>
	  <html>
	  <head>
	  <title><?php echo $pgTitle; ?></title>
<?php
	if($refresh_sec)
	echo ('<meta http-equiv="refresh" content="'.$refresh_sec.';URL='.$refresh_url.'">');

	if($lang_charset)
	echo ('<meta http-equiv="content-type" content="text/html; charset='.$lang_charset.'">');
?>
	<?php echo $headcontent ?>
	</head>
	<?php echo $head_header ?>
      <table width="<?php echo $Config_table_size ?>%"  border=0 cellspacing=1 cellpadding=4 align=center class="tableProperties">
       <tr class="topbarFirst"> 
        <td class="ts">

      <table width="100%" border=0 cellspacing=0 cellpadding=0 align=center>
	   <tr class="ts">
	    <td><?php echo $topbar_first ?>&nbsp;</td>
          <td width="140">
              <div align="right">
<a href="<?php echo $dirpath ?>user/"><img src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_home.gif" width="16" height="16" border="0" alt="<?php echo $strIndexHome ?>"></a>
<a href="<?php echo $dirpath ?>user/help.php"><img src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_help.gif" width="16" height="16" alt="<?php echo $strMenusHelp ?>" border="0"></a>
<?php echo $admin_link;
   echo $icon_links; ?>
	 	  </div>
          </td>
	  </tr>
	</table>

     </td>
   </tr>
        <tr valign="middle"> 
          <td  class="topbarSecond" colspan=2> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr> 
		<td class="ts" valign=middle><?php echo $topbar_second ?></td>
		<td width=1><img src=<?php echo $dirpath.$Config_imgdir ?>/blank.gif width=1 height=12 border=0></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr valign="top"> 
          <td background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>" height="2" colspan=2> 
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td height="200" valign=top class=tn> 
<?php
	  }

        function Footer()
	  {
	  global $dirpath, $head_footer, $Config_table_size, $Config_imgdir, $starttime;
?>
	                  <p>&nbsp;</p>
			     </td>
	      	   </tr>
		      </table>  
			</td>
                </tr>
              </table>
<?php
global $copyright_footer, $bottom_bar, $strProcessTime;
$csr_temp = new ComFunc();

echo($bottom_bar);
echo($copyright_footer);

echo $head_footer;

if($starttime) {
$totaltime = CalcStartTime('end');
echo("<p><div class=\"ts\" align=\"center\">".$csr_temp->LangConvert($strProcessTime, $totaltime)."</div>"); }

if(!preg_match("/<\/HTML>/i", $head_footer))
echo ("</HTML>");

}

        function HeaderOut( $pgTitle = '', $refresh_sec = '', $refresh_url = '', $loaderpg = '', $temp_var='0', $icon_links='0' )
	  {
	  global $dirpath, $Config_SiteTitle, $Config_buyline, $Config_main_bgcolor, $Config_main_bgimage, $headcontent, $head_header, $Config_table_size, $Config_imgdir, $topbar_first_out, $lang_charset, $strIndexHome;

	  if($pgTitle == "onload")
	  { 
		$pgTitle = "";
		$onloadshow = "onLoad=\"onLoad()\"";

		$head_header = eregi_replace("<body", "<body $onloadshow", $head_header);
	  }

	  else if($loaderpg == "onload")
	  { 
		$onloadshow = "onLoad=\"onLoad()\"";
		$head_header = eregi_replace("<body", "<body $onloadshow", $head_header);
	  }

	  if(!$pgTitle)
	  { $pgTitle = "$Config_SiteTitle :: $Config_buyline"; }

	  if(!$icon_links)
	  $icon_links = "&nbsp;<img src=\"{$dirpath}$Config_imgdir/design/icon_back.gif\" width=\"16\" height=\"16\">&nbsp;<img src=\"{$dirpath}$Config_imgdir/design/icon_front.gif\" width=\"16\" height=\"16\">&nbsp;";

?>
	  <html>
	  <head>
	  <title><?php echo $pgTitle; ?></title>
<?php
	if($refresh_sec)
	echo ('<meta http-equiv="refresh" content="'.$refresh_sec.';URL='.$refresh_url.'">');

	if($lang_charset)
	echo ('<meta http-equiv="content-type" content="text/html; charset='.$lang_charset.'">');

?>
	  <?php echo $headcontent ?>
	  </head>

	<?php echo $head_header ?>
      <table width="<?php echo $Config_table_size ?>%" border=0 cellspacing=1 cellpadding=4 align=center class="tablePropertiesLOGOUT">
        <tr class="topbarFirstOUT">
          <td class="topbarFirstOUT">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="ts"> 
                <td><?php echo $topbar_first_out ?>&nbsp;</td>
                <td width="140"> 
                  <div align="right"><a href="<?php echo $Config_mainurl ?>"><img src="<?php echo $dirpath.$Config_imgdir ?>/design/icon_home.gif" width="16" height="16" border="0" alt="<?php echo $strIndexHome ?>"></a> <?php echo $icon_links ?></div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr valign="top"> 
         <td background="<?php echo $Config_main_bgimage ?>" bgcolor="<?php echo $Config_main_bgcolor ?>" height="2"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td>
<?php
	  }

        function FooterOut()
	  {
	  global $dirpath, $head_footer, $Config_table_size, $Config_imgdir, $starttime;
?>
	                  <p>&nbsp;</p>
			     </td>
	      	   </tr>
		      </table>  
			</td>
                </tr>
              </table>

<?php
global $copyright_footer, $bottom_bar_out, $strProcessTime;
$csr_temp = new ComFunc();

echo($bottom_bar_out);
echo($copyright_footer);
echo $head_footer;

if($starttime) {
$totaltime = CalcStartTime('end');
echo("<p><div class=\"ts\" align=\"center\">".$csr_temp->LangConvert($strProcessTime, $totaltime)."</div>"); }

}

	  function errMessage( $errMsg, $MsgID, $errImg='error', $errSize = "50" )
	  {
	  global $dirpath, $Config_imgdir;

	  if(preg_match("/Not Logged/i", $errMsg))
	  { echo("<br>"); $errSize = "60"; }
?>

<br>
<table width="<?php echo $errSize ?>%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="12" height="12" valign="top" align="left"><img src="<?php echo ($dirpath.$Config_imgdir); ?>/form_tl.gif" width="12" height="12"></td>
    <td bgcolor="#CECECE" class=ts><img src="<?php echo ($dirpath.$Config_imgdir); ?>/blank.gif" height=2></td>
    <td width="12" height="12" align="right" valign="top"><img src="<?php echo ($dirpath.$Config_imgdir); ?>/form_tr.gif" width="12" height="12"></td>
  </tr>
  <tr bgcolor="#CECECE"> 
    <td bgcolor="#CECECE" width="12" class=ts><img src="<?php echo ($dirpath.$Config_imgdir); ?>/blank.gif" height=2></td>
    <td>

	    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		    <tr>
		    <td align="right" valign="top" width="80">
		      <div align="center"><img src="<?php echo ($dirpath.$Config_imgdir."/".$errImg); ?>.gif" width="60" height="53"></div>
		    </td>
	         <td bgcolor="#CECECE" width="5" class=ts><img src="<?php echo ($dirpath.$Config_imgdir); ?>/blank.gif" height=2></td>
		    <td valign="middle" class=tn><span class=warn><b><?php echo $MsgID; ?></b></span>
      		<br><span class=tn><font color="#660000"><?php echo ( $errMsg ); ?></font></span>
    		</td>
	    </td></tr></table>

    </td>
    <td bgcolor="#CECECE" width="12" class=ts><img src="<?php echo ($dirpath.$Config_imgdir); ?>/blank.gif" height=2></td>
  </tr>
  <tr> 
    <td width="12" height="12" valign="top" align="left"><img src="<?php echo ($dirpath.$Config_imgdir); ?>/form_bl.gif" width="12" height="12"></td>
    <td bgcolor="#CECECE" class=ts><img src="<?php echo ($dirpath.$Config_imgdir); ?>/blank.gif" height=2></td>
    <td width="12" height="12" align="right" valign="top"><img src="<?php echo ($dirpath.$Config_imgdir); ?>/form_br.gif" width="12" height="12"></td>
  </tr>
</table>

<?php
	  }

		// optional function
		function SaleAlbinator( $sizeClass = "tn", $alignWhere = "center", $breakIT = "<br>" )
		{
		?>
<div class="<?php echo $sizeClass; ?>" align="<?php echo $alignWhere ?>">
<?php echo $breakIT ?>
Are you a website owner? Get Albinator for your website, <a href="http://www.albinator.com/product/" target="_blank">get more info</a></div>

		<?php
		}
}


function CalcStartTime( $dowhat )
{
	if($dowhat == "start")
	{
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$starttime = $mtime;

		return($starttime);
	}

	else
	{
	global $starttime;

		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime); 

		return($totaltime);
	}
}


class PagedResultSet
{  
	var $result;	
	var $pageSize;
	var $page;
	var $row;

	function PagedResultSet($query,$pageSize)
	{
	   global $sf;
	   $this->result = queryDB($query);
	   $this->pageSize = $pageSize;
	   if ((int)$sf <= 0) $sf = 1;
	   
	   if ($sf > $this->getNumPages())
	   $sf = $this->getNumPages();

	   $this->setPageNum($sf);
      }

	function getNumPages()
      {
         if (!$this->result) return FALSE;
    	   return ceil(mysql_num_rows($this->result)/(float)$this->pageSize);
      }

	function setPageNum($pageNum)
      {
         if ($pageNum > $this->getNumPages() or $pageNum <= 0) return FALSE;
         $this->page = $pageNum;
         $this->row = 0;
         mysql_data_seek($this->result,($pageNum-1) * $this->pageSize);
      }

	function getPageNum()
      { return $this->page; }

  	function isLastPage()
	{ return ($this->page >= $this->getNumPages()); }

      function isFirstPage()
	{ return ($this->page <= 1); }

	function fetchArray()
      {
        if (!$this->result)
        return FALSE;

        if ($this->row >= $this->pageSize)
        return FALSE;

        $this->row++;
        return mysql_fetch_array($this->result);
      }

	function getPageNav($queryvars = '')
      {
		global $strNext, $strPrev, $strPage, $sf;
		$pageNoDispLimit = 10;

		if($queryvars)
		$queryvars = "&$queryvars";

		if ($this->getNumPages() > 1)
		{
			if($this->getNumPages() > $pageNoDispLimit)
			{
				if($sf > ($pageNoDispLimit/2))
				{
					$start_value = $sf - ($pageNoDispLimit/2);					
					$end_value   = $sf + ($pageNoDispLimit/2);
				}
				else
				{
					$start_value = 1;
					$end_value   = $sf + ($pageNoDispLimit - 1);
				}				
			}
			else
			{
				$start_value = 1;
				$end_value   = $this->getNumPages();
			}

			if($end_value > $this->getNumPages())
			$end_value = $this->getNumPages();

	      	for ($i=$start_value; $i<=$end_value; $i++)
                 	{
                 		  if ($i==$this->page)
				  $nav .= "$strPage $i ";
				  else
			        $nav .= "<a href=\"?sf={$i}".
	                    $queryvars."\">{$i}</a> ";
     	            }
		}

		if (!$this->isFirstPage())
            {
		   if($start_value > 1)
		   $nav = " <a href=\"?sf=1".$queryvars."\">1</a> ... ".$nav;

               $nav = "<a href=\"?sf=".
	         ($this->getPageNum()-1).$queryvars.'">&lt; '.$strPrev.'</a> '.$nav;
            }

   		if (!$this->isLastPage())
		{
		   if($end_value < $this->getNumPages())
		   $nav = $nav." ... <a href=\"?sf=".$this->getNumPages().$queryvars."\">".$this->getNumPages()."</a> ";

	         $nav .= "<a href=\"?sf=".
               ($this->getPageNum()+1).$queryvars.'">'.$strNext.' &gt;</a> ';
            }

        return $nav;
       }
}

// do not change this...  Language Must be English
$copyright_footer=<<<__COPYRIGHT_FOOTER
          <p class="ts" align="center">powered by: <a href="http://www.albinator.com/product/" target="_blank">Albinator</a> &copy; copyright 2001-02, <a href="http://www.mgzhome.com" target="_blank" class="nounderts">mgZhome</a>

<!-- optional part below as footer -->
<br>$strAlbinatorBuy

__COPYRIGHT_FOOTER;

?>