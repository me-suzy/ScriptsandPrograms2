<?php
	$dirpath = "$Config_rootdir"."../../";
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

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "Denid Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	

	$randpass = md5(uniqid ($Config_p));

	if(strlen($randpass) > 15)
	$randpass = substr($randpass, 0, 15);

?>

 	 <head>
       <title><?php echo $strPassword ?></title>
	 <link rel="stylesheet" HREF="<?php echo "{$dirpath}essential/{$Config_LangLoad}_default.css"; ?>" type="text/css">
	 <script>
	 self.focus()
       </script>
	 <script>
	 <!--

	function putpass() {
	self.opener.document.Register.password.value = document.rpass.pass.value;
	self.opener.document.Register.repassword.value = document.rpass.pass.value;
	self.opener.document.Register.uname.focus()
	return false;
	}

	 //-->
	 </script>
       </head>
       <body text=#FFFFFF bgcolor=#000000>
	 <div align=center>
       <form action=randpass.php name=rpass method=post>
	 <input type=text size=30 maxlength=15 name=pass value=<?php echo $randpass ?>><br>
	 <br>
	<input type=submit name=submit value="<?php echo $strMore ?>">
&nbsp;<input type=button name=put value="<?php echo $strAdd ?>" onclick="return putpass()">
	 </form>
       </div>
	 </body>
