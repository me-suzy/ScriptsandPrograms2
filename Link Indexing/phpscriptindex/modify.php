<?
include "./config.php";

if ($getcode && $email && $pass){
	if ($headerfile) include $headerfile;
	print "<blockquote><font size='-1' face='$fontname'>$sitetitle asks for nothing more than a link back 
		  from your site. The HTML code for this link is below. <br>
			<br>
		  This link helps you too! The Script Index works like a TopSite. Hits 
		  from your site to ours are counted by this link, and the more clicks you send 
		  us, the higher up your script's listing will appear in the category.
			<br>
			</b>Copy this code and place it somewhere, anywhere on your site:<br>
			<br>
			</font> 
			<form name='form1' method='post' action=''>
			  <font size='-1' face='$fontname'> 
			  <textarea name='textfield' cols='40' rows='2' wrap='VIRTUAL'><a href=\"".$script_index_url."in.php?id=$getcode\">$sitetitle PHP Script Index</a></textarea>
			  <br>
			  <br>
			  You may also create a link to our site any other way you choose, perhaps via 
			  a newsletter. You can use this URL:<br>
			  <br>
			  <input type='text' name='textfield2' size='40' value='".$script_index_url."in.php?id=$getcode'>
			  </font> 
			</form>
			<p align='center'><font face='$fontname'>[<a href='modify.php?email=$email&pass=$pass'>Return 
		to Members Area</a>]</font></p></blockquote>";
	if ($footerfile) include $footerfile;
	exit;
}
	
if ($updatelisting && $pass && $email){
	if (!$dlurl && !$homeurl) $status .= "Either a Homepage URL or Download URL is required.<br>";
	if (!$title) $status .= "Script Name is required.<br>";
	if (!$descr) $status .= "Script description is required.<br>";
	if (!$email) $status .= "Email address is required.<br>";
	if (!$status){
		$sql = "update $tablescripts set subcat='$subcat', title='$title', homeurl='$homeurl', dlurl='$dlurl', demourl='$demourl', descr='$descr', price='$price', version='$version' where email='$email' and password='$pass' and id='$updatelisting'";
		$result = mysql_query($sql) or die("Failed: $sql");
		if ($newpass){
			$sql = "update $tablescripts set password='$newpass' where email='$email' and password='$pass' and id='$updatelisting'";
			$result = mysql_query($sql) or die("Failed: $sql");
			$pass = $newpass;
		}
		Header("Location: modify.php?email=$email&pass=$pass");
		exit;
	}
	Header("Location: modify.php?email=$email&pass=$pass&modify=$updatelisting&status=$status");
	exit;
}


if ($email && $pass && $modify){
	$sql = "select id,subcat,title,homeurl,dlurl,demourl,descr,price,version from $tablescripts where email='$email' and password='$pass' and id='$modify'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$subcat = $resrow[1];
	$title = $resrow[2];
	$homeurl = $resrow[3];
	$dlurl = $resrow[4];
	$demourl = $resrow[5];
	$descr = $resrow[6];
	$price = $resrow[7];
	$version = $resrow[8];
	if ($headerfile) include $headerfile;
	print "<form name='form1' method='post' action='modify.php'><blockquote><font color='#FF0000'><b><font size='-1' face='$fontname'>$status</font></b></font><br></blockquote><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' align='center'><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Script Name:</font></td><td width='53%' bgcolor='$table_bgcolor'> <font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='title' value='$title'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Version:</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='version' value='$version'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Homepage URL:</font></td><td width='53%' bgcolor='$table_bgcolor'> <font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='homeurl' value='$homeurl'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Demo URL</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='demourl' value='$demourl'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Download URL:</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='dlurl' value='$dlurl'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Price: (U.S. dollars, or enter license type) </font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='price' value='$price'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Category:</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><select name='subcat'>".getcategoriesascombo($subcat)."</select></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Description (500 chars max)</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><textarea name='descr' cols='40' rows='5'>$descr</textarea></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Email Address:</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>&nbsp;$email</font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Change password (enter ONLY to change it)</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='password' name='newpass'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>&nbsp;</font></td><td width='53%' bgcolor='$table_bgcolor'> <font size='-1' face='$fontname' color='$table_textcolor'><input type='submit' value='Save Changes'><input type='hidden' name='updatelisting' value='$modify'><input type='hidden' name='email' value='$email'><input type='hidden' name='pass' value='$pass'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>&nbsp;</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>[<a href='modify.php?email=$email&pass=$pass'><font color='$table_textcolor'>Cancel</font></a>]</font></td></tr></table></form>";
	if ($footerfile) include $footerfile;
	exit;
}




if ($email && $pass && $delete){
	$sql = "select subcat from $tablescripts where id='$delete' and email='$email' and password='$pass'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows!=0){
		$resrow = mysql_fetch_row($result);
		$catid = $resrow[0];
		$sql = "delete from $tablescripts where id='$delete' and email='$email' and password='$pass'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$sql = "select ct from $tablecats where id='$catid'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$ct = $resrow[0];
		$ct--;
		$sql = "update $tablecats set ct='$ct' where id='$catid'";
		$result = mysql_query($sql) or die("Failed: $sql");
		Header("Location: modify.php?email=$email&pass=$pass");
		exit;
	}
}

if (!$email || !$pass){
	if ($status=="1") $status = "No scripts were found that were submitted using the email address and password you've provided.";
	if ($headerfile) include $headerfile;
	if ($status) print "<font color='red' face='$fontname' size='-1'>$status</font>";
	print "<form method='post' action='modify.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' align='center'><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Email Address:</font></td><td width='53%' bgcolor='$table_bgcolor'> <font size='-1' face='$fontname' color='$table_textcolor'><input type='text' name='email' value='$email'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>Password:</font></td><td width='53%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'><input type='password' name='pass'></font></td></tr><tr><td width='47%' bgcolor='$table_bgcolor'><font size='-1' face='$fontname' color='$table_textcolor'>&nbsp;</font></td><td width='53%' bgcolor='$table_bgcolor'> <font size='-1' face='$fontname' color='$table_textcolor'><input type='submit' value='Log In'></font></td></tr></table></form>";
	if ($footerfile) include $footerfile;
}

if ($email && $pass){
	$sql = "select id,subcat,title,version,hitsin,hitsout from $tablescripts where email='$email' and password='$pass'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows==0){
		Header("Location: modify.php?status=1");
		exit;
	}

	if ($headerfile) include $headerfile;
	print "<font face='$fontname' size='-1'>Found $numrows scripts registered to $email:</font>";
	print "<table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='$cellpadding' bordercolor='$table_border_color' align='center'><tr bgcolor='$table_head_color'><td width='8%'><font color='$table_head_textcolor' face='$fontname' size='-1'>ID</font></td><td width='27%'><font color='$table_head_textcolor' face='$fontname' size='-1'>Title</font></td><td width='25%'><font color='$table_head_textcolor' face='$fontname' size='-1'>Category</font></td><td width='8%'><font color='$table_head_textcolor' face='$fontname' size='-1'>Hits In</font></td><td width='8%'><font color='$table_head_textcolor' face='$fontname' size='-1'>Hits Out</font></td><td width='24%'><font color='$table_head_textcolor' face='$fontname' size='-1'>Actions</font></td></tr>";
	for ($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];
		$subcat = $resrow[1];
		$title = $resrow[2];
		$version = $resrow[3];
		$hitsin = $resrow[4];
		$hitsout = $resrow[5];
		$sq2 = "select cat from $tablecats where id='$subcat'";
		$reslt = mysql_query($sq2) or die("Failed: $sq2");
		$resrw = mysql_fetch_row($reslt);
		$cat = $resrw[0];
		print "<tr bgcolor='$table_bgcolor'><td width='8%'><font size='-1' face='$fontname' color='$table_textcolor'>$id</font></td><td width='27%'><font size='-1' face='$fontname' color='$table_textcolor'>$title $version</font></td><td width='25%'><font size='-1' face='$fontname' color='$table_textcolor'>$cat</font></td><td width='8%' align='center'><font size='-1' face='$fontname' color='$table_textcolor'>$hitsin</font></td><td width='8%' align='center'><font size='-1' face='$fontname' color='$table_textcolor'>$hitsout</font></td><td width='24%' align='center'><font size='-1' face='$fontname' color='$table_textcolor'><a href='modify.php?email=$email&pass=$pass&modify=$id'><font color='$table_textcolor'>Modify</font></a> <a href='modify.php?email=$email&pass=$pass&delete=$id'><font color='$table_textcolor'>Delete</font></a> <a href='modify.php?email=$email&pass=$pass&getcode=$id'><font color='$table_textcolor'>Get Code</font></a></font></td></tr>";
	}
	print "</table>";
	print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>PHP Script Index</a></center>";
	if ($footerfile) include $footerfile;
}
?>