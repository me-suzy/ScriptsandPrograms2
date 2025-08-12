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

	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if(preg_match("/M/", $row_user[prefs]))
	$privlevel = "1";
	else
	$privlevel = "0";

	if($privlevel != "1")
	{
       $usr->Header($Config_SiteTitle .' :: '.$strMenusManipulate);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/manipulate.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strManipulateNoAccess, <a href=\"feedback.php\">$strMailUs</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
	 echo("<BR>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}

       $usr->Header($Config_SiteTitle .' :: '.$strMenusManipulate);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/manipulate.gif>&nbsp;</div><br>");


	if(!$aid && !$pid)
	{
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=index.php>$strCreate</a></b>\n<br>";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }
	mysql_free_result( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
?>
<br><div align=center>
<?php echo ($csr->LangConvert($strSelectAlbum, "$strAlbumCrErr21")); ?>:
</div><br><br>
<form action="manipulate.php" method="post">
<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo("$strAlbumCrErr16"); ?>
        <select name="aid">
<?php
		while($row = mysql_fetch_array( $result_user ))
		{
			echo("<option value=$row[aid]>".stripslashes($row[aname])."</option>\n");
		}
?>
        </select>
<input type=submit name=submit value="<?php echo("$strNext"); ?> &gt;" class="butfieldc">
      </div>
    </td>
  </tr>
</table>
</form>	

<p>&nbsp;</p>
<?php
	}

	else if(!$pid && $aid)
	{

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=manipulate.php>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	$rs = new PagedResultSet("SELECT * FROM $tbl_pictures WHERE aid = '$aid' ORDER BY pindex, pname",$Config_maxshow);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("aid=$aid");

	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr17, <a href=upload.php>$strAdd</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	echo("<div class=tn align=center><br>\n$strSelectPhoteManipulate<br><span class='ts'>$nav</span></div><br>\n");
	echo("<div class=tn align=center>\n<table align=center width=600 cellpadding=4 cellspacing=4>\n");

	$result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=1";
	else
	$borderval = "border=0";

	$i = -1;
      $total = 0;

		while($row = $rs->fetchArray())
		{
	 	 $total++;
		 if($i == 3 || $i == -1)
		 { $i = 0; echo("\n<tr>"); $messagebar = "\n<tr>\n"; }
		 else
		 { $i++; }
		
		 $picurl = $dirpath."$Config_datapath/$uid/tb_$row[pname]";
		 error_reporting(0);
   	       $size = GetImageSize ("$picurl");
		 error_reporting(E_ERROR | E_WARNING);

		 $width = $size[0];
		 $height = $size[1];

  		 echo("\n<td align=center valign=bottom><a href=\"manipulate.php?aid=$aid&pid=$row[pid]\"><img src=\"$picurl\" $borderval width=$width height=$height></a></td>");
		 $messagebar .= "\n<td class=ts align=center valign=bottom><a href=\"manipulate.php?aid=$aid&pid=$row[pid]\">$strEdit</a></td>";

		 if($i == 3)
		 echo("\n</tr>$messagebar\n</tr><tr><td colspan=4 height=4>&nbsp;</td></tr>\n");
		}

	if($total < 4)
	echo("\n</tr>$messagebar\n</tr>");

	else if($total%4 != 0)
	{ 
	  $i++;
	  if($i%2 == 0)
	  echo("\n<td colspan=2>&nbsp;</td>\n</tr>$messagebar<td colspan=2>&nbsp;</td>\n</tr>");
	  else if($i%3 == 0)
	  echo("\n<td>&nbsp;</td>\n</tr>$messagebar\n<td>&nbsp;</td>\n</tr>");
	  else
	  echo("\n<td colspan=3>&nbsp;</td>\n</tr>$messagebar\n<td colspan=3>&nbsp;</td>\n</tr>");
      }

	echo("\n\n</table>\n\n</div>\n");

?>

<br>
<div align=center><?php echo ("<span class='ts'>$nav</span>"); ?><p><a href=ecards.php>&lt;&lt; <?php echo $strBackAlbumSelect ?></a></div>

<p>&nbsp;</p>

<?php
	}

else if($aid && $pid)
{
	if(!$aid)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=manipulate.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	if(!$pid)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=manipulate.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr19, <a href=manipulate.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
	$row = mysql_fetch_array( $result );
	$pname = $row[pname];
	
	srand((double)microtime()*100);
	$randnum = rand();

	error_reporting(0);
  	$size = GetImageSize ("$dirpath"."$Config_datapath/$uid/$row[pname]");
	error_reporting(E_ERROR | E_WARNING);

	if($row[i_used])
	{
	$fullsize = 1;
	$DIRR = "full_";
	}
?>

<p>&nbsp;</p>
<div class=ts align=center>
<?php echo $strManipulateTerms ?>
<br><br>
<img src=<?php echo "$dirpath"."$Config_datapath/$uid/$DIRR"."$pname?$randnum"; ?>>
<br><br>
<?php echo $strManipulateNotice ?>
</div><br><br>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
<table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
  <tr bgcolor="#DDDDDD"> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr bgcolor="#CCCCCC"> 
          <td> 
            <div align="right"><span class="tn"><span class="tn"><b><?php echo $strManipulateOpt1 ?> </b></span><b>&nbsp;</b></span></div>
          </td>
        </tr>
        <tr> 
          <td>
              <div align="right"><span class="tn"> <font color="#333333"><?php echo $strType ?> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                </font> 
                <select name="param_a">
                  <option value="Uniform" selected>Uniform</option>
                  <option value="Gaussian">Gaussian</option>
                  <option value="Multiplicative">Multiplicative</option>
                  <option value="Impulse">Impulse</option>
                  <option value="Laplacian">Laplacian</option>
                </select>
                <input type=hidden name=dowhat value=addnoise>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </span></div>
			</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt2 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><?php echo $strManipulateOpt2 ?></font> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <select name="param_a">
                  <option value=".5" selected>very less</option>
                  <option value="1">less</option>
                  <option value="2">more</option>
                  <option value="3">a lot</option>
                </select>
                <input type=hidden name=dowhat value=blur>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt3 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><?php echo $strManipulateOpt3 ?></font> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <select name="param_a">
                  <option value="1" selected>very less</option>
                  <option value="2">less</option>
                  <option value="3">more</option>
                  <option value="4">a lot</option>
                  <option value="5">even more</option>
                </select>
                <input type=hidden name=dowhat value=sharpen>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt4 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><?php echo $strManipulateOpt4 ?></font> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <select name="param_a">
                  <option value=".5" selected>very less</option>
                  <option value="1">less</option>
                  <option value="2">more</option>
                  <option value="3">a lot</option>
                </select>
                <input type=hidden name=dowhat value=reducenoise>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt5 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <font color="#333333">Azimuth 
                <select name="param_a">
                  <option value="45" selected>lightest</option>
                  <option value="35">even lighter</option>
                  <option value="25">lighter</option>
                  <option value="15">dark</option>
                  <option value="10">darker</option>
                  <option value="5">darkest</option>
                </select>
                Elevate</font> 
                <select name="param_b">
                  <option value="45" selected>lightest</option>
                  <option value="35">even lighter</option>
                  <option value="15">dark</option>
                  <option value="10">darker</option>
                  <option value="5">darkest</option>
                </select>
                <input type=hidden name=dowhat value=shade>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt6 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"> <font color="#333333">
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                Size 
                <select name="param_a">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
                Color 
                <select name="param_b">
                  <option value="#ffffff" selected>white</option>
                  <option value="#000000">black</option>
                  <option value="#000080">blue</option>
                  <option value="#008000">green</option>
                  <option value="#C00000">red</option>
                  <option value="#ffff00">yellow</option>
                  <option value="#d4d4d4">gray</option>
                  <option value="#a52a2a">brown</option>
                  <option value="#8a2be2">blueviolet</option>
                  <option value="#5f9ea0">cadetblue</option>
                  <option value="#d2691e">chocolate</option>
                  <option value="#00ffff">cyan</option>
                  <option value="#ffd700">gold</option>
                  <option value="#adff2f">greenyellow</option>
                  <option value="#f0e68c">khaki</option>
                  <option value="#e6e6fa">lavender</option>
                  <option value="#fffacd">lemonchiffon</option>
                  <option value="#add8e6">lightblue</option>
                  <option value="lightslategray">lightslategray</option>
                  <option value="#ff00ff">magenta</option>
                  <option value="#800000">maroon</option>
                  <option value="#00ff00">lime</option>
                  <option value="#ffe4e1">mistyrose</option>
                  <option value="#000080">navy</option>
                  <option value="#da70d6">orchid</option>
                  <option value="#ff4500">orange</option>
                  <option value="#800080">purple</option>
                  <option value="#f5deb3">wheat</option>
                  <option value="#800080">violet</option>
                  <option value="#4682b4">steelblue</option>
                  <option value="#d2b48c">tan</option>
                </select>
                <input type=hidden name=dowhat value=border>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </font></span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt7 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><?php echo $strType ?>
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <select name="param_a">
                  <option value="True" selected>Enhance</option>
                  <option value="False">Reduce</option>
                </select>
                <input type=hidden name=dowhat value=contrast>
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </font></span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<script>
<!--
function GammaCheck() {
var red, green, blue;

red = eval(document.gamma.param_a.value);
green = eval(document.gamma.param_b.value);
blue = eval(document.gamma.param_c.value);

if(red > 5)
{ alert('Red color must be < 5'); 
  document.gamma.param_a.focus()
  return false }

if(red < 0)
{ alert('Red color must be > 0'); 
  document.gamma.param_a.focus()
  return false }

if(green > 5)
{ alert('Green color must < 5'); 
  document.gamma.param_b.focus()
  return false }

if(green < 0)
{ alert('Green color must be > 0'); 
  document.gamma.param_b.focus()
  return false }

if(blue > 5)
{ alert('Blue color must < 5'); 
  document.gamma.param_c.focus()
  return false }

if(blue < 0)
{ alert('Blue color must be > 0'); 
  document.gamma.param_c.focus()
  return false }
}
//-->
</script>
<form name="gamma" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td height="2"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td>
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt8 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><span class="tn"> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <?php echo $strRed ?> 
                <input type="text" name="param_a" size=4 maxlength=3>
                <?php echo $strGreen ?> 
                <input type="text" name="param_b" size=4 maxlength=3>
                <?php echo $strBlue ?> 
                <input type="text" name="param_c" size=4 maxlength=3>
                <br>
                <?php echo $strManipulateOpt8Notice ?> 
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=dowhat value=gamma>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                </span> 
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb onclick="return GammaCheck()">
                </font></span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<script>
<!--
function BrightCheck() {
var brt, sat, hue;

brt = eval(document.bright.param_a.value);
sat = eval(document.bright.param_b.value);
hue = eval(document.bright.param_c.value);

if(brt < -100 || brt > 100)
{ alert('<?php echo $strManipulateError1 ?>'); 
  document.bright.param_a.focus()
  return false }

if(sat < -100 || sat > 100)
{ alert('<?php echo $strManipulateError2 ?>'); 
  document.bright.param_b.focus()
  return false }

if(hue < -100 || hue > 100)
{ alert('<?php echo $strManipulateError3 ?>'); 
  document.bright.param_c.focus()
  return false }
}
//-->
</script>
<form name="bright" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td height="2"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt9 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td> 
              <div align="right"> <span class="tn"><font color="#333333"><span class="tn"> 
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                Brightness 
                <input type="text" name="param_a" size=4 maxlength=3>
                Saturation 
                <input type="text" name="param_b" size=4 maxlength=3>
                Hue 
                <input type="text" name="param_c" size=4 maxlength=3>
                <br>
                <?php echo $strManipulateOpt9Notice ?> 
                <input type=hidden name=callwhat value=manipulate>
                <input type=hidden name=dowhat value=brightness>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                </span> 
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb onclick="return BrightCheck()">
                </font></span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<form name="manupilate" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi" method="post" onsubmit="ManWindow()" target="ManWindow">
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#000000">
    <tr bgcolor="#DDDDDD"> 
      <td height="2"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr bgcolor="#CCCCCC"> 
            <td> 
              <div align="right"><span class="tn"><b><?php echo $strManipulateOpt10 ?>&nbsp;</b></span></div>
            </td>
          </tr>
          <tr> 
            <td height="2"> 
              <div align="right"> <span class="tn"><font color="#333333"><span class="tn">
                <input type="hidden" name="fn" value="<?php echo $pname ?>">
                <input type=hidden name=callwhat value=manipulate>
                <select name="dowhat">
                  <option value="flip" selected>Flip (upside-down)</option>
                  <option value="flop">Flop (Mirror)</option>
                  <option value="grayscale">Grayscale (in gray shades only)</option>
                  <option value="despeckle">Despecke (reduce speckles)</option>
                  <option value="enhance">Enhance (enhance a noisy image)</option>
                  <option value="edge">Enhance Edges</option>
                  <option value="emboss">Emboss</option>
                  <option value="raise">3D effect (buttonize)</option>
                  <option value="trim">Trim (remove edges of bgcolor)</option>
                </select>
                <input type=hidden name=uid value=<?php echo $uid ?>>
                </span> 
                <input type="submit" name="submit" value="<?php echo $strApply ?>" class=butfieldb>
                </font></span></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>

<?php
}

$usr->Footer();
exit;

?>