<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$csr = new ComFunc();
	$ucook = new UserCookie();
	$albumcook = new Cookie();
	$letgo = "0";

      if ( !$ucook->LoggedIn() )
	{ $ShowHeader = "HeaderOut"; $ShowFooter = "FooterOut"; }
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

	if(!$aid || !$uuid || !$pid)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

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
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uuid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

   $row = mysql_fetch_array( $result );

   if($row[private] == 1 && !$albumcook->checkCookie("alid"))
   { $albumcook->delete("alid"); }

   if($row[private] == 1 && $albumcook->get("alid") != "$aid")
   {
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strReqPass8 <a href=\"showalbum.php?aid=$aid&uuid=$uuid&sendurl=view&ppid=$pid\">$strReqPass7</a></b>\n";
       $usr->errMessage( $errMsg, $strNote );
   	 $usr->$ShowFooter();	
	 exit;
   }

   else
   {
	$nr = $row[pused];
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError4</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid='$pid' && aid='$aid'" );
	$row_orig = mysql_fetch_array( $result );

	$result = queryDB( "SELECT aname FROM $tbl_albumlist WHERE aid='$row_orig[aid]'" );
	$row_album = mysql_fetch_array( $result );
   }

	 if($row_orig[pmsg] && $row_orig[pmsg] != '0')
	 { $pmsg = "<br>&quot; <i>$row_orig[pmsg]</i> &quot;"; }

	 $result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$uuid'" );
 	 $row_user = mysql_fetch_array( $result_user );

	 if(file_exists($dirpath."$Config_datapath/$uuid/full_".$row_orig[pname]) || $row_orig[i_used])
	 { 
	  $fullsize = 1;
	  $DIRR = "full_";
	 }
	 else
	 $fullsize = 0;

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

	 $listlinks = "<a href=showlist.php?uuid=$uuid&dowhat=user>$strShow1</a> :: <a href=showalbum.php?uuid=$uuid&aid=$aid>$strAlbum $strIndexHome</a>";

	 if($nr_albums > 1 && $nr_albums > $curpos)
	 { $pagelinks = "<a href=showpic.php?aid=$aid&uuid=$uuid&pid=$npid>$strNext &gt;&gt;</a>"; 
 	   $pagelinks_icon = "<a href=showpic.php?aid=$aid&uuid=$uuid&pid=$npid><img src=\"".$dirpath.$Config_imgdir."/design/icon_front.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strNext $strPhoto\"></a>";
		$sepb= "::";
       }

	   if($curpos > 1)
	   { 
	     if($pagelinks)
	     $sepa = "::";
	
     	     $prev = 1;

	     $pagelinks = "<a href=showpic.php?aid=$aid&uuid=$uuid&pid=$ppid>&lt;&lt; $strPrev</a> :: $listlinks $sepa $pagelinks";

	     if($pagelinks_icon)
	     $pagelinks_icon ="<a href=showpic.php?aid=$aid&uuid=$uuid&pid=$ppid><img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0 alt=\"$strPrev $strPhoto\"></a>&nbsp;$pagelinks_icon"; 
	     else
	     $pagelinks_icon ="<a href=showpic.php?aid=$aid&uuid=$uuid&pid=$ppid><img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0 alt=\"$strPrev $strPhoto\"></a>&nbsp;<img src=\"".$dirpath.$Config_imgdir."/design/icon_front.gif\" width=\"16\" height=\"16\" border=0>"; 

}
	   else
	   { $pagelinks_icon = "<img src=\"".$dirpath.$Config_imgdir."/design/icon_back.gif\" width=\"16\" height=\"16\" border=0>&nbsp;$pagelinks_icon"; }


       error_reporting(0);
	 $sizeval = GetImageSize($dirpath."$Config_datapath/$uuid/$DIRR".$row_orig[pname]);
 	 error_reporting(E_ERROR | E_WARNING);

	 if($prev != 1)
	 { $pagelinks = "$listlinks $sepb $pagelinks"; }

	$icon_links="<a href='showlist.php?uuid=$uuid&dowhat=user'><img src=\"{$dirpath}$Config_imgdir/design/icon_list.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strShow1\"></a>&nbsp;<a href='showalbum.php?uuid=$uuid&aid=$aid'><img src=\"{$dirpath}$Config_imgdir/design/icon_ahome.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strAlbum $strIndexHome\"></a>&nbsp;$pagelinks_icon&nbsp;";
      $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum", '','','','',$icon_links);

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=\"1\"";
	else
	$borderval = "border=\"0\"";

	if($fullsize == 1)
	$fullsize_val = "[<a href=\"javascript:Popup('showpicfull.php?uuid=$uuid&pid=$pid&aid=$aid', 'fullSize', 700, 550)\"  onmouseover=\"self.status='$strShowError5';return true;\" onmouseout=\"self.status=''\">$strShowError6</a>]";

      echo ("<br><div align='right' class='ts'><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;<br>");
	echo("$strAlbum: <b>".stripslashes($row_album[aname])."</b>&nbsp;&nbsp;</div>");
	echo ("<br><div align=center class=tn>$strPhoto $curpos $strOf $nr_albums $fullsize_val</div>");
      echo ("<br><div align=center class=tn><img src=".$dirpath."$Config_datapath/$uuid/$DIRR"."$row_orig[pname] $borderval $sizeval[3]><br>".stripslashes($pmsg)."<br></div>");
	echo ("<br><br><div align=center class=tn>$pagelinks</div><br><br>");

	echo($csr->LangConvert($strShowAbuse, $Config_abuse_link));
	echo("<br><br>");
                
$usr->$ShowFooter();

?>