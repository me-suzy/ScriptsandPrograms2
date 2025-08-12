<?php

/*
AstaStuSito ver 0.2.6
Copyright (C) 2001-2002 isazi <isazi@olografix.org>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

# Include 

include("../back/auth-conf.php");
include("../back/message-func.php");

include("news-func.php");
include("news-conf.php");

# Authentication procedure 

if (($User == $UserNewsOK) && ($Pass == $PassNewsOK)) {
	
	# First check if page's name is correct, past open page 
	# for writing or exit
	
	if ( !strstr($Page, ".") ) {
		
		$Page .= ".html";
	}
	
	$File = fopen("$ArchDir/$Page","w") or exit;
	flock($File, 2);

	# Make the page calling the function MakeXhtmlPage() with $Title,
	# $Author and $Article respectively, write and close the file

	fputs($File,MakeXhtmlPage($Title, $Author, $Article));
	flock($File, 3);
	fclose($File);

	# Clean $Title for use in successive array

	$Title = str_replace("\\\"", "``", $Title);
	$Title = str_replace("\\'", "`", $Title);
	$Title = htmlentities($Title);

	# Make an array to contain the link at the articles

	$ElementArray = "<a href=\"$ArchUrl/$Page\">$Title</a><br /><br />";
	$FileArch = fopen("$ArchPage","a+") or exit;
	flock($FileArch, 2);
	fputs($FileArch,"$ElementArray\n");
	flock($FileArch, 3);
	fclose($FileArch);
	$FileArch = fopen("$ArchPage","r+") or exit;
	flock($FileArch, 2);
	while (!feof($FileArch)) {
		
		$ArrayArchTemp[] = fgets($FileArch,4096);
	}
	flock($FileArch, 3);
	fclose($FileArch);
	$ArrayArch = array_reverse($ArrayArchTemp);
	
        # Make a trick for delete the first element that is null
	
	$Trash = array_shift($ArrayArch);
			
	# Make an index page for the articles
	
	$ArtHtml1 = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>$ArtHtmlTitle</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$CSS\"></link>
</head>
<body>
<br />
<h2 align=\"center\">$ArtHtmlTitle</h2>
<br />
<p align=\"center\">$ArtHtmlPar</p>
<br />
<br />
<p align=\"center\">";

	foreach($ArrayArch as $ArrayVal) {
		
		$ArtHtml1 .= $ArrayVal;
	}

	$ArtHtml2 = "<br />\n</p>\n</body>\n</html>";

	# Write the index in it's directory

	if (file_exists("$ArtHtmlName")) {
		unlink("$ArtHtmlName");
	}

	$ArtHtmlPage = fopen("$ArtHtmlName","w") or exit;
	flock($ArtHtmlPage, 2);
	fputs($ArtHtmlPage, "$ArtHtml1"."$ArtHtml2");
	flock($ArtHtmlPage, 3);
	fclose($ArtHtmlPage);

	# Print a confirmation's page using the function MakeXhtmlMessage()
	# initizialized with $Title and $Message respectively, and exit
	
	$Title = "Submission OK";
	$Message = "The page has been formatted and indexed.<br />\nCome back to see the <a href=\"$ArtHtmlName\">page</a>";
	
	print(MakeXhtmlMessage($Title, $Message));
	exit;
	
}

else {
	
        # Print a "logon failed" page using the function MakeXhtmlMessage()
        # initizialized with $Title and $Message respectively, and exit

	$Title = "Logon Failed";
	$Message = "Your logon has failed, please SHUT UP AND DIE :-)";
	
	print(MakeXhtmlMessage($Title, $Message));
	exit;
}

?>
