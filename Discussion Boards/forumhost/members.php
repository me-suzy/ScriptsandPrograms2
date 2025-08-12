<?
include "./config.php";

if ($email && $password && $getcode && $width && $height){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		print "The email or password you've entered is incorrect. Please go back and try again.";
		exit;
	}
	$sql = "select forumtitle from $table where id='$validlogin'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);
	$forumtitle = $resrow[0];
	$htmllinkcode = "<a href=&quot;".$fhurl."forum.php?id=$validlogin&quot;>$forumtitle</a>";
	$popupcode = "<a href=&quot;#&quot; onClick=&quot;window.open('".$fhurl."forum.php?id=$validlogin','pop_forum','height=$height,width=$width,top=0,left=0,resizable=yes,scrollbars=yes')&quot;>$forumtitle</a>";
	$iframecode = "&lt;iframe src=&quot;".$fhurl."forum.php?id=".$validlogin."&quot; name=&quot;forum&quot; marginwidth=&quot;0&quot; marginheight=&quot;0&quot; width = &quot;$width&quot; height=&quot;$height&quot; frameborder=&quot;0&quot;&gt;&lt;/iframe&gt;";
	print "<form name='form1' method='post' action='members.php'><table width='95%' border='0' cellspacing='0' cellpadding='0'><tr> <td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Your Forum URL:</font></td><td width='50%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> <input type='text' name='textfield2' size='50' value='".$fhurl."forum.php?id=$validlogin'></font></td></tr><tr> <td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>HTML Link:</font></td><td width='50%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> <input type='text' name='textfieldx' size='50' value=\"$htmllinkcode\"></font></td></tr><tr> <td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Popup Code:</font></td><td width='50%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> <input type='text' name='textfield' size='50' value=\"$popupcode\"></font></td></tr><tr> <td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>IFRAME Code:</font></td><td width='50%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> <textarea name='textfield3' cols='38' rows='5'>$iframecode</textarea></font></td></tr><tr> <td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Change the Width Height for IFRAME and Popup Dimensions:</font></td><td width='50%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Width: <input type='text' name='width' size='3' maxlength='3' value='$width'>&nbsp;&nbsp;Height: <input type='text' name='height' size='3' maxlength='3' value='$height'><input type='hidden' name='email' value='$email'><input type='hidden' name='password' value='$password'><input type='hidden' name='getcode' value='1'>&nbsp;<input type='submit' value='Change'></font></td></tr></table><br></form>";
	exit;
}

if ($email && $password && $reset=="1"){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $fhtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print $bannerhtmlcode;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "update $table set totalposts='0' where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "delete from $tableposts where owner='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: members.php?email=$email&password=$password");
	exit;
}

if ($email && $password && $update){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $fhtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print $bannerhtmlcode;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	if ($newpass) {
		$sql = "update $table set password='$newpass' where id='$validlogin'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$password = $newpass;
	}
	setcookie ("ckForumAdminEmail", "$email", "315360000");
	setcookie ("ckForumAdminPassword", "$password", "315360000");
	Header("Location: members.php?email=$email&password=$password");
	exit;
}

if ($email && $password && $saveforumsettings){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		print "The email or password you've entered is incorrect. Please go back and try again.";
		exit;
	}
	$forumtitle = addslashes($forumtitle);
	$forumdescr = addslashes($forumdescr);
	$headtext = addslashes($headtext);
	$bottomtext = addslashes($bottomtext);
	$headhtml = addslashes($headhtml);
	$foothtml = addslashes($foothtml);
	$sql = "update $table set forumtitle='$forumtitle', forumdescr='$forumdescr', headhtml='$headhtml', foothtml='$foothtml', headtext='$headtext', bottomtext='$bottomtext', mybordercolor='$mybordercolor', mybordersize='$mybordersize', mycellspacing='$mycellspacing', mycellpadding='$mycellpadding', bannedippost='$bannedippost', bannedipforum='$bannedipforum', enablesmilies='$enablesmilies', profanityfilter='$profanityfilter', allowimages='$allowimages' where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	setcookie ("ckForumAdminEmail", "$email", "315360000");
	setcookie ("ckForumAdminPassword", "$password", "315360000");
	Header("Location: members.php?email=$email&password=$password");
	exit;
}

if ($email && $password){
	$email = strtolower($email);
	$password = strtolower($password);
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $fhtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print $bannerhtmlcode;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "select forumtitle,forumdescr,headhtml,foothtml,headtext,bottomtext,mybordercolor,mybordersize,mycellspacing,mycellpadding,bannedippost,bannedipforum,enablesmilies,profanityfilter,allowimages from $table where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$forumtitle = $resrow[0];
	$forumdescr = $resrow[1];
	$headhtml = $resrow[2];
	$foothtml = $resrow[3];
	$headtext = $resrow[4];
	$bottomtext = $resrow[5];
	$mybordercolor = $resrow[6];
	$mybordersize = $resrow[7];
	$mycellspacing = $resrow[8];
	$mycellpadding = $resrow[9];
	$bannedippost = $resrow[10];
	$bannedipforum = $resrow[11];
	$enablesmilies = $resrow[12];
	$profanityfilter = $resrow[13];
	$allowimages = $resrow[14];
	$forumtitle = stripslashes($forumtitle);
	$forumdescr = stripslashes($forumdescr);
	$headtext = stripslashes($headtext);
	$bottomtext = stripslashes($bottomtext);
	$headhtml = stripslashes($headhtml);
	$foothtml = stripslashes($foothtml);

	if ($enablesmilies=="1") $ensmchkd = "checked";
	if ($profanityfilter=="1") $enpfchkd = "checked";
	if ($allowimages=="1") $allowimgchkd = "checked";

	$sql = "select count(*) from $tableposts where owner='$validlogin'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);	
	$totalposts = $resrow[0];
	$sql = "update $table set totalposts='$totalposts' where id='$validlogin'";
}

if (!$email || !$password){
	$pagetitle = $fhtitle.": Log In";
	if ($headerfile) include $headerfile;
	print $bannerhtmlcode;
	print "<form name='form1' method='get' action='members.php'><table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' align='center'>
	  <tr bgcolor='$tablebgcolor'> 
	    
      <td width='30%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Email:</font></td>
	    <td width='70%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      <input type='text' name='email' maxlength='255' value='$email'>
        </font></td>
	  </tr>
	  <tr bgcolor='$tablebgcolor'> 
	    <td width='30%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Password:</font></td>
	    <td width='70%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      <input type='password' name='password' value='$password'>
	      </font></td>
	  </tr>
	  <tr bgcolor='$tablebgcolor'> 
	    <td width='30%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>&nbsp;</font></td>
	    <td width='70%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      
        <input type='submit' value='Log In' name='submit'>
	      </font></td>
	  </tr>
	</table></form>";
	if ($footerfile) include $footerfile;
	exit;
}

if (!$validlogin){
	$pagetitle = $fhtitle.": Invalid Email and/or Password";
	if ($headerfile) include $headerfile;
	print $bannerhtmlcode;
	print "The email or password you've entered is incorrect. Please go back and try again.";
	if ($footerfile) include $footerfile;
	exit;
}

$pagetitle = $fhtitle.": Members Area";
if ($headerfile) include $headerfile;
print $bannerhtmlcode;
?>
<b><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="#FF0000"><? if ($status) print "The following error(s) have occured:<br>".$status; ?></font></b><br>
<form name="form1" method="post" action="members.php">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $accountrules; ?>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="62%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Total 
        Posts:</font></td>
      <td width="38%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $totalposts; ?>
        [<a href="members.php?email=<? print $email; ?>&password=<? print $password; ?>&reset=1"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Delete 
        <b>ALL</b> Posts</font></a>] </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="62%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Email:</font></td>
      <td width="38%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="email" value="<? print $email; ?>">
        <? print $email; ?>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="62%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">New 
        Password (enter ONLY to change it):</font></td>
      <td width="38%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="newpass">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="62%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"><div align="center"><b>[<a href="#" onClick="window.open('members.php?email=<? print $email; ?>&password=<? print $password; ?>&getcode=1&width=640&height=480','pop_getcode','height=220,width=600,top=0,left=150,resizable=no,scrollbars=no')"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Get 
        HTML Code</font></a>]</b></div></font></td>
      <td width="38%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="update" value="1">
        <input type="hidden" name="password" value="<? print $password; ?>">
        <input type="submit" value="Save Changes">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Your 
        Forum URL:</font>
        <input type="text" name="textfield" size="50" value="<? print $fhurl."forum.php?id=$validlogin"; ?>">
        <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        </font></td>
    </tr>
  </table>
</form>

<form name="form2" method="post" action="members.php">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Forum 
        Appearance:</font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Table 
        Border Color:</font></td>
      <td width="64%"> 
        <input type="text" name="mybordercolor" value="<? print $mybordercolor; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Table 
        Border Size:</font></td>
      <td width="64%"> 
        <input type="text" name="mybordersize" value="<? print $mybordersize; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Table 
        Cell Spacing:</font></td>
      <td width="64%"> 
        <input type="text" name="mycellspacing" value="<? print $mycellspacing; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Table 
        Cell Padding:</font></td>
      <td width="64%"> 
        <input type="text" name="mycellpadding" value="<? print $mycellpadding; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Header 
        HTML:</font></td>
      <td width="64%"> 
        <textarea name="headhtml" cols="30" rows="4"><? print $headhtml; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Footer 
        HTML:</font></td>
      <td width="64%"> 
        <textarea name="foothtml" cols="30" rows="4"><? print $foothtml; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Header 
        Text :</font></td>
      <td width="64%"> 
        <textarea name="headtext" cols="30" rows="4"><? print $headtext; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Footer 
        Text :</font></td>
      <td width="64%"> 
        <textarea name="bottomtext" cols="30" rows="4"><? print $bottomtext; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">&nbsp; 
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Forum 
        Options: </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Forum 
        Title :</font></td>
      <td width="64%"> 
        <input type="text" name="forumtitle" value="<? print $forumtitle; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Forum 
        Description :</font></td>
      <td width="64%"> 
        <input type="text" name="forumdescr" value="<? print $forumdescr; ?>">
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">IPs 
        Banned from Posting:<br>
        (enter one per line -- use whole or partial IP addresses)</font></td>
      <td width="64%"> 
        <textarea name="bannedippost" cols="30" rows="4"><? print $bannedippost; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">IPs 
        Banned from Forum:<br>
        (enter one per line -- use whole or partial IP addresses)</font></td>
      <td width="64%"> 
        <textarea name="bannedipforum" cols="30" rows="4"><? print $bannedipforum; ?></textarea>
      </td>
    </tr>
    <? if ($adminallowimages) print "    <tr bgcolor='$tablebgcolor'> 
      <td width='36%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Allow 
        Image Attachments? </font></td>
      <td width='64%'> 
        <input type='checkbox' name='allowimages' value='1' $allowimgchkd>
        <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Yes!</font> 
      </td>
    </tr>";
?>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Enable 
        Smilies? </font></td>
      <td width="64%"> 
        <input type="checkbox" name="enablesmilies" value="1" <? print $ensmchkd; ?>>
        <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Yes!</font> 
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="36%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Enable 
        Profanity Filter? </font></td>
      <td width="64%"> 
        <input type="checkbox" name="profanityfilter" value="1" <? print $enpfchkd; ?>>
        <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Yes!</font> 
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"> 
        <div align="center"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
          <input type="hidden" name="email" value="<? print $email; ?>">
          <input type="hidden" name="password" value="<? print $password; ?>">
          <input type="hidden" name="saveforumsettings" value="1">
          <input type="submit" value="Save Changes" name="submit">
          </font></div>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"> 
        <div align="center"></div>
      </td>
    </tr>
  </table>
</form>
<center><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'><b>Powered 
          By <a href='http://nukedweb.memebot.com/' target='_other'>ForumHost</a></b></font></center>
<? if ($footerfile) include $footerfile; ?>