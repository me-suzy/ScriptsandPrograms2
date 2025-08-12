<?
if (!$id) exit;
include "./config.php";

$sql = "select cat from $tablecats where id='$id'";
$result = mysql_query($sql);
$resrow = mysql_fetch_row($result);
$cat = $resrow[0];

if ($go=="1"){
	if (!$dlurl && !$homeurl) $status = "Either a Homepage URL or Download URL is required.<br>";
	if (!$title) $status = "Script Name is required.<br>";
	if (!$descr) $status = "Script description is required.<br>";
	if (!$email) $status = "Email address is required.<br>";
	if (!$password) $status = "A password is required in order to edit your listing later.<br>";
	$sql = "select * from $tablescripts where title='$title'";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if ($numrows!=0) $status = "This Script Title is already in the database.";
	if (!$status){
		$sql = "insert into $tablescripts values('', '$id', '$email', '$password', '$title', '$homeurl', '$dlurl', '$demourl', '$descr', '$price', '$version', '0', '0', now())";
		$result = mysql_query($sql) or die("Failed: $sql");
		$sql = "select ct from $tablecats where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$ct = $resrow[0];
		$ct++;
		$sql = "update $tablecats set ct='$ct' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$sql = "select id from $tablescripts where title='$title'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];

		if ($emailnotify=="1"){
			$outmsg = "Title: $title<br>Email: $email<br>HomeURL: $homeurl<br>Download: $dlurl<br>Description:<br>$descr<br><br><a href=\"".$script_index_url."cat.php?admindelete=$id\">Delete It?</a><br><br>";
			$subj = "New Script Added: $title";
			$header = "$adminemail\n";
			$header .= "MIME-Version: 1.0\n";
			$header .= "Content-Type: text/html\n";
			$header .= "Content-Transfer-Encoding: 8bit\n\n";
			$header .= "$outmsg\n";
			$z = mail($adminemail, $subj, "", $header);
		}

		$pagetitle = "$sitetitle: Your Script was Added!";
		if ($headerfile) include $headerfile;
		print "<blockquote><font size='-1' face='$fontname'>Your 
		  script has been added! But, you're not done yet. $sitetitle asks for nothing more than a link back 
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
			  <textarea name='textfield' cols='40' rows='2' wrap='VIRTUAL'><a href=\"".$script_index_url."in.php?id=$id\">$sitetitle PHP Script Index</a></textarea>
			  <br>
			  <br>
			  You may also create a link to our site any other way you choose, perhaps via 
			  a newsletter. You can use this URL:<br>
			  <br>
			  <input type='text' name='textfield2' size='40' value='".$script_index_url."in.php?id=$id'>
			  </font> 
			</form>
			<p align='center'><font face='$fontname'>[<a href='index.php'>Return 
		to $sitetitle</a>]</font></p></blockquote>";
		if ($footerfile) include $footerfile;
		exit;
	}
}
$pagetitle = "Add Your PHP Script to $sitetitle";
if ($headerfile) include $headerfile;
?>
<form name="form1" method="post" action="add.php">
  <blockquote><font color="#FF0000"><b><font size="-1" face="<? print $fontname; ?>">
    <? print $status; ?>
    </font></b></font><br>
  </blockquote>
  <table width="<? print $table_width; ?>" border="<? print $table_border; ?>" cellspacing="<? print $cellspacing; ?>" cellpadding="<? print $cellpadding; ?>" bordercolor="<? print $table_border_color; ?>" align="center">
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Script 
        Name:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"> <font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="title" value="<? print $title; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Version:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="version" value="<? print $version; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Homepage 
        URL:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"> <font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="homeurl" value="<? print $homeurl; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Demo 
        URL</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="demourl" value="<? print $demourl; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Download 
        URL:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="dlurl" value="<? print $dlurl; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Price: 
        (U.S. dollars, or enter license type) </font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="price" value="<? print $price; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Category:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">&nbsp; 
        <? print $cat; ?>
        <input type="hidden" name="site" value="<? print $site; ?>"><input type="hidden" name="id" value="<? print $id; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Description 
        (500 chars max)</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <textarea name="descr" cols="40" rows="5"><? print $descr; ?></textarea>
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Email 
        Address:</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="text" name="email" value="<? print $email; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">Choose 
        a password (required for editing later): </font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="password" name="password" value="<? print $password; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">&nbsp;</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"> <font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>"> 
        <input type="submit" value="Add Entry">
        <input type="hidden" name="go" value="1">
        </font></td>
    </tr>
    <tr> 
      <td width="47%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">&nbsp;</font></td>
      <td width="53%" bgcolor="<? print $table_bgcolor; ?>"><font size="-1" face="<? print $fontname; ?>" color="<? print $table_textcolor; ?>">&nbsp;</font></td>
    </tr>
  </table>
</form>
<? if ($footerfile) include $footerfile; ?>