<?php

/*
AstaStuSito ver 0.2.6
Copyright (C) 2001 isazi <isazi@olografix.org>

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

include("image-conf.php");

# Authentication procedure

if (($User == $UserImageOK) && ($Pass == $PassImageOK))

        {
	# Copy image in default image's path
	
	copy($Image, "$ImageDir/$Image_name") or exit;
	
	# Making an archive for the images
	
	$ElementArray = "<a href=\"$ImageUrl/$Image_name\"><img src=\"$Image_name\" width=\"60\" height=\"45\" border=\"0\" alt=\"$ImageComment\"></a>";
	$ImageArch = fopen("$ImagePage", "a+") or exit;
	flock($ImageArch, 2);
	fputs($ImageArch, "$ElementArray\n");
	flock($ImageArch, 3);
	fclose($ImageArch);
	$ImageArch = fopen("$ImagePage", "r+") or exit;
	flock($ImageArch, 2);
	while (!feof($ImageArch))
		{
		$ArrayImageTemp[] = fgets($ImageArch, 4096);
		}
	flock($ImageArch, 3);
	fclose($ImageArch);
	$ArrayImage = array_reverse($ArrayImageTemp);
	
	# Make a trick for delete the first element that is null
	
	$Trash = array_shift($ArrayImage);
	
	# Making the index page
	
	$ImageHtml1 = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>$ImageHtmlTitle</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$CSS\"></link>
</head>
<body>
<br />
<h2 align=\"center\">$ImageHtmlTitle</h2>
<br />
<p align=\"center\">$ImageHtmlPar</p>
<br />
<br />
<table align=\"center\" border=\"0\">
<tr>";
	
	$RowTemp = 1;
	foreach ($ArrayImage as $Value)
		{
		if ($RowTemp == $ImageRow)
			{
			$ImageHtml1 .= "<td>$Value</td>\n";
			$ImageHtml1 .= "</tr>\n<tr>";
			$RowTemp = 1;
			}
		else
			{
			$ImageHtml1 .= "<td>$Value</td>\n";
			$RowTemp++;
			}
		}
	
	$ImageHtml2 = "</tr>
</table>
<br />
<br />
</body>
</html>";
	
	# Then write the index in it's place

	if (file_exists($ImageHtmlName))
		{
		unlink("$ImageHtmlName");
		$ImageHtmlPage = fopen("$ImageHtmlName", "w") or exit;
		flock($ImageHtmlPage, 2);
		fputs($ImageHtmlPage, "$ImageHtml1"."$ImageHtml2");
		flock($ImageHtmlPage, 3);
		fclose($ImageHtmlPage);
		}
	else
		{
		$ImageHtmlPage = fopen("$ImageHtmlName", "w") or exit;
		flock($ImageHtmlPage, 2);
		fputs($ImageHtmlPage, "$ImageHtml1"."$ImageHtml2");
		flock($ImageHtmlPage, 3);
		fclose($ImageHtmlPage);
		}
	
	# Print a confirmation message using the function MakeXhtmlMessage()
	# initizialized with $Title and $Message respectively, and exit

	$Title = "Image Uploaded";
	$Message = "The image was succesfully uploaded and the index was made.<br />
Come and see the <a href=\"$ImageHtmlName\">Index</a>";
	
	print(MakeXhtmlMessage($Title, $Message));
	exit;
	
	}
else
	{
	# Print a "logon failed" page using the function MakeXhtmlMessage()
	# initizialized with $Title and $Message respectively, and exit

	$Title = "Logon Failed";
	$Message = "Your logon has failed, please SHUT UP AND DIE :-)";

	print(MakeXhtmlMessage($Title, $Message));
	exit;

	}
?>
