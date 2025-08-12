<?
include "./config.php";

if ($reset){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $gbtitle.": ".$login_page_error;
		if ($headerfile) include $headerfile;
		print "Either the Email Address you've entered is not in the database, or the password is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	if ($reset=="1") $fld = "pageviews";
	if ($reset=="2") $fld = "uniquehits";
	$sql = "update $table set $fld='0' where email='$email' and password='$password'";
	$result = mysql_query($sql) or die("Failed: $sql");
}


if ($update=="1"){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $gbtitle.": ".$login_page_error;
		if ($headerfile) include $headerfile;
		print "Either the Email Address you've entered is not in the database, or the password is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	if (!$sitetitle) $status .= "Website Title is required.<br>";
	if (!$siteurl) $status .= "Website URL is required.<br>";
	if (!$email) $status .= "An email address is required.<br>";
	if (!$status){
		$sql = "update $table set email='$email', siteurl='$siteurl', sitetitle='$sitetitle', headtext='$headtext', bgcolor='$bgcolor', textcolor='$textcolor', linkcolor='$linkcolor' where email='$email' and password='$password'";
		$result = mysql_query($sql) or die("Failed: $sql");
	}
	if ($newpass){
		$sql = "update $table set password='$newpass' where email='$email' and password='$password'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$password = $newpass;
	}
}

if ($email && $password){
	$email = strtolower($email);
	$password = strtolower($password);
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $gbtitle.": ".$login_page_error;
		if ($headerfile) include $headerfile;
		print "Either the Email Address you've entered is not in the database, or the password is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "select id,email,sitetitle,siteurl,headtext,bgcolor,textcolor,linkcolor,uniquehits,pageviews,entries from $table where email='$email' and password='$password'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$email = $resrow[1];
	$sitetitle = $resrow[2];
	$siteurl = $resrow[3];
	$headtext = $resrow[4];
	$bgcolor = $resrow[5];
	$textcolor = $resrow[6];
	$linkcolor = $resrow[7];
	$uniquehits = $resrow[8];
	$pageviews = $resrow[9];
	$entries = $resrow[10];
	$htmlcode = "[&lt;a href=\"#\" onClick=\"javascript:window.open('".$gburl.$guestbook_filename."?id=$id','pop_gb','height=$gb_popwin_height,width=$gb_popwin_width,top=0,left=0,resizable=no,scrollbars=yes');\"&gt;View Guestbook&lt;/a&gt;] \n[&lt;a href=\"#\" onClick=\"javascript:window.open('".$gburl.$guestbook_filename."?id=$id&sign=1','pop_gb','height=$gb_popwin_height,width=$gb_popwin_width,top=0,left=0,resizable=no,scrollbars=yes');\"&gt;Sign Guestbook&lt;/a&gt;]";
}

if (!$email || !$password){
	$pagetitle = $gbtitle.": ".$login_page_start;
	if ($headerfile) include $headerfile;
	print "<form name='form1' method='post' action='$edit_filename'><table width='$tablewidth' border='$bordersize' cellspacing='$cellspacing' cellpadding='$cellpadding' align='center'>
	  <tr bgcolor='$tablebgcolor'> 
	    <td width='50%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Email:</font></td>
	    <td width='50%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      <input type='text' name='email' maxlength='255' value='$email'></font></td>
	  </tr>
	  <tr bgcolor='$tablebgcolor'> 
	    <td width='50%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Password:</font></td>
	    <td width='50%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      <input type='password' name='password' value='$password'>
	      </font></td>
	  </tr>
	  <tr bgcolor='$tablebgcolor'> 
	    <td width='50%'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>&nbsp;</font></td>
	    <td width='50%'> <font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'> 
	      <input type='submit' value='$login_button_text' name='submit'>
	      </font></td>
	  </tr>
	</table></form>";
	if ($footerfile) include $footerfile;
	exit;
}

$pagetitle = $gbtitle.": ".$login_page_done;
if ($headerfile) include $headerfile;
?>
<b><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="#0000FF"><? if ($new=="1") print "Your account has been created! Below are more options for designing your guestbook."; ?></font></b><br>
<b><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="#FF0000"><? if ($status) print "The following error(s) have occured:<br>".$status; ?></font></b><br>
<form name="form1" method="post" action="<? print $edit_filename; ?>">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Pageviews:</font></td>
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $pageviews; ?>
        [<a href="<? print $edit_filename; ?>?email=<? print $email; ?>&password=<? print $password; ?>&reset=1"><font color="<? print $tabletextcolor; ?>">Reset</font></a>] 
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Unique 
        Hits:</font></td>
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $uniquehits; ?>
        [<a href="<? print $edit_filename; ?>?email=<? print $email; ?>&password=<? print $password; ?>&reset=2"><font color="<? print $tabletextcolor; ?>">Reset</font></a>] 
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"><center>[<a href="#" onClick="javascript:window.open('<? print $gburl.$guestbook_filename; ?>?id=<? print $id; ?>&preview=1','pop_gb','height=<? print $gb_popwin_height; ?>,width=<? print $gb_popwin_width; ?>,top=0,left=0,resizable=no,scrollbars=yes');"><font color="<? print $tabletextcolor; ?>">Preview Guestbook</font></a>] [<a href="#" onClick="javascript:window.open('<? print $gburl.$guestbook_filename; ?>?id=<? print $id; ?>&edit=1&adminemail=<? print $email; ?>&adminpassword=<? print $password; ?>','pop_gb','height=<? print $gb_popwin_height; ?>,width=<? print $gb_popwin_width; ?>,top=0,left=0,resizable=no,scrollbars=yes');"><font color="<? print $tabletextcolor; ?>">Edit Guestbook Entries</font></a>]</center></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Email:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $email; ?>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">New 
        Password (enter ONLY to change it):</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="password" name="newpass">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Website 
        URL:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="siteurl" maxlength="255" value="<? print $siteurl; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Website 
        Title:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="sitetitle" maxlength="255" value="<? print $sitetitle; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Background 
        Color:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="bgcolor" maxlength="255" value="<? print $bgcolor; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Text 
        Color:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="textcolor" maxlength="255" value="<? print $textcolor; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Link 
        Color:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="text" name="linkcolor" maxlength="255" value="<? print $linkcolor; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Header 
        Text (Appears at the top of your guestbook.): </font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <textarea name="headtext" wrap="PHYSICAL" rows="3"><? print stripslashes($headtext); ?></textarea>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">&nbsp; 
        <input type="hidden" name="email" value="<? print $email; ?>">
        <input type="hidden" name="password" value="<? print $password; ?>">
        </font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="update" value="1">
        <input type="submit" value="<? print $updateinfo_button_text; ?>">
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%">&nbsp;</td>
      <td width="50%">&nbsp;</td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"> 
        <div align="center"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Insert 
          this code into the source of your webpage to create View/Sign links 
          to your guestbook: <br>
          <textarea cols="50" rows="3" wrap="OFF"><? print $htmlcode; ?></textarea>
          </font></div>
      </td>
    </tr>
  </table>
</form>
<?
print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>GuestBookHost</a></center>";
if ($footerfile) include $footerfile;
?>