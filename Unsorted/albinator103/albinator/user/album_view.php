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
   
 	    closeDB();
          exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/alist.gif>&nbsp;</div>");
       $errMsg = "<b>$strAlbumCrErr6, <a href=index.php>$strCreate</a>...</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->Footer();

	 mysql_free_result( $result );

	 closeDB();
	 exit;
      }

# show

		if($nr > 1)
		$s = $strPuralS;

     	      $usr->Header($Config_SiteTitle ." :: $strMenusMyAlbums");
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/alist.gif>&nbsp;</div>");

?>

<br>
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center">
  <tr> 
    <td class=tn><font color="#666666"> 
      <?php echo $strAlbumPrivateNotice ?></font></td>
  </tr>
</table>
<br>
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center">
  <tr> 
    <td width=70% class=ts>
	<a href="tell.php?aid=all"><?php echo("$strTellCmt1"); ?></a> :: <a href=#view><?php echo("$strTellCmt2"); ?></a>
    </td>
    <td width=30% class=ts>
	<div align="right"><?php echo ($csr->LangConvert($strIndexAddpic.$s, $nr)); ?></div>
    </td>
  </tr>
</table>
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#aaaaaa">
  <tr bgcolor="#dddddd"> 
    <td class=tn>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td class=tn><b><?php echo("$strAlbumCrErr15"); ?></b></td>
        </tr>
        <tr>
          <td class=ts><?php echo $Config_mainurl ?>/showlist.php?uuid=<?php echo ("$uid"); ?>&dowhat=user</td>
        </tr>
        <tr>
          <td>
            <div align="right" class=ts>[<a href=album_edit.php><?php echo("$strChange"); ?></a>] [<a href="<?php echo $Config_mainurl ?>/showlist.php?uuid=<?php echo $uid ?>&dowhat=user"><?php echo $strView ?></a>] [<a href="tell.php?aid=all"><?php echo ("$strMenusTell"); ?></a>]</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

<?php
	while($row = mysql_fetch_array( $result ))
	{
	if($i == 1) { $rowcolor = "#dddddd"; $i = 0; }
	else { $rowcolor = "#eeeeee"; $i = 1; }

	$nr_pics = $row[pused];		
?>

  <tr bgcolor="<?php echo $rowcolor ?>"> 
    <td class=tn>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td class=tn><b><?php echo (stripslashes($row[aname])); ?></b><?php if($row[private]) echo (" :: $strPrivate "); ?> :: <?php echo("$nr_pics  $strPhoto"); if($nr_pics != 1) echo("$strPuralS"); ?></td>
        </tr>
        <tr>
          <td class=ts><?php echo $Config_mainurl ?>/showalbum.php?aid=<?php echo ("$row[aid]&uuid=$uid"); ?></td>
        </tr>
        <tr>
          <td>
            <div align="right" class=ts>[<a href=album_edit.php><?php echo("$strMake $strChange$strPuralS"); ?></a>] [<a href="<?php echo $Config_mainurl ?>/showalbum.php?aid=<?php echo ("$row[aid]&uuid=$uid"); ?>"><?php echo $strView ?></a>] [<a href="tell.php?aid=<?php echo $row[aid] ?>"><?php echo ("$strMenusTell"); ?></a>]</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

<?php
	}

	if($i == 1) { $rowcolor = "#dddddd"; $i = 0; }
	else { $rowcolor = "#eeeeee"; $i = 1; }

?>

  <tr bgcolor="<?php echo $rowcolor ?>"> 
    <td class=tn>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td class=tn><b><?php echo($csr->LangConvert($strTellCmt3, $Config_sitename)); ?></b></td>
        </tr>
        <tr>
          <td class=ts><?php echo $Config_mainurl ?></td>
        </tr>
        <tr>
          <td>
            <div align="right" class=ts>[<a href="tell.php?aid=site"><?php echo("$strMenusTell") ?></a>]</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>


<?php

	echo("</table><p>&nbsp;</p>");

?>

<?php

            $usr->Footer();
		exit;
?>
