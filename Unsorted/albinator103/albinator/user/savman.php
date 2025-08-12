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

		 error_reporting(0);
		 $size = GetImageSize ("$dirpath"."$Config_datapath/$uid/$fn");
		 error_reporting(E_ERROR | E_WARNING);

		 if($size[0] > $size[1])
		 { $wt = $Config_tbwidth_short;
		   $ht = $Config_tbheight_short; }
		 else
		 { $wt = $Config_tbwidth_long;
		   $ht = $Config_tbheight_long; }

		 if(file_exists("{$dirpath}$Config_datapath/$uid/full_$fn"))
		 {
		 error_reporting(0);
		 $size = GetImageSize ("$dirpath"."$Config_datapath/$uid/full_$fn");
		 error_reporting(E_ERROR | E_WARNING);
		 $wtf = $size[0];
		 $htf = $size[1];
		 $fullresize = "<input type=hidden name=full value=1>
				    <input type=hidden name=wtf value=\"$wtf\">
				    <input type=hidden name=htf value=\"$htf\">";
		 }
?>

<html>
<head>
<title><?php echo ($Config_sitetile." $strManipulateSave"); ?></title>
<?php echo $headcontent ?>
</head>
<body background=<?php echo $dirpath.$Config_imgdir ?>/design/background.gif bgcolor=#ffffff>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%" class=tn>
  <tr>
    <td align=center class=tn>
	<font size=5>Apply changes?</font><br><br><br>
      <table width="98%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr> 
          <td height="2"> 
            <div align="center" class=tn>
		<?php echo $strManipluateSaveLine ?>
              <br><br>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="55%" height="6"> 
                    <form name="form1" method="post" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi">
                      <div align="right" class=tn> 
                        <input type="checkbox" name="thumb" value="1">
                        <?php echo("$strUpdate $strThumbnail"); ?>
                        <input type=hidden name=callwhat value="savman">
                        <input type=hidden name=wt value=<?php echo $wt ?>>
                        <input type=hidden name=ht value=<?php echo $ht ?>>
                        <input type=hidden name=fn value=<?php echo $fn ?>>
                        <input type=hidden name=uid value=<?php echo $uid ?>>
                        <input type=hidden name=dowhat value=save>
				<?php echo $fullresize ?>
                        <input type="submit" name="Submit" value="<?php echo $strYes ?>">&nbsp;&nbsp;
                      </div>
                    </form>
                  </td>
                  <td width="45%" height="6"> 
                    <form name="form2" method="post" action="<?php echo $dirpath.$Config_cgidir ?>/albinator.cgi">
                      <input type=hidden name=fn value=<?php echo $fn ?>>
					  <input type=hidden name=uid value=<?php echo $uid ?>>
	                          <input type=hidden name=callwhat value="savman">
					  <input type=hidden name=dowhat value=cancel>
                      <input type="submit" name="submit" value="<?php echo $strNo ?>">
                    </form>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>