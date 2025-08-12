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

	$effect = 1;
	$csr->userLogoff();

	$result = queryDB( "UPDATE $tbl_config SET fnvalue='We are upgrading the system' WHERE fname='sysmsg'" );
 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='0' WHERE fname='sysstatus'" );

      $result_user = queryDB( "SELECT COUNT(uid) FROM $tbl_userinfo" );
	$row_user    = mysql_fetch_array( $result_user );

	$count = 0;
	for($c=0;$c<$row_user[0];$c++)
	{
		if($uid_list[$c])
		{
			$flag = 1;
			$count++;
			$where_list .= "uid = '".$uid_list[$c]."' || ";
		}
	}

	$where_list = substr($where_list, 0, -4);	

	if($flag != 1)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $errMsg = "<b>No Selection, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
	}

	if($count == 1 && $level != "1")
	{
      $result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid='$uid_list[0]'");
	$nr = mysql_num_rows( $result );

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: Resize");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
?>

<script language="Javascript">
<!--
function SetChecked(val) 
{
	dml=document.userList;
	len = dml.elements.length;

	var i=0;

	for( i=0 ; i<len ; i++) 
	{
		if (dml.elements[i].name=='album_list[]')
		{
			dml.elements[i].checked=val;
		}
	}
}
//-->
</script>

<div align="center" class='ts'><a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a></div>

<form action="resize_final.php" method="post" name="userList">
<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr class="tn">

<?php echo("
    <td width=\"5%\">&nbsp;</td>
    <td width=\"75%\"><b>$strAlbum $strName</b></td>
    <td width=\"20%\"><b>$strPhoto$strPuralS</td>
"); ?>

  </tr>

<?php

$i = 0;

	while($row = mysql_fetch_array( $result ))
	{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }

?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>">
    <td><input type="checkbox" name="album_list[]" value="<?php echo($row[aid]); ?>"></td>
    <td><?php echo("$row[aname]"); ?></td>
    <td><?php echo("$row[pused]"); ?></td>
  </tr>

<?php
	}			
?>

  <tr class="tn">
	  <input type="hidden" name="uid_list[0]" value="<?php echo($uid_list[0]); ?>">
	  <input type="hidden" name="level" value="1">
    <td colspan="4" align="right"><input type="submit" name="resize" value="resize &gt;"></td>
  </tr>


<?php
	echo("</table>");
?>

	</form>

<div align="center" class='ts'><a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a></div>

<?
	$usr->Footer(); 
	exit;
	}
	else if($count == 1)
	{
      $result_albums = queryDB( "SELECT COUNT(aid) FROM $tbl_albumlist WHERE uid='$uid_list[0]'" );
	$row_albums    = mysql_fetch_array( $result_albums );

	$flag = 0;
	$count = 0;
	for($c=0;$c<$row_albums[0];$c++)
	{
		if($album_list[$c])
		{
			$flag = 1;
			$count++;
			$where_list2 .= "aid = '".$album_list[$c]."' || ";
		}
	}

	$where_list2 = substr($where_list2, 0, -4);

	if($flag != 1)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $errMsg = "<b>No Selection, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
	}
	}
	else
	$where_list2 = "uid = '$row_user[uid]'";

	set_time_limit(0);

?>
 	 <head>
       <title><?php echo("Resizing Files"); ?></title>
	 <link rel="stylesheet" HREF="<?php echo "{$dirpath}essential/{$Config_LangLoad}_default.css"; ?>" type="text/css">
	 <script>
	 self.focus()
       </script>
       </head>
       <body text=#FFFFFF bgcolor=#000000 link=#CCCCCC alink=#CCCCCC vlink=#CCCCCC>
	 <p>&nbsp;</p>
	 <p>&nbsp;</p>
	 <p>&nbsp;</p>
	 <div align=center class=tn>
	 Resizing files...

<?php

  $result_user = queryDB( "SELECT uid FROM $tbl_userinfo where $where_list" );

  while($row_user = mysql_fetch_array( $result_user ))
  {
 	 echo("<br>- ".$row_user[uid]);
	 $result_alb = queryDB( "SELECT aid FROM $tbl_albumlist WHERE $where_list2" );

	 while($row_alb = mysql_fetch_array( $result_alb ))
	 {
		 $result_pic = queryDB( "SELECT pname, pid FROM $tbl_pictures WHERE aid = '$row_alb[aid]'" );
		 while($row_pic = mysql_fetch_array( $result_pic ))
		 {
			$big_image = 0; $done_resize = 1;
			$ext = "";

			error_reporting(0);
		 	$size = GetImageSize ("$dirpath"."$Config_datapath/$row_user[uid]/$row_pic[pname]");
			error_reporting(E_ERROR | E_WARNING);
			
			if($size[0])
			{
			$picname = $row_pic[pname];

	 # to restrict the custom width max size
	 if($Config_exceed_width || $Config_exceed_height) // check if there are not zeros
	 {
	  if(!$Config_exceed_width)
	  $Config_exceed_width = $size[0];
	  if(!$Config_exceed_height)
	  $Config_exceed_height = $size[1];

	   if($size[0] > $Config_exceed_width || $size[1] > $Config_exceed_height)
	   { 
		$ratiosize = $size[0] / $size[1];
	 	$ht = $Config_exceed_width / $ratiosize;
	      $ht = floor($ht);
		$wt = $Config_exceed_width;

		if($ht > $Config_exceed_height)
		{
		#$ratiosize = $Config_exceed_width / $Config_exceed_height;
	 	$wt = $Config_exceed_height * $ratiosize;
	      $wt = floor($wt);
		$ht = $Config_exceed_height;
		}

		if($Config_forceSize == "1")
		{ $fullval = ""; $big_image = 0; }
	      else
		{ $fullval = "full_"; $big_image = 1; }

		error_reporting(0);
		$size = GetImageSize("$dirpath"."$Config_datapath/$row_user[uid]/$picname");
		error_reporting(E_ERROR | E_WARNING);
		if($size[0] > $size[1])
		{ $tbwt = $Config_tbwidth_short;
		  $tbht = $Config_tbheight_short; }
		else
		{ $tbwt = $Config_tbwidth_long;
		  $tbht = $Config_tbheight_long; }


		if($Config_ResizeBy == "1" || $Config_ResizeBy == "3")
		{
		$done_resize = 0;
		$ext = giveExtension( $picname );

			if($ext)
			{
				$fileType = strtoupper($ext);
				$csr->ResizeImg($picname, "$fullval".$picname, $fileType, $wt, $ht, $row_user[uid]);
				$csr->ResizeImg($picname, "tb_".$picname, $fileType, $tbwt, $tbht, $row_user[uid]);
				$done_resize = 1;
			}
		}

		else
		{
	virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$row_user[uid]&wt=$wt&ht=$ht&fn=$picname&callwhat=reimageb");
	virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$row_user[uid]&wt=$tbwt&ht=$tbht&fn=$picname&callwhat=reimage");
		$done_resize = 1;
		}


		$big_image = 1;		
	  }
      }

		if($big_image == 0 && $done_resize == 1 && file_exists($dirpath."$Config_datapath/$row_user[uid]/full_$picname"))
		{
			$result = queryDB( "UPDATE $tbl_pictures SET i_used='0' WHERE pid='$row_pic[pid]'" );
			unlink("$dirpath"."$Config_datapath/$row_user[uid]/full_$picname");
		}

			}
		 }
	 }
   }

 	 $result = queryDB( "UPDATE $tbl_config SET fnvalue='0' WHERE fname='sysmsg'" );
 	 $result = queryDB( "UPDATE $tbl_config SET fnvalue='1' WHERE fname='sysstatus'" );
 ?>

	 <?php echo("<b>$strDone, <a href='revise.php'>Revise</a></b><br><br>
	 <p>&nbsp;</p>
	 [<a href=javascript:self.close();>$strClose<a>]"); ?>
       </div>
	 </body>


<?php

function giveExtension( $picname )
{
	$ext = strtolower(substr($picname, -3, 3));

	if($ext == "jpg" || $ext == "png")
	return($ext);

	return(0);
}

?>