<?php
	$dirpath = "$Config_rootdir"."../";
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

	if($send_url)
	{ 
	   $sendurl = "$send_url.php";
	   $i=1;

	   $parray = split ('[|]', $params);

	   foreach ($parray as $pairval) 
	   {	   
	    list ($pkey, $pvalue) = split ('[-]', $pairval);

	    if($i > 1)
	    $sendurl .= "&";
	    else
	    $sendurl .= "?";

	    $sendurl .= "$pkey=$pvalue";
	    $i++;
	   }
      }

	if(!$aid)
      {
	    $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
          echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbumCrErr1, <a href=\"$sendurl\">$strRetry</a></b><br><br>\n";
	    $usr->errMessage( $errMsg, $strError );
	    $usr->Footer();
   
          exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid' && uid = '$uid'" );
	$nr = mysql_num_rows ( $result );
	mysql_free_result ( $result );

	if(!$nr)
      {
	    $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
          echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbumCrErr4, <a href=$sendurl>$strRetry</a>...</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    $usr->Footer();
   
          exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid'" );
	$nr = mysql_num_rows ( $result );
	mysql_free_result ( $result );

	if(!$nr)
      {
	     if($done == 1)
	     {
		$sendurl = "album_edit.php";
     	      $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing", '1', $sendurl);
            echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAllImageNotice, $strRedirecting...</b><br>else <a href=$sendurl>$strClickhere</a>\n";
 	      $usr->errMessage( $errMsg, $strNote, 'tick', '70' );
	     }

	    else
	    {
	     $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
           echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	     $errMsg = "<b>$strAlbumCrErr17, <a href=\"upload.php\">$strAdd</a></b><br><a href=$sendurl>$strBack</a><br>\n";
	     $usr->errMessage( $errMsg, $strError, 'error', '60' );
	    }
	
            $usr->Footer();
		exit;
      }

	$result = queryDB( "SELECT aname, private FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$row_orig = mysql_fetch_array ( $result );
	mysql_free_result ( $result );

	$rs = new PagedResultSet("SELECT * FROM $tbl_pictures WHERE aid = '$aid' ORDER BY pindex, pname",20);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("aid=$aid");

	if($nr < 9)
	{ $index_size = "1"; }
	else if($nr > 9 && $nr < 99)
	{ $index_size = "2"; }
	else
	{ $index_size = "3"; }
	
	$usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");

?>

<script language="Javascript">
<!--
function SetChecked(val) 
{
	dml=document.picedit;
	len = dml.elements.length;

	var i=0;

	for( i=0 ; i<len ; i++) 
	{
		dml.elements[i].checked=val;
	}
}
//-->
</script>

<?php

	echo("<div align='center' class='ts'>$nav (<a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a>)</div>");

?>

<br>
<div align=center>
<form method=post action=pic_edit_chg.php name=picedit>

<?php
if($nr > 10)
{
?>

<input type="button" name="back" value="<?php echo($strBack); ?>" onclick="document.location.href('<?php if($sendurl) { echo $sendurl; } else { echo ("album_edit.php"); } ?>')" class="butfieldb">
&nbsp;
<input type="submit" name="move" value="<?php echo(strtolower($strMove)) ?>" class="butfieldc">
<input type="submit" name="copy" value="<?php echo(strtolower($strCopy)) ?>" class="butfieldc">
<input type="submit" name="delete" value="<?php echo(strtolower($strDelete)) ?>" class="butfieldc">
&nbsp;
<input type="reset" name="reset" value="<?php echo(strtolower($strClear)) ?>" class="butfieldb">
<input type="submit" name="change" value="<?php echo("$strChange") ?>" class="butfieldb">
<br><br>

<?php
}
?>

  <table width="85%" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr bgcolor=#000000> 
	<td class=tn width=50%>
        <div align="left">
	   <font color='#ffffff'>
		<?php echo ("<b>$row_orig[aname]</b>");
		   if($row_orig[private] == 1)
		   echo (" :: $strPrivate");
		?>
	   </font>
	  </div>
	</td>
      <td class=tn width=50%> 
        <div align="right">
	   <font color=#ffffff>
		<?php if($nr == 1) $s = ""; else $s = $strPuralS; echo ($csr->LangConvert($strTotalInfo, $nr, $strPhoto.$s)); ?>
	   </font>
	  </div>
      </td>
    </tr>
   </table>

<!-- bodyof editing -->
<?php
	$i = 0;
	while($row = $rs->fetchArray())
	{
	$i++;
	$big_image_size = $row[i_used] + $row[o_used];
	$tb_image_size  = $row[t_used];

	$big_image_size = $csr->calcSpaceVal( $big_image_size );
	$tb_image_size = $csr->calcSpaceVal( $tb_image_size );

	$size_show = "<span class=ts>$strPhoto $big_image_size";
	if(preg_match("/A/i", $Config_spaceScheme))
	$size_show .= " :: $strThumbnail $tb_image_size</span>";	
?>


<table width="85%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#aaaaaa">
  <tr bgcolor="#dddddd"> 
    <td width="<?php echo $Config_tbwidth_short ?>" align='center'><a href="<?php echo $dirpath ?>showpic.php?aid=<?php echo ($row[aid]); ?>&pid=<?php echo ($row[pid]); ?>&uuid=<?php echo $uid ?>" class=nounderts><img src="<?php echo ("$dirpath"."$Config_datapath/$uid/tb_$row[pname]"); ?>"  alt="<?php echo $strView ?>" border=0></a></td>
    <td class=ts valign=top> 
      <div align="right"><?php echo $size_show ?><br><br>
        <?php echo $strCaption ?>: <input type="text" name="new_pmsg<?php echo $i ?>" value="<?php echo (stripslashes($row[pmsg])); ?>" maxlength=200 class=fieldsf size=30>&nbsp;&nbsp;
        <?php echo $strOrder ?>
        <input type="text" name="new_pindex<?php echo $i ?>" size="3" maxlength="<?php echo $index_size ?>" value="<?php echo ($row[pindex]); ?>" class=fieldsf>
        <input type="hidden" name="pid<?php echo $i ?>" value="<?php echo ($row[pid]); ?>">
	  <input type="checkbox" name="new_pcheck<?php echo $row[pid] ?>" value="1">
        <br><br>
        [<a href="ecards.php?aid=<?php echo ($row[aid]); ?>&pid=<?php echo ($row[pid]); ?>" class=nounderts><?php echo $strMenusEcards ?></a>] [<a href="manipulate.php?aid=<?php echo ($row[aid]); ?>&pid=<?php echo ($row[pid]); ?>" class=nounderts><?php echo $strMenusManipulate ?></a>] [<a href="<?php echo $dirpath ?>showpic.php?aid=<?php echo ($row[aid]); ?>&pid=<?php echo ($row[pid]); ?>&uuid=<?php echo $uid ?>" class=nounderts><?php echo $strView ?></a>]
      </div>
    </td>
  </tr>
</table>

<?php
	 }

?>
<input type="hidden" name="aid" value="<?php echo ($aid); ?>">
<input type="hidden" name="sf" value="<?php echo ($sf); ?>">

<input type="hidden" name="send_url" value="pic_edit.php">
<br>

<input type="button" name="back" value="<?php echo($strBack); ?>" onclick="document.location.href('<?php if($sendurl) { echo $sendurl; } else { echo ("album_edit.php"); } ?>')" class="butfieldb">
&nbsp;
<input type="submit" name="move" value="<?php echo(strtolower($strMove)) ?>" class="butfieldc">
<input type="submit" name="copy" value="<?php echo(strtolower($strCopy)) ?>" class="butfieldc">
<input type="submit" name="delete" value="<?php echo(strtolower($strDelete)) ?>" class="butfieldc">
&nbsp;
<input type="reset" name="reset" value="<?php echo(strtolower($strClear)) ?>" class="butfieldb">
<input type="submit" name="change" value="<?php echo("$strChange") ?>" class="butfieldb">

</form>

</div>
<br>

<?php
	echo("<div align='center' class='ts'>$nav (<a href=javascript:SetChecked(1)>Check All</a> ~ <a href=javascript:SetChecked(0)>UnCheck All</a>)</div>");

closeDB();
$usr->Footer();

?>