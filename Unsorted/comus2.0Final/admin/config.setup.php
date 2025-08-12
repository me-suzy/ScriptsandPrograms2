<?

if (!file_exists($DOCUMENT_ROOT . "/includes/config.inc.php"))
{
    die ("Can not locate config.inc.php. Please make sure that you have it withing your includes directory and it is chmod to 766.");
}else{
    if (!is_writeable($DOCUMENT_ROOT . "/includes/config.inc.php")){
       die ("Unable to write to config.inc.php. Please make sure that you chmod it to 766.");
    }
}
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
	include("../includes/language/lang-english.php");
}

include($DOCUMENT_ROOT . "/includes/header.php");

if(isset($change)){

$setup_ar[] = "<?\n";
$setup_ar[] = "\$adj_var = '$arch_days';\n";
$setup_ar[] = "\$dbhost = '$dbhost';\n";
$setup_ar[] = "\$db = '$db';\n";
$setup_ar[] = "\$dbuser = '$dbuser';\n";
$setup_ar[] = "\$dbpasswd = '$dbpasswd';\n";
$setup_ar[] = "\$tgpemail = '$tgpemail';\n";
$setup_ar[] = "\$sitename = '$sitename';\n";
$setup_ar[] = "\$recip = '$recip';\n";
$setup_ar[] = "\$siteowner = '$siteowner';\n";
$setup_ar[] = "\$bgcolor = '$bgcolor';\n";
$setup_ar[] = "\$adjust_time = strtotime(\"now -$arch_days days\");\n";
$setup_ar[] = "\$hmail = '$hmail';\n";
$setup_ar[] = "\$useblacklist = '$useblacklist';\n";
$setup_ar[] = "\$useconfirm = '$useconfirm';\n";
$setup_ar[] = "\$useemail = '$useemail';\n";
$setup_ar[] = "\$reqrecip = '$reqrecip';\n";
$setup_ar[] = "\$popcheck = '$popcheck';\n";
$setup_ar[] = "\$javacheck = '$javacheck';\n";
$setup_ar[] = "\$flcheck = '$flcheck';\n";
$setup_ar[] = "\$iframecheck = '$iframecheck';\n";
$setup_ar[] = "\$objectcheck = '$objectcheck';\n";
$setup_ar[] = "\$usepreferred = '$usepreferred';\n";
$setup_ar[] = "\$usedupe = '$usedupe';\n";
$setup_ar[] = "\$badwordcheck = '$badwordcheck';\n";
$setup_ar[] = "\$badword = '$badword';\n";
$setup_ar[] = "\$galinmain = $galinmain;\n";
$setup_ar[] = "\$descleng = $descleng;\n";
$setup_ar[] = "\$daynormalgal = $daynormalgal;\n";
$setup_ar[] = "\$daypartnergal = $daypartnergal;\n";
$setup_ar[] = "\$dateform = '$dateform';\n";
$setup_ar[] = "\$dnow = date(\$dateform);\n";
$setup_ar[] = "\$then = date(\$dateform, \$adjust_time);\n";
$setup_ar[] = "\$cjultra = '$cjultra';\n";
$setup_ar[] = "\$cjstring = '$cjstring';\n";
$setup_ar[] = "\$cjstring2 = '$cjstring2';\n";
$setup_ar[] = "\$posting = '$posting';\n";
$setup_ar[] = "\$advgalcheck = '$advgalcheck';\n";
$setup_ar[] = "\$galminpic = '$galminpic';\n";
$setup_ar[] = "\$galmaxpic = '$galmaxpic';\n";
$setup_ar[] = "\$maxlink = '$maxlink';\n";
$setup_ar[] = "\$thumbcheck = '$thumbcheck';\n";
$setup_ar[] = "\$thumbw = '$thumbw';\n";
$setup_ar[] = "\$thumbh = '$thumbh';\n";
$setup_ar[] = "\$changedesc = '$changedesc';\n";
$setup_ar[] = "\$makelngbox = '$makelngbox';\n";
$setup_ar[] = "\$deflang = '$deflang';\n";
$setup_ar[] = "\$popup = 'window.open';\n";
$setup_ar[] = "\$java = '<script';\n";
$setup_ar[] = "\$flcode = 'changecolor(';\n";
$setup_ar[] = "\$iframecode = '<iframe';\n";
$setup_ar[] = "\$objectcode = '<object';\n";
$setup_ar[] = "\$conn = mysql_connect(\"\$dbhost\",\"\$dbuser\",\"\$dbpasswd\")
            or die(\"Unable to connect to SQL server!\");
            @mysql_select_db(\"\$db\")
            or die(\"Unable to select database!\");\n";

$setup_ar[] = "?>";

if ($fp = @ fopen($DOCUMENT_ROOT . "/includes/config.inc.php" , "w")) {
            $common = implode("", $setup_ar);
            fwrite($fp, $common);
            fclose($fp);
   }
   $message = GTG_SETUP_UPDATED;
}
?> 
<? include ($DOCUMENT_ROOT . "/includes/config.inc.php"); ?>

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

      <TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
        <? printabout(2); ?>
        <TR> 
          <TD ALIGN="CENTER" COLSPAN="2"> <A HREF="admin/index.php">
            <? echo GTGP_SET_RETURN; ?>
            </A> 
            <? echo "<BR><font color=red>$message</center>"; ?>
          </TD>
        </TR><form name="form1" method="post" action="<?$PHP_SELF?>">
        <TR>
          <TD ALIGN="LEFT" COLSPAN="2"><B>
            <? echo GTGP_ADMIN_MYSQL; ?>
            </B></TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="dbhost" value="<? echo $dbhost; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_MYSQL_HOST; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT">
            <input type="text" name="db" value="<? echo $db; ?>">
          </TD>
          <TD ALIGN="LEFT">
            <? echo GTGP_ADMIN_MYSQL_DB; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT">
            <input type="text" name="dbuser" value="<? echo $dbuser; ?>">
          </TD>
          <TD ALIGN="LEFT">
            <? echo GTGP_ADMIN_MYSQL_USER; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT">
            <input type="text" name="dbpasswd" value="<? echo $dbpasswd; ?>">
          </TD>
          <TD ALIGN="LEFT">
            <? echo GTGP_ADMIN_MYSQL_PASS; ?>
          </TD>
        </TR>
        <TR>
          <TD ALIGN="LEFT" COLSPAN="2"><B>
            <? echo GTGP_ADMIN_TGP; ?>
            </B></TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="tgpemail" value="<? echo $tgpemail; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_EMAIL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="sitename" value="<? echo $sitename; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_URL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="recip" value="<? echo $recip; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_RURL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="siteowner" value="<? echo $siteowner; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_OWNER; ?>
          </TD>
        </TR>
        <TR>
          <TD ALIGN="LEFT" COLSPAN="2"><B>
            <? echo GTGP_ADMIN_TGP; ?>
            </B></TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <?
	$content .= "<select name=\"deflang\">";
	$handle=opendir("../includes/language");
	while ($file = readdir($handle))
	{
		if (preg_match("/^lang\-(.+)\.php/", $file, $matches))
		{
			$langFound = $matches[1];
			$languageslist .= "$langFound ";
		}
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++)
	{
		if($languageslist[$i]!="")
		{
			$content .= "<option value=\"$languageslist[$i]\"";
			if($languageslist[$i]==$deflang) $content .= " selected";
			$content .= ">".ucfirst($languageslist[$i])."</option>\n";
		}
	}
	$content .= "</select>";
	echo $content;
?>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_LNG; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="bgcolor" value="<? echo $bgcolor; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_BGCOL; ?>
          </TD>
        </TR>
        <!--
<TR>
	<TD ALIGN="RIGHT" WIDTH="30%"><input type="text" name="arch_days" value="<? echo $adj_var; ?>"></TD>
	<TD ALIGN="LEFT"  WIDTH="70%"><? echo GTGP_ADMIN_TGP_GALNUM; ?></TD>
</TR>
-->
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="hmail">
              <option value="<? echo $hmail; ?>" selected> 
              <? echo tsl($hmail); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_HMAIL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="changedesc">
              <option value="<? echo $changedesc; ?>" selected> 
              <? echo tsl($changedesc); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_CHD; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="useblacklist">
              <option value="<? echo $useblacklist; ?>" selected> 
              <? echo tsl($useblacklist); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_BL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="useconfirm">
              <option value="<? echo $useconfirm; ?>" selected> 
              <? echo tsl($useconfirm); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_CONFIRM; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="useemail">
              <option value="<? echo $useemail; ?>" selected> 
              <? echo tsl($useemail); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_UEMAIL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="reqrecip">
              <option value="<? echo $reqrecip; ?>" selected> 
              <? echo tsl($reqrecip); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_RECIP; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="usepreferred">
              <option value="<? echo $usepreferred; ?>" selected> 
              <? echo tsl($usepreferred); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_PARTNER; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="popcheck">
              <option value="<? echo $popcheck; ?>" selected> 
              <? echo tsl($popcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_POPUP; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="javacheck">
              <option value="<? echo $javacheck; ?>" selected> 
              <? echo tsl($javacheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_JAVA; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="flcheck">
              <option value="<? echo $flcheck; ?>" selected> 
              <? echo tsl($flcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_FL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="iframecheck">
              <option value="<? echo $iframecheck; ?>" selected> 
              <? echo tsl($iframecheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_IFRAME; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="objectcheck">
              <option value="<? echo $objectcheck; ?>" selected> 
              <? echo tsl($objectcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_OBJECT; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="usedupe">
              <option value="<? echo $usedupe; ?>" selected> 
              <? echo tsl($usedupe); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_DUP; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="badwordcheck">
              <option value="<? echo $badwordcheck; ?>" selected> 
              <? echo tsl($badwordcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_BANW; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="badword" value="<? echo $badword ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_BANWL; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="galinmain" value="<? if($galinmain) {echo $galinmain;}else{echo "100";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_GALINMAIN; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" value="<? if($descleng) {echo $descleng;}else{echo "50";} ?>" name="descleng">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_LDESC; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" value="<? if($daynormalgal) {echo $daynormalgal;}else{echo "5";} ?>" name="daynormalgal">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_GALN; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" value="<? if($daypartnergal) {echo $daypartnergal;}else{echo "5";} ?>" name="daypartnergal">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_GALP; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="cjultra">
              <option value="<? echo $cjultra; ?>"> 
              <? echo tsl($cjultra); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_USET; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="cjstring" value="<? echo $cjstring; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_TRB; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="cjstring2" value="<? echo $cjstring2; ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_TRA; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="dateform" value="<? if($dateform) { echo $dateform; } else { echo "Y-m-d"; } ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_TGP_DATE; ?>
          </TD>
        </TR>
        <TR>
          <TD ALIGN="LEFT" COLSPAN="2"><B>
            <? echo GTGP_ADMIN_GAL_ADV; ?>
            </B></TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="posting">
              <option value="<? echo $posting; ?>"> 
              <? echo tsl($posting); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_POSTING; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="makelngbox">
              <option value="<? echo $makelngbox; ?>"> 
              <? echo tsl($makelngbox); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_MKLNGBOX; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="advgalcheck">
              <option value="<? echo $advgalcheck; ?>"> 
              <? echo tsl($advgalcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_ADVCHECK; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="galminpic" value="<? if($galminpic) {echo $galminpic;}else{echo "15";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_MINPIC; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="galmaxpic" value="<? if($galmaxpic) {echo $galmaxpic;}else{echo "20";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_MAXPIC; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="maxlink" value="<? if($maxlink) {echo $maxlink;}else{echo "5";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_MAXLINK; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%"> 
            <select name="thumbcheck">
              <option value="<? echo $thumbcheck; ?>"> 
              <? echo tsl($thumbcheck); ?>
              </option>
              <option value="Yes">
              <? echo GTGP_YES; ?>
              </option>
              <option value="No">
              <? echo GTGP_NO; ?>
              </option>
            </select>
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_THUMBCHECK; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="thumbw" value="<? if($thumbw) {echo $thumbw;}else{echo "50,150";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_THUMBW; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">
            <input type="text" name="thumbh" value="<? if($thumbh) {echo $thumbh;}else{echo "50,150";} ?>">
          </TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <? echo GTGP_ADMIN_GAL_THUMBH; ?>
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="RIGHT" WIDTH="30%">&nbsp;</TD>
          <TD ALIGN="LEFT"  WIDTH="70%">
            <input type="submit" name="change" value="<? echo GTGP_ADMIN_TGP_SUBMIT; ?>">
          </TD>
        </TR>
        <TR> 
          <TD ALIGN="CENTER" COLSPAN="2"> <A HREF="admin/index.php">
            <? echo GTGP_SET_RETURN; ?>
            </A> 
            <? echo "<BR><font color=red>$message</center><BR>"; ?>
          </TD>
        </TR>
      </TABLE>
<BR><BR>
</TD>
</TR>
</TABLE>
</form>
</body>
</html>