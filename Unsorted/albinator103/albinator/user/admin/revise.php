<?php
	$dirpath = "$Config_rootdir"."../../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	$total_per_page = 10;
	
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

	$effect = 1;
	$csr->userLogoff();

 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='We are upgrading the system' WHERE fname='sysmsg'" );
 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='0' WHERE fname='sysstatus'" );
?>
	 <html>
 	 <head>
       <title><?php echo("$strRevising DB"); ?></title>
	 <link rel="stylesheet" HREF="<?php echo "{$dirpath}essential/{$Config_LangLoad}_default.css"; ?>" type="text/css">
	 <META HTTP-EQUIV="Expires" CONTENT="Mon, 06 Jan 1990 00:00:01 GMT"> 

	 <?php

	 $result_user = queryDB( "SELECT COUNT(uid) FROM $tbl_userinfo" );
       $total_users = mysql_fetch_array( $result_user );
	 
	 if($total_users[0] > ($sf + $total_per_page))
	 echo('<meta http-equiv="refresh" content="1;URL=revise.php?sf='. ($sf + $total_per_page) .'">');
	 else
	 $flag_end = true;

	 ?>

	 <script>
	 self.focus()
       </script>
       </head>
       <body text=#FFFFFF bgcolor=#000000 link=#CCCCCC alink=#CCCCCC vlink=#CCCCCC>
	 <p>&nbsp;</p>
	 <p>&nbsp;</p>
	 <div align=center class='tn'>
	 <?php echo("$strAdminSysCmt1, $strAdminSysCmt2<br>
	 $strRevising DB..."); ?>
<?php
	 $total_size = 0; $album_total_size = 0;
	 if(!$sf) $sf = 0;

	 $result_user = queryDB( "SELECT uid FROM $tbl_userinfo LIMIT $sf,$total_per_page" );

  while($row_user = mysql_fetch_array( $result_user ))
  {
	 $result_alb = queryDB( "SELECT aid FROM $tbl_albumlist WHERE uid='$row_user[uid]'" );
	 while($row_alb = mysql_fetch_array( $result_alb ))
	 {
		 $result_pic = queryDB( "SELECT pname, pid FROM $tbl_pictures WHERE aid = '$row_alb[aid]'" );
		 while($row_pic = mysql_fetch_array( $result_pic ))
		 {
			$or_size = 0; $tb_size = 0; $inter_size = 0;

			if(file_exists("$dirpath"."$Config_datapath/$row_user[uid]/$row_pic[pname]"))
		 	$or_size = filesize ("$dirpath"."$Config_datapath/$row_user[uid]/$row_pic[pname]");

			if(eregi("A", $Config_spaceScheme))
			{
	if(file_exists("$dirpath"."$Config_datapath/$row_user[uid]/tb_$row_pic[pname]"))
		 	$tb_size = filesize ("$dirpath"."$Config_datapath/$row_user[uid]/tb_$row_pic[pname]");
			}

			if(eregi("B", $Config_spaceScheme) && 
file_exists($dirpath."$Config_datapath/$row_user[uid]/full_$row_pic[pname]"))
		 	$inter_size = filesize ("$dirpath"."$Config_datapath/$row_user[uid]/full_$row_pic[pname]");

			$result = queryDB( "UPDATE $tbl_pictures SET o_used='$or_size', t_used='$tb_size', i_used='$inter_size' WHERE pid='$row_pic[pid]'" );

			$album_total_size += $or_size + $tb_size + $inter_size;
			$total_size += $or_size + $tb_size + $inter_size;
		 }
	 $result = queryDB( "UPDATE $tbl_albumlist SET sused='$album_total_size' WHERE aid='$row_alb[aid]'" );
	 $album_total_size = 0;
	 }
    $result = queryDB( "UPDATE $tbl_userinfo SET sused='$total_size' WHERE uid='$row_user[uid]'" );
    $total_size = 0;
   }

 	 $result = queryDB( "UPDATE $tbl_config SET fnvalue='0' WHERE fname='sysmsg'" );
 	 $result = queryDB( "UPDATE $tbl_config SET fnvalue='1' WHERE fname='sysstatus'" );
 ?>

	 <?php 

	 if($flag_end == true)
	 {
	 	echo("<b>$strDone</b><br><br>$strAdminSysCmt3...<br><p>&nbsp;</p>[<a href=javascript:self.close();>$strClose<a>]");
	 	//echo("\n".'<script>'."\n".'self.opener.location = "config.php?dowhat=show"'."\n".'</script>'."\n");
	 }
	 else
	 {
	 	echo("<br><br><br><b>processing...<br>don't press anything<br></b>");
	 }

	 ?>

       </div>

	 </body>
	 </html>
