<?
include "./config.php";

if ($email && $password && $getcode!=""){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		print "The email or password you've entered is incorrect. Please go back and try again.";
		exit;
	}
	$sql = "select filename from $tablepics where id='$getcode'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		print "The image does not exist.";
		exit;
	}
	$resrow = mysql_fetch_row($result);
	$filename = $resrow[0];
	$filename = strtolower($filename);
	$filename = str_replace(".gif", ".jpg", $filename);
	$filename = str_replace(".png", ".jpg", $filename);
	$imghtmlcode = "<img src=\"".$ihurl."show.php/$getcode/$filename\" border=\"0\">";
	$imgurl = $ihurl."show.php/$getcode/$filename";
	print "<div align='center'>
	  <form name='form1' method='get' action='members.php'>
	    <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>HTML Code: <br>
	    <textarea name='textfield' cols='30'>$imghtmlcode</textarea>
	    <br>
	    Image URL:<br>
	    <input type='text' name='textfield' size='30' value='$imgurl'>
	    <br>Note: File extension will always be .jpg no matter what it was when you uploaded 
	    it.</font> 
	  </form>
	</div>";
	exit;
}


if ($email && $password && $reset=="1"){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $ihtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "update $tableusers set totalviews='0' where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: members.php?email=$email&password=$password");
	exit;
}

if ($email && $password && $newpass!=""){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $ihtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "update $tableusers set password='$newpass' where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: members.php?email=$email&password=$newpass");
	exit;
}

if ($email && $password && $deleteid){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $ihtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "select filename from $tablepics where id='$deleteid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$filename = $resrow[0];
	$sql = "delete from $tablepics where id='$deleteid' and owner='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	@unlink($upfilesfolder.$validlogin."-".$filename);
	Header("Location: members.php?email=$email&password=$password");
	exit;
}

if ($email && $password && $upimages){
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $ihtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	if (count($upfile)==0) $uploadstatus = "You haven't selected any files to upload.<br>";

	if (!$status && !$uploadstatus){
		for($x=0;$x<count($upfile);$x++){
			if ($upfile_name[$x]){
				$upfilestatus = "";
				if (($maximgsize!="") && ($upfile_size[$x]>$maximgsize)) $upfilestatus = $upfile_name[$x]." exceeds the maximum image size limit.<br>";
				if (($maximagesperaccount!="") && (numimagesacct($validlogin)>=$maximagesperaccount)) $upfilestatus = $upfile_name[$x]." - You have uploaded the maximum number of images in your account.<br>";
				$imgtyp = substr($upfile_name[$x], -3);
				$imgtyp = strtolower($imgtyp);
				if (($imgtyp!="jpg") && ($imgtyp!="gif") && ($imgtyp!="png")) $upfilestatus = $upfile_name[$x]." is not a valid image (JPG, GIF and PNG only).<br>";
				$sql = "select filename from $tablepics where owner='$validlogin' and filename='$upfile_name[$x]'";
				$result = mysql_query($sql) or die("Failed: $sql");
				$numrows = mysql_num_rows($result);
				if ($numrows!=0) $upfilestatus = $upfile_name[$x]." - File already exists.<br>";
				if (!$upfilestatus){
					$sql = "insert into $tablepics values('', '$validlogin', '$upfile_name[$x]', '$upfile_size[$x]', '0', now())"; 
					$result = mysql_query($sql) or die("Failed: $sql");
					@copy($upfile[$x], $upfilesfolder.$validlogin."-".$upfile_name[$x]);
					$uppedfiles = $upfile_name[$x]." successfully uploaded.<br>";
				}
				$uploadstatus .= $upfilestatus;
			}
		}
	}
}

if ($email && $password){
	$email = strtolower($email);
	$password = strtolower($password);
	$validlogin = verifylogin($email,$password);
	if (!$validlogin){
		$pagetitle = $ihtitle.": Invalid Email and/or Password";
		if ($headerfile) include $headerfile;
		print "The email or password you've entered is incorrect. Please go back and try again.";
		if ($footerfile) include $footerfile;
		exit;
	}
	$sql = "select totalviews from $tableusers where id='$validlogin'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$totalviews = $resrow[0];

	for($v=0;$v<$numuploadfields;$v++){
		$uploadfields .= "<input type='file' name='upfile[".$v."]' size='".$uploadfieldcharwidth."'><br>";
	}
	$accountrules = "<ul>";
	if ($maximagesperaccount) $accountrules .= "<li>You may have a total of $maximagesperaccount images in your account.</li>";
	if ($remove_views) $accountrules .= "<li>Each image will be removed after being viewed $remove_views times.</li>";
	if ($remove_days) $accountrules .= "<li>Each image will be removed after $remove_days days.</li>";
	if ($maximgsize) $accountrules .= "<li>Images cannot be larger than $maximgsize bytes.</li>";
	$accountrules .= "</ul>";
	
	
		
}

if (!$email || !$password){
	$pagetitle = $ihtitle.": Log In";
	if ($headerfile) include $headerfile;
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
	$pagetitle = $ihtitle.": Invalid Email and/or Password";
	if ($headerfile) include $headerfile;
	print "The email or password you've entered is incorrect. Please go back and try again.";
	if ($footerfile) include $footerfile;
	exit;
}
$sql = "select id,filename,views from $tablepics where owner='$validlogin'";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
$numimagesinacct = $numrows;
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$imageid = $resrow[0];
	$filename = $resrow[1];
	$imageviews = $resrow[2];
	$imagelisthtml .= "<tr bgcolor='$tablebgcolor'><td><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>[<a href='members.php?email=$email&password=$password&deleteid=$imageid'><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Delete</font></a>] [<a href='#' onClick=\"window.open('members.php?email=$email&password=$password&getcode=$imageid','pop_getcode','height=170,width=350,top=0,left=150,resizable=no,scrollbars=no')\"><font face='$tablefontname' size='$tablefontsize' color='$tabletextcolor'>Get Code</font></a>][Views: $imageviews] $filename</font></td></tr>\n";
}

$pagetitle = $ihtitle.": Members Area";
if ($headerfile) include $headerfile;
?>
<b><font size="<? print $tablefontsize; ?>" face="<? print $tablefontname; ?>" color="#FF0000"><? if ($status) print "The following error(s) have occured:<br>".$status; ?></font></b><br>
<form name="form1" method="get" action="members.php">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">
        <? print $accountrules; ?>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Total 
        Image Views</font></td>
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <? print $totalviews; ?>
        [<a href="members.php?email=<? print $email; ?>&password=<? print $password; ?>&reset=1"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Reset</font></a>] 
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Email:</font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="email" value="<? print $email; ?>"><? print $email; ?>
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
      <td width="50%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">&nbsp; 
        <input type="hidden" name="password" value="<? print $password; ?>">
        </font></td>
      <td width="50%"> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <input type="hidden" name="update" value="1">
        <input type="submit" value="Save Changes">
        </font></td>
    </tr>
  </table>
</form>
<? if (!$uploadstatus && !$uppedfiles) $uploadstatus = "&nbsp;"; ?>
<form name="form2" method="post" action="members.php" enctype="multipart/form-data">
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td width="26%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Upload 
        Images: </font></td>
      <td width="74%"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
        <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"><b><? print $uploadstatus.$uppedfiles; ?></b></font>
        </font></td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2"> 
        <center>
          <? print $uploadfields; ?>
        </center>
      </td>
    </tr>
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td colspan="2">
        <div align="center"><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">&nbsp; 
          <input type="hidden" name="email" value="<? print $email; ?>">
          <input type="hidden" name="password" value="<? print $password; ?>">
          </font> <font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>"> 
          <input type="hidden" name="upimages" value="1">
          <input type="submit" value="Upload Images">
          </font></div>
      </td>
    </tr>
  </table>
  <br>
  <table width="<? print $tablewidth; ?>" border="<? print $bordersize; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" align="center">
    <tr bgcolor="<? print $tablebgcolor; ?>"> 
      <td><font face="<? print $tablefontname; ?>" size="<? print $tablefontsize; ?>" color="<? print $tabletextcolor; ?>">Displaying <? print $numimagesinacct; ?> uploaded images:</font></td>
    </tr>
<? print $imagelisthtml; ?>
  </table>
</form>
<? print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>FreeImageHost</a></center>";
if ($footerfile) include $footerfile; ?>