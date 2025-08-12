<?

// Parses data from form and stores it in a file in the $submitdir.
function submit_site($name, $email, $site_name, $site_url, $description)
{
        global $submitdir, $memdir;
        
	if ($name && $email && $site_name && $site_url && $description)
	{
		$name = stripslashes($name);
		$email = stripslashes($email);
		$site_name = stripslashes($site_name);
		$site_url = stripslashes($site_url);
		$description = stripslashes($description);

		$data = "$name\n$email\n$site_name\n$site_url\n$description";

		$fname = date("YmdHis");

		if ( $fp = fopen($submitdir.'/'.$fname, 'w'))
		{
			fwrite($fp, $data);
		}
		return true;
	}
}

// Views all entries in $submitdir awaiting action
function view_submissions()
{
        global $submitdir, $memdir;
	$submissions=opendir("$submitdir");

	$files = array();           
	while ($file = readdir($submissions))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	for (reset ($files); list ($key, $value) = each ($files); )
	{
		$name = $value;
		$this = file("$submitdir/$value");
		for (reset ($this); list ($key, $value) = each ($this); )
		{
			if ($key == "0")
			{ 
				echo "[<A HREF=$PHP_SELF?mode=approve&site=$name>Approve Site</a>] ";
				echo "[<A HREF=$PHP_SELF?mode=reject&site=$name>Reject Site</a>]<BR>";
				echo "<B>Name:</B> $value<BR>";
			}
			if ($key == "1")
			{ 
				echo "<B>E-Mail:</B> <A HREF=mailto:$value>$value</A><BR>";
			}
			if ($key == "2")
			{ 
				echo "<B>Site Title:</B> $value<BR>";
			}
			if ($key == "3")
			{ 
				echo "<B>Site URL:</B> <A HREF=$value TARGET=_New>$value</A><BR>";
			}
			if ($key == "4")
			{ 
				echo "<B>Description:</B><BR>$value<BR>";
			}
			if ($key > "4")
			{ 
				echo "$value<BR>";
			}
		}
	echo "<HR>";
	}
}

// Virtualy identical to view_submissions, but uses $memdir and has different options.
function view_members()
{
        global $submitdir, $memdir;
	$members=opendir("$memdir");

	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	for (reset ($files); list ($key, $value) = each ($files); )
	{
		$name = $value;
		$this = file("$memdir/$value");
		for (reset ($this); list ($key, $value) = each ($this); )
		{
			if ($key == "0")
			{ 
				echo "[<A HREF=$PHP_SELF?mode=edit&site=$name>Edit Site</a>] ";
				echo "[<A HREF=$PHP_SELF?mode=delete&site=$name>Delete Site</a>]<BR>";
				echo "<B>Name:</B> $value<BR>";
			}
			if ($key == "1")
			{ 
				echo "<B>E-Mail:</B> <A HREF=mailto:$value>$value</A><BR>";
			}
			if ($key == "2")
			{ 
				echo "<B>Site Title:</B> $value<BR>";
			}
			if ($key == "3")
			{ 
				echo "<B>Site URL:</B> <A HREF=$value TARGET=_New>$value</A><BR>";
			}
			if ($key == "4")
			{ 
				echo "<B>Description:</B><BR>$value<BR>";
			}
			if ($key > "4")
			{ 
				echo "$value<BR>";
			}
		}
	echo "<HR>";
	}
}

// Add a site to the ring.
function approve_site($site)
{
        global $submitdir, $memdir, $url, $adminmail;
	$this = file("$submitdir/$site");
	$data = "";

	// Get all data from the file.
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key >= "0")
		{ 
			$value = chop($value);
			if ($data == "") { $data = $value; }
			else
			{
				$data = "$data\n$value";
			}
		}
	}

	// Extract details for confirmation email.
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "0")
		{ 
			$name = chop($value);
		}
		if ($key == "1")
		{ 
			$email = chop($value);
		}
		if ($key == "2")
		{ 
			$site_name = chop($value);
		}
		if ($key == "3")
		{ 
			$site_url = chop($value);
		}
	}

	$members=opendir("$memdir");

	// Determine the number of current members.
	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	// Add one to current and generate new ring id.
	$fname = count($files) + 1;
//	echo count($files);
	sort($files);
	$max = count($files) - 1;
	$lastfile = $files[$max];
//	echo $lastfile;
	while ($fname <= $lastfile)
	{
		$fname++;
	}
//	echo $fname;
	if ( $fp = fopen($memdir.'/'.$fname, 'w'))
	{
		// write the member file.
		fwrite($fp, $data);
	}
	// Delete the file form the submissions directory.
	unlink($submitdir.'/'.$site);

	$message = "Dear $name:\n\nWe are pleased to inform you that your site, $site_name ($site_url) has been approved and added to the web ring.\n\n Please follow the URL below to find the ring code, which you should then place on your site.\n\n$url/getcode.php?id=$fname";

	// Send confirmation email.
	mail("$email", "Web Ring - Your Site Has Been Approved", $message, "From: $adminmail\nReply-To: $adminmail\nX-Mailer: PHP/" . phpversion());

	return true;
}

// Echo site info into a form for editing.
function edit_site()
{
        global $submitdir, $memdir, $site;
	$this = file("$memdir/$site");
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "0")
		{ 
			echo "Use the fomr below to edit the information for an existing site.";
			echo "<HR>";
			echo "<FORM ACTION=$PHP_SELF?mode=edit_confirm METHOD=POST>";
			echo "<TABLE BORDER=0 CELLSPACINT=0 CELLPADDING=10>";
			echo "<TR>";
			echo "<TD WIDTH=80 VALIGN=TOP><B>Your Name</B></TD>";
			echo "<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=name SIZE=20 VALUE=\"$value\"></TD>";
			echo "</TR>";
		}

		if ($key == "1")
		{
                        echo "<TR>";
			echo "<TD WIDTH=80 VALIGN=TOP><B>Your E-Mail</B></TD>";
			echo "<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=email SIZE=20 VALUE=\"$value\"></TD>";
			echo "</TR>";
		}
		if ($key == "2")
		{ 
                        echo "<TR>";
			echo "<TD WIDTH=80 VALIGN=TOP><B>Site Name</B></TD>";
			echo "<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=site_name SIZE=30 VALUE=\"$value\"></TD>";
			echo "</TR> ";
		}
		if ($key == "3")
		{
                        echo "<TR>";
			echo "<TD WIDTH=80 VALIGN=TOP><B>Site URL</B></TD>";
			echo "<TD VALIGN=TOP><INPUT TYPE=TEXT NAME=site_url SIZE=30 VALUE=\"$value\"></TD>";
			echo "</TR>";
		}
		if ($key == "4")
		{
                        echo "<TR>";
			echo "<TD WIDTH=80 VALIGN=TOP><B>Description:</B></TD>";
			echo "<TD VALIGN=TOP><TEXTAREA NAME=description ROWS=5 COLS=25>";
			echo $value;
		}
  		if ($key > "4")
		{
                        echo $value;

		}
	}
        echo "</TEXTAREA></TD></TR>";
	echo "<TR>";
	echo "<INPUT TYPE=HIDDEN NAME=id VALUE='$site'>";
	echo "<TD COLSPAN=2 ALIGN=RIGHT><INPUT TYPE=Submit NAME=Submit VALUE=Submit></TD>";
	echo "</TR>";
	echo "</TABLE>";
}

// Write updated info to the member file.
function edit_confirm($name, $email, $site_name, $site_url, $description, $id)
{

        global $submitdir, $memdir, $name, $email, $site_name, $site_url, $description, $id;
	$data = "$name\n$email\n$site_name\n$site_url\n$description";

	if ( $fp = fopen($memdir.'/'.$id, 'w'))
	{
		fwrite($fp, $data);
	}

	return true;
}

// Just remove the file from the member directory.
function delete_site($site)
{
	global $memdir, $site;
	unlink($memdir.'/'.$site);
	return true;
}

// When a site doesn't get added....
function reject_site($site)
{
        global $submitdir, $memdir, $site, $url, $adminmail;
	$this = file("$submitdir/$site");
	$data = "";
	// Extract info for denial email.
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "0")
		{ 
			$name = chop($value);
		}
		if ($key == "1")
		{ 
			$email = chop($value);
		}
		if ($key == "2")
		{ 
			$site_name = chop($value);
		}
		if ($key == "3")
		{ 
			$site_url = chop($value);
		}
	}
	unlink($submitdir.'/'.$site);

	$message = "Dear $name:\n\nWe regret to inform you that your site, $site_name ($site_url) was declied entry into the web ring.";

	// Send message denying application.
	mail("$email", "Web Ring - Your Application Has Been Denied", $message, "From: $adminmail\nReply-To: $adminmail\nX-Mailer: PHP/" . phpversion());

}

// Redirect to a random site.
function random_site()
{
	global $memdir, $id;

	srand((double) microtime() * 1000000);

	$members=opendir("$memdir");

	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	$max = count($files);
	if ($max == '1')
	{
		$name = '1';
	}
	else
	{
		$name = rand(1,$max);
	}
	$this = file("$memdir/$name");
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "3")
		{ 
			header("Location: $value");
		}
	}
}

// Redirect to the next site in the list.
function go_next($id)
{
	global $memdir, $id;

	srand((double) microtime() * 1000000);

	$members=opendir("$memdir");

	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	$max = count($files);
	if ($id == $max)
	{
		// If the user is coming from the last site in the list, next becomes first.
		$name = '1';
	}
	else
	{
		// Otherwise, we just add one to the id.
		$name = $id + 1;
	}
	$this = file("$memdir/$name");
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "3")
		{ 
			header("Location: $value");
		}
	}
}

// Go to the previous site in the list.
function go_prev($id)
{
	global $memdir, $id;

	srand((double) microtime() * 1000000);

	$members=opendir("$memdir");

	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	$max = count($files);
	if ($id == '1')
	{
		// If the user is coming from the first site in the list, then prev becomes last.
		$name = $max;
	}
	else
	{
		// Otherwsie we subtract one to get the new id.
		$name = $id - 1;
	}
	$this = file("$memdir/$name");
	for (reset ($this); list ($key, $value) = each ($this); )
	{
		if ($key == "3")
		{ 
			header("Location: $value");
		}
	}
}

// List all sites in the ring. -- Identical to show_members() only displays less info.
function list_all()
{
        global $submitdir, $memdir;
	$members=opendir("$memdir");
        echo "<HEAD><TITLE>WebRing</TITLE><LINK REL=STYLESHEET HREF=style.css></HEAD>";
	echo "<DIV CLASS=headline>Web Ring Index</DIV>";
        echo "<DIV CLASS=normal><B>Listing All Sites</B><HR>";
	$files = array();           
	while ($file = readdir($members))
	{
		if ($file != "." && $file != "..")
		{
		        $files[] = $file; 
		}
	}

	for (reset ($files); list ($key, $value) = each ($files); )
	{
		$name = $value;
		$this = file("$memdir/$value");
		for (reset ($this); list ($key, $value) = each ($this); )
		{
			if ($key == "2")
			{ 
				$title = $value;
			}
			if ($key == "3")
			{ 
				echo "<B><A HREF=$value TARGET=_New>$title</A></B><BR>";
			}
			if ($key >= "4")
			{ 
				echo "$value<BR>";
			}
		}
	echo "<HR>";
	}
}

?>
