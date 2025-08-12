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

	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uuid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strUser $strProfile");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	 echo("<BR>");
       $errMsg = "<b>$strAlbumCrErr24</b>\n";
       $usr->errMessage( $errMsg, $strSorry, 'error', '65' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }
	mysql_free_result( $result );


 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );
	$nr = mysql_num_rows( $result );

 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uuid'" );
	$row_user = mysql_fetch_array( $result_user );

      $parray = split ('[|]', $row_user[profile]);

      $usr->$ShowHeader($Config_SiteTitle ." :: $strUser $strProfile");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	echo ("<P>&nbsp;</P>");
	echo ("<table width=80% cellspacing=1 cellpadding=0 border=0 align=center bgcolor=#CCCCCC><tr><td>\n");
	echo ("<table width=100% cellspacing=0 cellpadding=4 border=0 align=center>\n");
?>

  <tr class="tn" bgcolor="#DDDDDD"> 
    <td colspan=2>
	<table width=98% cellspacing=0 cellpadding=4 border=0 align=center>
	<tr>
	<td width=110>

<?php

	foreach ($parray as $pairval_first) 
	{
		list ($cphoto, $pfile) = split ('[*]', $pairval_first);

		if($cphoto == "0")
		break;
	}

	if($cphoto == "0" && $pfile != "0")
	{
	  $picurl = "$dirpath"."$Config_datapath/$uuid/$pfile";
	  error_reporting(0);
   	  $size = GetImageSize ("$picurl");
	  error_reporting(E_ERROR | E_WARNING);

  	  echo("\n<img src=\"$picurl\" $size[3]>");
	}

	else
	{
	  $picurl = "$dirpath"."$Config_imgdir"."/noprofile.jpg";
	  error_reporting(0);
   	  $size = GetImageSize ("$picurl");
	  error_reporting(E_ERROR | E_WARNING);

  	  echo("\n<img src=\"$picurl\" border=1 $size[3]>");
	}

?>
    </td>
    <td>
<?php
    echo("<div align=left class=tn><b>&nbsp;<font size=3 color=#333333>$row_user[uname]");
  
    if(!preg_match("/E/", $row_user[prefs]))
    echo("<br>&nbsp;$row_user[email]");

    if(!$row_user[country])
    $row_user[country] = "";

    echo("<br>&nbsp;$row_user[country]</font></b></div>");
?>	
    </td>
  </tr>
</table>
</td>
</tr>
<tr class="tn" bgcolor="#EEEEEE"> 
   <td width=40%><b><font color=#666666><?php echo $strProfileOpt1 ?></font></b></td>
<?php

 	$result_alb = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid='$uuid'" );
	$nr_alb = mysql_num_rows( $result_alb );

?>
   <td>

<?php 
if($nr_alb > 1)
$s = $strPuralS; 

if($nr_alb > 0)
echo "$nr_alb $strAlbum$s (<a href=".$dirpath."showlist.php?uuid=$uuid&dowhat=user>$strList</a>)"; 
else
echo "0 $strAlbum$strPuralS";
?>

  </td>
</tr>
<?php
$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#eeeeee"; }
	else
	{ $i++; $rowcolor = "#dddddd"; }

	foreach ($parray as $pairval) 
	{
		list ($pfid, $pvalue) = split ('[*]', $pairval);

		if($pfid == $row[fid])
		{ $found = 1; break; }
		else
		$found = 0;
	}

?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td width=40%><font color=#666666><b><?php echo $row[tname] ?></b></font></td>
    <td>
<?php

	if($found == 1)
	{
		if($row[type] == "checkbox")
		{
			$pvalue = ereg_replace ("\+", ", ", $pvalue);	
		}

		echo ($pvalue);
	}

	else
	{
		echo(" ");
	}
?>
    </td>
   </tr>

<?php

}

?>

</table>
</td>
</tr>
</table>
<p>&nbsp;</p>

<?php

$usr->$ShowFooter(); 

?>