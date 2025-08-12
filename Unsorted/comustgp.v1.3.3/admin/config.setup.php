<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<?php

if (!file_exists($DOCUMENT_ROOT . "/includes/config.inc.php"))
{
    die ("Can not locate config.inc.php. Please make sure that you have it withing your includes directory and it is chmod to 777.");
}else{
    if (!is_writeable($DOCUMENT_ROOT . "/includes/config.inc.php")){
       die ("Unable to write to config.inc.php. Please make sure that you chmod it to 777.");
    }
}

if(isset($change)){

$setup_ar[] = "<?php\n";
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
$setup_ar[] = "\$useblacklist = '$useblacklist';\n";
$setup_ar[] = "\$useconfirm = '$useconfirm';\n";
$setup_ar[] = "\$reqrecip = '$reqrecip';\n";
$setup_ar[] = "\$popcheck = '$popcheck';\n";
$setup_ar[] = "\$usepreferred = '$usepreferred';\n";
$setup_ar[] = "\$usedupe = '$usedupe';\n";
$setup_ar[] = "\$badwordcheck = '$badwordcheck';\n";
$setup_ar[] = "\$badword = '$badword';\n";
$setup_ar[] = "\$lim = $lim;\n";
$setup_ar[] = "\$descleng = $descleng;\n";
$setup_ar[] = "\$dnow = date(\"Ymd\");\n";
$setup_ar[] = "\$then = date(\"Ymd\", \$adjust_time);\n";
$setup_ar[] = "\$cjultra = '$cjultra';\n";
$setup_ar[] = "\$cjstring = '$cjstring';\n";
$setup_ar[] = "\$cjstring2 = '$cjstring2';\n";  
$setup_ar[] = "\$conn = mysql_connect(\"\$dbhost\",\"\$dbuser\",\"\$dbpasswd\")
            or die(\"Unable to connect to SQL server!\");
            @mysql_select_db(\"\$db\")
            or die(\"Unable to select database!\");\n";

$setup_ar[] = "function SessionID(\$length=30) 
{ 
\$Pool = \"23456789ABCDEFGHJKLMNPQRSTUVWXYZ\"; 
\$Pool .= \"23456789abcdefghjklmnpqrstuvwxyz\"; 
for(\$index = 0; \$index < \$length; \$index++) 
{ 
\$sid .= substr(\$Pool, 
(rand()%(strlen(\$Pool))), 1); 
} 
return(\$sid); 
} 
srand(time()); 
\$session = SessionID(45);\n";



$setup_ar[] = "?>";

if ($fp = @ fopen($DOCUMENT_ROOT . "/includes/config.inc.php" , "w")) {
            $common = implode("", $setup_ar);
            fwrite($fp, $common);
            fclose($fp);
   }
   $message = "Setup Updated!";
}
?> 
<?php include ($DOCUMENT_ROOT . "/includes/config.inc.php"); ?>
<html>
<body>
<center>
  <h2>Comus site settings<br>
  </h2>
</center>
<center><a href="index.php"><b><font size=-1 face=arial>Return to main page</font></b></a><br></center>
<? echo "<center><font color=red>$message</font></center>"; ?>
<form name="form1" method="post" action="<?$PHP_SELF?>">
  <table width="600" border="0" cellspacing="0" cellpadding="3" bgcolor="#6699CC" align="center">
    <tr> 
      <td width="278"><font size="2"><b>Host:</b> (almost always 
        localhost)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="dbhost" value="<? echo $dbhost; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Database Name: </b>(your 
        database name)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="db" value="<? echo $db; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Database user name: </b>(db 
        account name)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="dbuser" value="<? echo $dbuser; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Database Password: </b>(db 
        password)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="dbpasswd" value="<? echo $dbpasswd; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>TGP Email: </b>(Your email 
        address)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="tgpemail" value="<? echo $tgpemail; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278" bgcolor="#6699CC"><font size="2"><b>Site 
        URL: </b>(mysite.com note: no http://www)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="sitename" value="<? echo $sitename; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Recip URL: </b>(ex: http://www.you.com)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="recip" value="<? echo $recip; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Site Owner: </b>(your 
        name)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="siteowner" value="<? echo $siteowner; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Background Color: </b>(i.e. 
        ffffff or white)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="bgcolor" value="<? echo $bgcolor; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Number of days galleries 
        are displayed:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="arch_days" value="<? echo $adj_var; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Use Blacklist?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="useblacklist">
            <option value="<? echo $useblacklist; ?>" selected> 
            <? echo $useblacklist; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Use Confirm email?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="useconfirm">
            <option value="<? echo $useconfirm; ?>" selected> 
            <? echo $useconfirm; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Require reciprocal link?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="reqrecip">
            <option value="<? echo $reqrecip; ?>" selected> 
            <? echo $reqrecip; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Use Preferred?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="usepreferred">
            <option value="<? echo $usepreferred; ?>" selected> 
            <? echo $usepreferred; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Allow pop-up's?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="popcheck">
            <option value="<? echo $popcheck; ?>" selected> 
            <? echo $popcheck; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Check for Duplicate Posts?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="usedupe">
            <option value="<? echo $usedupe; ?>" selected> 
            <? echo $usedupe; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><b><font size="2">Check for Banned words?:</font></b></td>
      <td width="310"> 
        <div align="left"> 
          <select name="badwordcheck">
            <option value="<? echo $badwordcheck; ?>" selected> 
            <? echo $badwordcheck; ?>
            </option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>List Banned words:</b> 
        (comma seperate words)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="badword" value="<? echo $badword ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Limit Archive Post View:</b> 
        (number of listings)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" name="lim" value="<? echo $lim; ?>">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278"><font size="2"><b>Limit Description Length:</b> 
        (number of letters)</font></td>
      <td width="310"> 
        <div align="left"> 
          <input type="text" value="<? echo $descleng; ?>" name="descleng">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278" height="28"><b>Do you use a traffic trade 
        script?</b></td>
      <td width="310" height="28"> 
        <select name="cjultra">
          <option value="<? echo $cjultra; ?>"> 
          <? echo $cjultra; ?>
          </option>
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td width="278" height="41"> 
        <blockquote> 
          <p>If yes, Code that goes before http://<br>
            ex: out.php?url=</p>
        </blockquote>
      </td>
      <td width="310" height="41"> 
        <input type="text" name="cjstring" value="<? echo $cjstring; ?>">
      </td>
    </tr>
    <tr> 
      <td width="278"> 
        <blockquote> 
          <p>If yes, Code that goes after .html<br>
            ex: &amp;link=gal&amp;s=70&amp;first=1</p>
        </blockquote>
      </td>
      <td width="310"> 
        <input type="text" name="cjstring2" value="<? echo $cjstring2; ?>">
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> 
          <input type="submit" name="change" value="Submit Changes">
        </div>
      </td>
    </tr>
    <tr> 
      <td width="278">&nbsp;</td>
      <td width="310"> 
        <div align="center"></div>
      </td>
    </tr>
  </table>
</form>
</body>
</html>