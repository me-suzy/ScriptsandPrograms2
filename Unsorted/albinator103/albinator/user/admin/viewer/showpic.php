<?php
	$dirpath = "$Config_rootdir"."../../../";
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
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "Denied Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	if(!$aid || !$username || !$pid)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid' && pid = '$pid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError4</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }

   	$row = mysql_fetch_array( $result );
	$nr = $row[pused];

	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError4</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid' && pid='$pid'" );
	$row_orig = mysql_fetch_array( $result );

	$result = queryDB( "SELECT aname FROM $tbl_albumlist WHERE aid='$row_orig[aid]'" );
	$row_album = mysql_fetch_array( $result );

	 if($row_orig[pmsg] && $row_orig[pmsg] != '0')
	 { $pmsg = "<br>&quot; <i>$row_orig[pmsg]</i> &quot;"; }

	 $result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$username'" );
 	 $row_user = mysql_fetch_array( $result_user );

	 if(file_exists($dirpath."$Config_datapath/$username/full_".$row_orig[pname]) || $row_orig[i_used])
	 { 
	  $fullsize = 1;
	  $DIRR = "full_";
	 }
	 else
	 { $sizeval = $size[3];
	   $fullsize = 0; }

	 $result = queryDB( "SELECT pid FROM $tbl_pictures WHERE aid = '$aid' ORDER BY pindex, pname ASC" );
	 $nr_albums = mysql_num_rows( $result );
       $i = 0;

	 while($row = mysql_fetch_array( $result ))
	 {	  
	  $i++;
	  if($makenext == 1)
	  { $npid = $row[pid]; 
	    $endwhile = 1; } 
	  else if($endwhile != 1 && $row[pid] != $pid)
	  { $ppid = $row[pid]; }

	  if($row[pid] == $pid)
	  { $curpos = $i;
	    $makenext = 1; }
	  else
	  { $makenext = 0; }
	 }

	 $listlinks = "<a href=showalbum.php?username=$username&aid=$aid>Album home</a>";

	 if($nr_albums > 1 && $nr_albums > $curpos)
	 { $pagelinks = "<a href=showpic.php?aid=$aid&username=$username&pid=$npid>$strNext &gt;&gt;</a>"; 
 	   $pagelinks_icon = "<a href=showpic.php?aid=$aid&username=$username&pid=$npid><img src=\"".$dirpath.$Config_imgdir."/design/icon_front.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strNext $strPhoto\"></a>";
		$sepb= "::";
       }

	   if($curpos > 1)
	   { 
	     if($pagelinks)
	     $sepa = "::";
	
     	     $prev = 1;

	     $pagelinks = "<a href=showpic.php?aid=$aid&username=$username&pid=$ppid>&lt;&lt; $strPrev</a> :: $listlinks $sepa $pagelinks";

	     if($pagelinks_icon)
	     $pagelinks_icon ="<a href=showpic.php?aid=$aid&username=$username&pid=$ppid><img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0 alt=\"$strPrev $strPhoto\"></a>&nbsp;$pagelinks_icon"; 
	     else
	     $pagelinks_icon ="<a href=showpic.php?aid=$aid&username=$username&pid=$ppid><img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0 alt=\"$strPrev $strPhoto\"></a>&nbsp;<img src=\"".$dirpath.$Config_imgdir."/design/icon_front.gif\" width=\"16\" height=\"16\" border=0>"; 

}
	   else
	   { $pagelinks_icon = "<img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0>&nbsp;$pagelinks_icon"; }


	 if($prev != 1)
	 { $pagelinks = "$listlinks $sepb $pagelinks"; }

	$icon_links="<a href='showalbum.php?username=$username&aid=$aid'><img src=\"{$dirpath}$Config_imgdir/design/icon_ahome.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strAlbum $strIndexHome\"></a>&nbsp;$pagelinks_icon&nbsp;";
      $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum", '','','','',$icon_links);;

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=\"1\"";
	else
	$borderval = "border=\"0\"";

      error_reporting(0);
	$sizeval = GetImageSize($dirpath."$Config_datapath/$username/$DIRR".$row_orig[pname]);
 	error_reporting(E_ERROR | E_WARNING);

	if($fullsize == 1)
	$fullsize_val = "[<a href=\"javascript:Popup('showpicfull.php?username=$username&pid=$pid&aid=$aid', 'fullSize', 700, 550)\"  onmouseover=\"self.status='$strShowError5';return true;\" onmouseout=\"self.status=''\">$strShowError6</a>]";

      echo ("<br><div align='right' class='ts'><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;<br>");
	echo("$strAlbum: <b>".stripslashes($row_album[aname])."</b>&nbsp;&nbsp;</div>");
	echo ("<br><div align=center class=tn>$strPhoto $curpos $strOf $nr_albums $fullsize_val</div>");
      echo ("<br><div align=center class=tn><img src=".$dirpath."$Config_datapath/$username/$DIRR"."$row_orig[pname] $borderval $sizeval[3]><br>".stripslashes($pmsg)."<br></div>");
	echo ("<br><br><div align=center class=tn>$pagelinks</div><br><br>");
	echo($csr->LangConvert($strShowAbuse, $Config_abuse_link));
	echo("<br><br>");
                 
$usr->FooterOut();

?>